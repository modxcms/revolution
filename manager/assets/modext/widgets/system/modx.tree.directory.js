/**
 * Generates the Directory Tree
 * 
 * @class MODx.tree.Directory
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-directory
 */
MODx.tree.Directory = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        rootVisible: false
        ,root_id: 'root'
        ,title: _('files')
        ,ddAppendOnly: true
        ,enableDrag: true
        ,enableDrop: true
        ,ddGroup: 'modx-treedrop-dd'
        ,url: MODx.config.connectors_url+'browser/directory.php'
        ,baseParams: {
            prependPath: config.prependPath || null
            ,hideFiles: config.hideFiles || false
        }
        ,action: 'getList'
        ,primaryKey: 'dir'
        ,useDefaultToolbar: true
        ,tbar: [{
            icon: MODx.config.template_url+'images/restyle/icons/folder.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('file_folder_create')}
            ,handler: this.createDirectory
            ,scope: this
            ,hidden: MODx.perm.directory_create ? false : true
        },{
            icon: MODx.config.template_url+'images/restyle/icons/file_upload.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('upload_files')}
            ,handler: this.uploadFiles
            ,scope: this
            ,hidden: MODx.perm.file_upload ? false : true
        },'->',{
            icon: MODx.config.template_url+'images/restyle/icons/file_manager.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('modx_browser')}
            ,handler: this.loadFileManager
            ,scope: this
            ,hidden: MODx.perm.file_manager && !MODx.browserOpen ? false : true
        }]
    });
    MODx.tree.Directory.superclass.constructor.call(this,config);
    this.addEvents({
        'beforeUpload': true
        ,'afterUpload': true
        ,'fileBrowserSelect': true
    });
};
Ext.extend(MODx.tree.Directory,MODx.tree.Tree,{
    windows: {}
    ,_initExpand: function() {
        var treeState = Ext.state.Manager.get(this.treestate_id);
        this.expandPath(treeState,'text');
    }
    ,_saveState: function(n) {
        var p = n.getPath('text');
        Ext.state.Manager.set(this.treestate_id,p);
    }
    ,_handleDrop: function(e) { return false; }
    ,_showContextMenu: function(n,e) {
        n.select();
        this.cm.activeNode = n;
        this.cm.removeAll();
        if (n.attributes.menu && n.attributes.menu.items) {
            this.addContextMenuItem(n.attributes.menu.items);
            this.cm.show(n.getUI().getEl(),'t?');
        } else {
            var m = [];
            switch (n.attributes.type) {
                case 'dir':
                    m = this._getDirectoryMenu(n);
                    break;
                default:
                    m = this._getFileMenu(n);
                    break;
            }

            this.addContextMenuItem(m);
            this.cm.showAt(e.xy);
        }
        e.stopEvent();
    }

    ,_getFileMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];

        if (ui.hasClass('pupdate')) {
            m.push({
                text: _('file_edit')
                ,file: a.file
                ,handler: function(itm,e) {
                    this.loadAction('a='+MODx.action['system/file/edit']+'&file='+itm.file);
                }
            });
            m.push({
                text: _('rename')
                ,handler: this.renameFile
            });
        }
        if (ui.hasClass('premove')) {
            if (m.length > 0) { m.push('-'); }
            m.push({
                text: _('file_remove')
                ,handler: this.removeFile
            });
        }
        return m;
    }

    ,_getDirectoryMenu: function(n) {
        var ui = n.getUI();
        var m = [];
        if (ui.hasClass('pcreate')) {
            m.push({
                text: _('file_folder_create_here')
                ,handler: this.createDirectory
            });
        }
        if (ui.hasClass('pchmod')) {
            m.push({
                text: _('file_folder_chmod')
                ,handler: this.chmodDirectory
            });
        }
        if (ui.hasClass('pupdate')) {
            m.push({
                text: _('rename')
                ,handler: this.renameFile
            });
        }
        m.push({
            text: _('directory_refresh')
            ,handler: this.refreshActiveNode
        });
        if (ui.hasClass('pupload')) {
            m.push('-');
            m.push({
                text: _('upload_files')
                ,handler: this.uploadFiles
            });
        }
        if (ui.hasClass('premove')) {
            m.push('-');
            m.push({
                text: _('file_folder_remove')
                ,handler: this.removeDirectory
            });
        }
        return m;
    }

    
    ,getPath:function(node) {
        var path, p, a;

        // get path for non-root node
        if(node !== this.root) {
            p = node.parentNode;
            a = [node.text];
            while(p && p !== this.root) {
                a.unshift(p.text);
                p = p.parentNode;
            }
            a.unshift(this.root.attributes.path || '');
            path = a.join(this.pathSeparator);
        }

        // path for root node is it's path attribute
        else {
            path = node.attributes.path || '';
        }

        // a little bit of security: strip leading / or .
        // full path security checking has to be implemented on server
        path = path.replace(/^[\/\.]*/, '');
        return path+'/';
    }

    ,browser: null
    ,loadFileManager: function(btn,e) {
        if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'modx-browser'
                ,hideFiles: false
                ,rootVisible: false
                ,listeners: {
                    'select': {fn: function(data) {
                        this.fireEvent('fileBrowserSelect',data);
                    },scope:this}
                }
            });
        }
        if (this.browser) {
            this.browser.show();
        }
    }
    
    ,renameNode: function(field,nv,ov) {
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'browser/index.php'
            ,params: {
                action: 'rename'
                ,new_name: nv
                ,old_name: ov
                ,prependPath: this.config.prependPath || null
                ,file: this.treeEditor.editNode.id
            }
            ,listeners: {
               'success': {fn:this.refresh,scope:this}
            }
        });
    }
	
    ,renameFile: function(item,e) {
        var node = this.cm.activeNode;
        var r = {
            oldname: node.text
            ,newname: node.text
            ,path: node.id
        };
        if (!this.windows.rename) {
            this.windows.rename = MODx.load({
                xtype: 'modx-window-file-rename'
                ,record: r
                ,prependPath: this.config.prependPath || null
                ,listeners: {
                    'success':{fn:this.refresh,scope:this}
                }
            });
        }
        this.windows.rename.setValues(r);
        this.windows.rename.show(e.target);
    }
    
    ,createDirectory: function(item,e) {
        var node = this.cm && this.cm.activeNode ? this.cm.activeNode : false;
        var r = {parent: node ? node.id : '/'};
        if (!this.windows.create) {
            this.windows.create = MODx.load({
                xtype: 'modx-window-directory-create'
                ,record: r
                ,prependPath: this.config.prependPath || null
                ,listeners: {
                    'success':{fn:this.refresh,scope:this}
                }
            });
        }
        this.windows.create.setValues(r);
        this.windows.create.show(e ? e.target : Ext.getBody());
    }
	
    ,chmodDirectory: function(item,e) {
        var node = this.cm.activeNode;
        var r = {dir: node.id,mode: node.attributes.perms};
        if (!this.windows.chmod) {
            this.windows.chmod = MODx.load({
                xtype: 'modx-window-directory-chmod'
                ,record: r
                ,prependPath: this.config.prependPath || null
                ,listeners: {
                    'success':{fn:this.refresh,scope:this}
                }
            });
        }
        this.windows.chmod.setValues(r);
        this.windows.chmod.show(e.target);
    }

    ,removeDirectory: function(item,e) {
        var node = this.cm.activeNode;
        MODx.msg.confirm({
            text: _('file_folder_remove_confirm')
            ,url: MODx.config.connectors_url+'browser/directory.php'
            ,params: {
                action: 'remove'
                ,dir: node.id
                ,prependPath: this.config.prependPath || null
            }
            ,listeners: {
                'success':{fn:this.refreshParentNode,scope:this}
            }
        });
    }

    ,removeFile: function(item,e) {
        var node = this.cm.activeNode;
        MODx.msg.confirm({
            text: _('file_confirm_remove')
            ,url: MODx.config.connectors_url+'browser/file.php'
            ,params: {
                action: 'remove'
                ,file: node.id
                ,prependPath: this.config.prependPath || null
            }
            ,listeners: {
                'success':{fn:this.refreshParentNode,scope:this}
            }
        });
    }
    
    ,uploadFiles: function(btn,e) {
        if (!this.uploader) {
            this.uploader = new Ext.ux.UploadDialog.Dialog({
                url: MODx.config.connectors_url+'browser/file.php'
                ,base_params: {
                    action: 'upload'
                    ,prependPath: this.config.prependPath || null
                    ,prependUrl: this.config.prependUrl || null
                }
                ,reset_on_hide: true
                ,width: 550
                ,cls: 'ext-ux-uploaddialog-dialog modx-upload-window'
            });
            this.uploader.on('show',this.beforeUpload,this);
            this.uploader.on('uploadsuccess',this.uploadSuccess,this);
        }
        this.uploader.show(btn);
    }
    
    ,uploadSuccess:function() {
        if (this.cm.activeNode) {
            var node = this.cm.activeNode;
            (node.isLeaf() ? node.parentNode : node).reload();
            this.fireEvent('afterUpload',node);
        } else {
            this.refresh();
        }
    }    
    ,beforeUpload: function() {
        var path;
        if (this.cm.activeNode) {
            path = this.getPath(this.cm.activeNode);
            if(this.cm.activeNode.isLeaf()) {
               path = path.replace(/\/[^\/]+$/, '', path);
            }
        } else { path = '/'; }

        this.uploader.setBaseParams({
            action: 'upload'
            ,prependPath: this.config.prependPath || null
            ,prependUrl: this.config.prependUrl || null
            ,path: path
        });
        this.fireEvent('beforeUpload',this.cm.activeNode);
    }


    
});
Ext.reg('modx-tree-directory',MODx.tree.Directory);

/** 
 * Generates the Create Directory window
 * 
 * @class MODx.window.CreateDirectory
 * @extends MODx.Window
 * @param {Object} config An object of configuration options.
 * @xtype modx-window-directory-create
 */
MODx.window.CreateDirectory = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        width: 430
        ,height: 200
        ,title: _('file_folder_create')
        ,url: MODx.config.connectors_url+'browser/directory.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'prependPath'
            ,value: config.prependPath || null
        },{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,width: 150
            ,allowBlank: false
        },{
            fieldLabel: _('file_folder_parent')
            ,name: 'parent'
            ,xtype: 'textfield'
            ,width: 200
        }]
    });
    MODx.window.CreateDirectory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateDirectory,MODx.Window);
Ext.reg('modx-window-directory-create',MODx.window.CreateDirectory);

/** 
 * Generates the Chmod Directory window
 * 
 * @class MODx.window.ChmodDirectory
 * @extends MODx.Window
 * @param {Object} config An object of configuration options.
 * @xtype modx-window-directory-chmod
 */
MODx.window.ChmodDirectory = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('file_folder_chmod')
        ,width: 430
        ,height: 200
        ,url: MODx.config.connectors_url+'browser/directory.php'
        ,action: 'chmod'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'prependPath'
            ,value: config.prependPath || null
        },{
            fieldLabel: _('mode')
            ,name: 'mode'
            ,xtype: 'textfield'
            ,width: 150
            ,allowBlank: false
        },{
            name: 'dir'
            ,xtype: 'hidden'
            ,width: 200
        }]
    });
    MODx.window.ChmodDirectory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.ChmodDirectory,MODx.Window);
Ext.reg('modx-window-directory-chmod',MODx.window.ChmodDirectory);


MODx.window.RenameFile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('rename')
        ,width: 430
        ,height: 200
        ,url: MODx.config.connectors_url+'browser/index.php'
        ,action: 'rename'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'prependPath'
            ,value: config.prependPath || null
        },{
            fieldLabel: _('path')
            ,name: 'path'
            ,xtype: 'statictextfield'
            ,submitValue: true
            ,width: 400
        },{
            fieldLabel: _('old_name')
            ,name: 'oldname'
            ,xtype: 'statictextfield'
            ,width: 300
        },{
            fieldLabel: _('new_name')
            ,name: 'newname'
            ,xtype: 'textfield'
            ,width: 300
            ,allowBlank: false
        },{
            name: 'dir'
            ,xtype: 'hidden'
            ,width: 200
        }]
    });
    MODx.window.RenameFile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RenameFile,MODx.Window);
Ext.reg('modx-window-file-rename',MODx.window.RenameFile);

Ext.namespace("Ext.ux.Utils");Ext.ux.Utils.EventQueue=function(handler,scope){if(!handler){throw"Handler is required."}this.handler=handler;this.scope=scope||window;this.queue=[];this.is_processing=false;this.postEvent=function(event,data){data=data||null;this.queue.push({event:event,data:data});if(!this.is_processing){this.process()}};this.flushEventQueue=function(){this.queue=[]},this.process=function(){while(this.queue.length>0){this.is_processing=true;var event_data=this.queue.shift();this.handler.call(this.scope,event_data.event,event_data.data)}this.is_processing=false}};Ext.ux.Utils.FSA=function(initial_state,trans_table,trans_table_scope){this.current_state=initial_state;this.trans_table=trans_table||{};this.trans_table_scope=trans_table_scope||window;Ext.ux.Utils.FSA.superclass.constructor.call(this,this.processEvent,this)};Ext.extend(Ext.ux.Utils.FSA,Ext.ux.Utils.EventQueue,{current_state:null,trans_table:null,trans_table_scope:null,state:function(){return this.current_state},processEvent:function(event,data){var transitions=this.currentStateEventTransitions(event);if(!transitions){throw"State '"+this.current_state+"' has no transition for event '"+event+"'."}for(var i=0,len=transitions.length;i<len;i++){var transition=transitions[i];var predicate=transition.predicate||transition.p||true;var action=transition.action||transition.a||Ext.emptyFn;var new_state=transition.state||transition.s||this.current_state;var scope=transition.scope||this.trans_table_scope;if(this.computePredicate(predicate,scope,data,event)){this.callAction(action,scope,data,event);this.current_state=new_state;return}}throw"State '"+this.current_state+"' has no transition for event '"+event+"' in current context"},currentStateEventTransitions:function(event){return this.trans_table[this.current_state]?this.trans_table[this.current_state][event]||false:false},computePredicate:function(predicate,scope,data,event){var result=false;switch(Ext.type(predicate)){case"function":result=predicate.call(scope,data,event,this);break;case"array":result=true;for(var i=0,len=predicate.length;result&&(i<len);i++){if(Ext.type(predicate[i])=="function"){result=predicate[i].call(scope,data,event,this)}else{throw ["Predicate: ",predicate[i],' is not callable in "',this.current_state,'" state for event "',event].join("")}}break;case"boolean":result=predicate;break;default:throw ["Predicate: ",predicate,' is not callable in "',this.current_state,'" state for event "',event].join("")}return result},callAction:function(action,scope,data,event){switch(Ext.type(action)){case"array":for(var i=0,len=action.length;i<len;i++){if(Ext.type(action[i])=="function"){action[i].call(scope,data,event,this)}else{throw ["Action: ",action[i],' is not callable in "',this.current_state,'" state for event "',event].join("")}}break;case"function":action.call(scope,data,event,this);break;default:throw ["Action: ",action,' is not callable in "',this.current_state,'" state for event "',event].join("")}}});Ext.namespace("Ext.ux.UploadDialog");Ext.ux.UploadDialog.BrowseButton=Ext.extend(Ext.Button,{input_name:"file",input_file:null,original_handler:null,original_scope:null,initComponent:function(){Ext.ux.UploadDialog.BrowseButton.superclass.initComponent.call(this);this.original_handler=this.handler||null;this.original_scope=this.scope||window;this.handler=null;this.scope=null},onRender:function(ct,position){Ext.ux.UploadDialog.BrowseButton.superclass.onRender.call(this,ct,position);this.createInputFile()},createInputFile:function(){var button_container=this.el.child("tbody");button_container.position("relative");this.wrap=this.el.wrap({cls:"tbody"});this.input_file=this.wrap.createChild({tag:"input",type:"file",size:1,name:this.input_name||Ext.id(this.el),style:"position: absolute; display: block; border: none; cursor: pointer"});this.input_file.setOpacity(0);var button_box=button_container.getBox();this.input_file.setStyle("font-size",(button_box.width*0.5)+"px");var input_box=this.input_file.getBox();var adj={x:3,y:3};if(Ext.isIE){adj={x:0,y:3}}this.input_file.setLeft(button_box.width-input_box.width+adj.x+"px");this.input_file.setTop(button_box.height-input_box.height+adj.y+"px");this.input_file.setOpacity(0);if(this.handleMouseEvents){this.input_file.on("mouseover",this.onMouseOver,this);this.input_file.on("mousedown",this.onMouseDown,this)}if(this.tooltip){if(typeof this.tooltip=="object"){Ext.QuickTips.register(Ext.apply({target:this.input_file},this.tooltip))}else{this.input_file.dom[this.tooltipType]=this.tooltip}}this.input_file.on("change",this.onInputFileChange,this);this.input_file.on("click",function(e){e.stopPropagation()})},detachInputFile:function(no_create){var result=this.input_file;no_create=no_create||false;if(typeof this.tooltip=="object"){Ext.QuickTips.unregister(this.input_file)}else{this.input_file.dom[this.tooltipType]=null}this.input_file.removeAllListeners();this.input_file=null;if(!no_create){this.createInputFile()}return result},getInputFile:function(){return this.input_file},disable:function(){Ext.ux.UploadDialog.BrowseButton.superclass.disable.call(this);this.input_file.dom.disabled=true},enable:function(){Ext.ux.UploadDialog.BrowseButton.superclass.enable.call(this);this.input_file.dom.disabled=false},destroy:function(){var input_file=this.detachInputFile(true);input_file.remove();input_file=null;Ext.ux.UploadDialog.BrowseButton.superclass.destroy.call(this)},onInputFileChange:function(){if(this.original_handler){this.original_handler.call(this.original_scope,this)}}});Ext.ux.UploadDialog.TBBrowseButton=Ext.extend(Ext.ux.UploadDialog.BrowseButton,{hideParent:true,onDestroy:function(){Ext.ux.UploadDialog.TBBrowseButton.superclass.onDestroy.call(this);if(this.container){this.container.remove()}}});Ext.ux.UploadDialog.FileRecord=Ext.data.Record.create([{name:"filename"},{name:"state",type:"int"},{name:"note"},{name:"input_element"}]);Ext.ux.UploadDialog.FileRecord.STATE_QUEUE=0;Ext.ux.UploadDialog.FileRecord.STATE_FINISHED=1;Ext.ux.UploadDialog.FileRecord.STATE_FAILED=2;Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING=3;Ext.ux.UploadDialog.Dialog=function(config){var default_config={border:false,width:450,height:350,minWidth:450,minHeight:350,plain:true,constrainHeader:true,draggable:true,closable:true,maximizable:false,minimizable:false,resizable:true,layout:"fit",region:"center",autoDestroy:true,closeAction:"hide",title:this.i18n.title,cls:"ext-ux-uploaddialog-dialog",url:"",base_params:{},permitted_extensions:[],reset_on_hide:true,allow_close_on_upload:false,upload_autostart:false,Make_Reload:false,post_var_name:"file"};config=Ext.applyIf(config||{},default_config);config.layout="absolute";Ext.ux.UploadDialog.Dialog.superclass.constructor.call(this,config)};Ext.extend(Ext.ux.UploadDialog.Dialog,Ext.Window,{fsa:null,state_tpl:null,form:null,grid_panel:null,progress_bar:null,is_uploading:false,initial_queued_count:0,upload_frame:null,initComponent:function(){Ext.ux.UploadDialog.Dialog.superclass.initComponent.call(this);var tt={created:{"window-render":[{action:[this.createForm,this.createProgressBar,this.createGrid],state:"rendering"}],destroy:[{action:this.flushEventQueue,state:"destroyed"}]},rendering:{"grid-render":[{action:[this.fillToolbar,this.updateToolbar],state:"ready"}],destroy:[{action:this.flushEventQueue,state:"destroyed"}]},ready:{"file-selected":[{predicate:[this.fireFileTestEvent,this.isPermittedFile],action:this.addFileToUploadQueue,state:"adding-file"},{}],"grid-selection-change":[{action:this.updateToolbar}],"remove-files":[{action:[this.removeFiles,this.fireFileRemoveEvent]}],"reset-queue":[{action:[this.resetQueue,this.fireResetQueueEvent]}],"start-upload":[{predicate:this.hasUnuploadedFiles,action:[this.setUploadingFlag,this.saveInitialQueuedCount,this.updateToolbar,this.updateProgressBar,this.prepareNextUploadTask,this.fireUploadStartEvent],state:"uploading"},{}],"stop-upload":[{}],hide:[{predicate:[this.isNotEmptyQueue,this.getResetOnHide],action:[this.resetQueue,this.fireResetQueueEvent]},{}],destroy:[{action:this.flushEventQueue,state:"destroyed"}]},"adding-file":{"file-added":[{predicate:this.isUploading,action:[this.incInitialQueuedCount,this.updateProgressBar,this.fireFileAddEvent],state:"uploading"},{predicate:this.getUploadAutostart,action:[this.startUpload,this.fireFileAddEvent],state:"ready"},{action:[this.updateToolbar,this.fireFileAddEvent],state:"ready"}]},uploading:{"file-selected":[{predicate:[this.fireFileTestEvent,this.isPermittedFile],action:this.addFileToUploadQueue,state:"adding-file"},{}],"grid-selection-change":[{}],"start-upload":[{}],"stop-upload":[{predicate:this.hasUnuploadedFiles,action:[this.resetUploadingFlag,this.abortUpload,this.updateToolbar,this.updateProgressBar,this.fireUploadStopEvent],state:"ready"},{action:[this.resetUploadingFlag,this.abortUpload,this.updateToolbar,this.updateProgressBar,this.fireUploadStopEvent,this.fireUploadCompleteEvent],state:"ready"}],"file-upload-start":[{action:[this.uploadFile,this.findUploadFrame,this.fireFileUploadStartEvent]}],"file-upload-success":[{predicate:this.hasUnuploadedFiles,action:[this.resetUploadFrame,this.updateRecordState,this.updateProgressBar,this.prepareNextUploadTask,this.fireUploadSuccessEvent]},{action:[this.resetUploadFrame,this.resetUploadingFlag,this.updateRecordState,this.updateToolbar,this.updateProgressBar,this.fireUploadSuccessEvent,this.fireUploadCompleteEvent],state:"ready"}],"file-upload-error":[{predicate:this.hasUnuploadedFiles,action:[this.resetUploadFrame,this.updateRecordState,this.updateProgressBar,this.prepareNextUploadTask,this.fireUploadErrorEvent]},{action:[this.resetUploadFrame,this.resetUploadingFlag,this.updateRecordState,this.updateToolbar,this.updateProgressBar,this.fireUploadErrorEvent,this.fireUploadCompleteEvent],state:"ready"}],"file-upload-failed":[{predicate:this.hasUnuploadedFiles,action:[this.resetUploadFrame,this.updateRecordState,this.updateProgressBar,this.prepareNextUploadTask,this.fireUploadFailedEvent]},{action:[this.resetUploadFrame,this.resetUploadingFlag,this.updateRecordState,this.updateToolbar,this.updateProgressBar,this.fireUploadFailedEvent,this.fireUploadCompleteEvent],state:"ready"}],hide:[{predicate:this.getResetOnHide,action:[this.stopUpload,this.repostHide]},{}],destroy:[{predicate:this.hasUnuploadedFiles,action:[this.resetUploadingFlag,this.abortUpload,this.fireUploadStopEvent,this.flushEventQueue],state:"destroyed"},{action:[this.resetUploadingFlag,this.abortUpload,this.fireUploadStopEvent,this.fireUploadCompleteEvent,this.flushEventQueue],state:"destroyed"}]},destroyed:{}};this.fsa=new Ext.ux.Utils.FSA("created",tt,this);this.addEvents({filetest:true,fileadd:true,fileremove:true,resetqueue:true,uploadsuccess:true,uploaderror:true,uploadfailed:true,uploadstart:true,uploadstop:true,uploadcomplete:true,fileuploadstart:true});this.on("render",this.onWindowRender,this);this.on("beforehide",this.onWindowBeforeHide,this);this.on("hide",this.onWindowHide,this);this.on("destroy",this.onWindowDestroy,this);this.state_tpl=new Ext.Template("<div class='ext-ux-uploaddialog-state ext-ux-uploaddialog-state-{state}'> </div>").compile()},createForm:function(){this.form=Ext.DomHelper.append(this.body,{tag:"form",method:"post",action:this.url,style:"position: absolute; left: -100px; top: -100px; width: 100px; height: 100px; clear: both;"})},createProgressBar:function(){this.progress_bar=this.add(new Ext.ProgressBar({x:0,y:0,anchor:"0",value:0,text:this.i18n.progress_waiting_text}))},createGrid:function(){var store=new Ext.data.Store({proxy:new Ext.data.MemoryProxy([]),reader:new Ext.data.JsonReader({},Ext.ux.UploadDialog.FileRecord),sortInfo:{field:"state",direction:"DESC"},pruneModifiedRecords:true});var cm=new Ext.grid.ColumnModel([{header:this.i18n.state_col_title,width:this.i18n.state_col_width,resizable:false,dataIndex:"state",sortable:true,renderer:this.renderStateCell.createDelegate(this)},{header:this.i18n.filename_col_title,width:this.i18n.filename_col_width,dataIndex:"filename",sortable:true,renderer:this.renderFilenameCell.createDelegate(this)},{header:this.i18n.note_col_title,width:this.i18n.note_col_width,dataIndex:"note",sortable:true,renderer:this.renderNoteCell.createDelegate(this)}]);this.grid_panel=new Ext.grid.GridPanel({ds:store,cm:cm,layout:"fit",height:this.height-100,region:"center",x:0,y:22,border:true,viewConfig:{autoFill:true,forceFit:true},bbar:new Ext.Toolbar()});this.grid_panel.on("render",this.onGridRender,this);this.add(this.grid_panel);this.grid_panel.getSelectionModel().on("selectionchange",this.onGridSelectionChange,this)},fillToolbar:function(){var tb=this.grid_panel.getBottomToolbar();tb.x_buttons={};tb.x_buttons.add=tb.addItem(new Ext.ux.UploadDialog.TBBrowseButton({input_name:this.post_var_name,text:this.i18n.add_btn_text,tooltip:this.i18n.add_btn_tip,iconCls:"ext-ux-uploaddialog-addbtn",handler:this.onAddButtonFileSelected,scope:this}));tb.x_buttons.remove=tb.addButton({text:this.i18n.remove_btn_text,tooltip:this.i18n.remove_btn_tip,iconCls:"ext-ux-uploaddialog-removebtn",handler:this.onRemoveButtonClick,scope:this});tb.x_buttons.reset=tb.addButton({text:this.i18n.reset_btn_text,tooltip:this.i18n.reset_btn_tip,iconCls:"ext-ux-uploaddialog-resetbtn",handler:this.onResetButtonClick,scope:this});tb.add("-");tb.x_buttons.upload=tb.addButton({text:this.i18n.upload_btn_start_text,tooltip:this.i18n.upload_btn_start_tip,iconCls:"ext-ux-uploaddialog-uploadstartbtn",handler:this.onUploadButtonClick,scope:this});tb.add("-");tb.x_buttons.close=tb.addButton({text:this.i18n.close_btn_text,tooltip:this.i18n.close_btn_tip,handler:this.onCloseButtonClick,scope:this})},renderStateCell:function(data,cell,record,row_index,column_index,store){return this.state_tpl.apply({state:data})},renderFilenameCell:function(data,cell,record,row_index,column_index,store){var view=this.grid_panel.getView();var f=function(){try{Ext.fly(view.getCell(row_index,column_index)).child(".x-grid3-cell-inner").dom.qtip=data}catch(e){}};f.defer(1000);return data},renderNoteCell:function(data,cell,record,row_index,column_index,store){var view=this.grid_panel.getView();var f=function(){try{Ext.fly(view.getCell(row_index,column_index)).child(".x-grid3-cell-inner").dom.qtip=data}catch(e){}};f.defer(1000);return data},getFileExtension:function(filename){var result=null;var parts=filename.split(".");if(parts.length>1){result=parts.pop()}return result},isPermittedFileType:function(filename){var result=true;if(this.permitted_extensions.length>0){result=this.permitted_extensions.indexOf(this.getFileExtension(filename))!=-1}return result},isPermittedFile:function(browse_btn){var result=false;var filename=browse_btn.getInputFile().dom.value;if(this.isPermittedFileType(filename)){result=true}else{Ext.Msg.alert(this.i18n.error_msgbox_title,String.format(this.i18n.err_file_type_not_permitted,filename,this.permitted_extensions.join(this.i18n.permitted_extensions_join_str)));result=false}return result},fireFileTestEvent:function(browse_btn){return this.fireEvent("filetest",this,browse_btn.getInputFile().dom.value)!==false},addFileToUploadQueue:function(browse_btn){var input_file=browse_btn.detachInputFile();input_file.appendTo(this.form);input_file.setStyle("width","100px");input_file.dom.disabled=true;var store=this.grid_panel.getStore();store.add(new Ext.ux.UploadDialog.FileRecord({state:Ext.ux.UploadDialog.FileRecord.STATE_QUEUE,filename:input_file.dom.value,note:this.i18n.note_queued_to_upload,input_element:input_file}));this.fsa.postEvent("file-added",input_file.dom.value)},fireFileAddEvent:function(filename){this.fireEvent("fileadd",this,filename)},updateProgressBar:function(){if(this.is_uploading){var queued=this.getQueuedCount(true);var value=1-queued/this.initial_queued_count;this.progress_bar.updateProgress(value,String.format(this.i18n.progress_uploading_text,this.initial_queued_count-queued,this.initial_queued_count))}else{this.progress_bar.updateProgress(0,this.i18n.progress_waiting_text)}},updateToolbar:function(){var tb=this.grid_panel.getBottomToolbar();if(this.is_uploading){tb.x_buttons.remove.disable();tb.x_buttons.reset.disable();tb.x_buttons.upload.enable();if(!this.getAllowCloseOnUpload()){tb.x_buttons.close.disable()}tb.x_buttons.upload.setIconClass("ext-ux-uploaddialog-uploadstopbtn");tb.x_buttons.upload.setText(this.i18n.upload_btn_stop_text);tb.x_buttons.upload.getEl().child(tb.x_buttons.upload.buttonSelector).dom[tb.x_buttons.upload.tooltipType]=this.i18n.upload_btn_stop_tip}else{tb.x_buttons.remove.enable();tb.x_buttons.reset.enable();tb.x_buttons.close.enable();tb.x_buttons.upload.setIconClass("ext-ux-uploaddialog-uploadstartbtn");tb.x_buttons.upload.setText(this.i18n.upload_btn_start_text);if(this.getQueuedCount()>0){tb.x_buttons.upload.enable()}else{tb.x_buttons.upload.disable()}if(this.grid_panel.getSelectionModel().hasSelection()){tb.x_buttons.remove.enable()}else{tb.x_buttons.remove.disable()}if(this.grid_panel.getStore().getCount()>0){tb.x_buttons.reset.enable()}else{tb.x_buttons.reset.disable()}}},saveInitialQueuedCount:function(){this.initial_queued_count=this.getQueuedCount()},incInitialQueuedCount:function(){this.initial_queued_count++},setUploadingFlag:function(){this.is_uploading=true},resetUploadingFlag:function(){this.is_uploading=false},prepareNextUploadTask:function(){var store=this.grid_panel.getStore();var record=null;store.each(function(r){if(!record&&r.get("state")==Ext.ux.UploadDialog.FileRecord.STATE_QUEUE){record=r}else{r.get("input_element").dom.disabled=true}});record.get("input_element").dom.disabled=false;record.set("state",Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING);record.set("note",this.i18n.note_processing);record.commit();this.fsa.postEvent("file-upload-start",record)},fireUploadStartEvent:function(){this.fireEvent("uploadstart",this)},removeFiles:function(file_records){var store=this.grid_panel.getStore();for(var i=0,len=file_records.length;i<len;i++){var r=file_records[i];r.get("input_element").remove();store.remove(r)}},fireFileRemoveEvent:function(file_records){for(var i=0,len=file_records.length;i<len;i++){this.fireEvent("fileremove",this,file_records[i].get("filename"))}},resetQueue:function(){var store=this.grid_panel.getStore();store.each(function(r){r.get("input_element").remove()});store.removeAll()},fireResetQueueEvent:function(){this.fireEvent("resetqueue",this)},uploadFile:function(record){Ext.Ajax.request({url:this.url,params:this.base_params||this.baseParams||this.params,method:"POST",form:this.form,isUpload:true,success:this.onAjaxSuccess,failure:this.onAjaxFailure,scope:this,record:record})},fireFileUploadStartEvent:function(record){this.fireEvent("fileuploadstart",this,record.get("filename"))},updateRecordState:function(data){if("success" in data.response&&data.response.success){data.record.set("state",Ext.ux.UploadDialog.FileRecord.STATE_FINISHED);data.record.set("note",data.response.message||data.response.error||this.i18n.note_upload_success)}else{data.record.set("state",Ext.ux.UploadDialog.FileRecord.STATE_FAILED);data.record.set("note",data.response.message||data.response.error||this.i18n.note_upload_error)}data.record.commit()},fireUploadSuccessEvent:function(data){this.fireEvent("uploadsuccess",this,data.record.get("filename"),data.response)},fireUploadErrorEvent:function(data){this.fireEvent("uploaderror",this,data.record.get("filename"),data.response)},fireUploadFailedEvent:function(data){this.fireEvent("uploadfailed",this,data.record.get("filename"))},fireUploadCompleteEvent:function(){this.fireEvent("uploadcomplete",this)},findUploadFrame:function(){this.upload_frame=Ext.getBody().child("iframe.x-hidden:last")},resetUploadFrame:function(){this.upload_frame=null},removeUploadFrame:function(){if(this.upload_frame){this.upload_frame.removeAllListeners();this.upload_frame.dom.src="about:blank";this.upload_frame.remove()}this.upload_frame=null},abortUpload:function(){this.removeUploadFrame();var store=this.grid_panel.getStore();var record=null;store.each(function(r){if(r.get("state")==Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING){record=r;return false}});record.set("state",Ext.ux.UploadDialog.FileRecord.STATE_FAILED);record.set("note",this.i18n.note_aborted);record.commit()},fireUploadStopEvent:function(){this.fireEvent("uploadstop",this)},repostHide:function(){this.fsa.postEvent("hide")},flushEventQueue:function(){this.fsa.flushEventQueue()},onWindowRender:function(){this.fsa.postEvent("window-render")},onWindowBeforeHide:function(){return this.isUploading()?this.getAllowCloseOnUpload():true},onWindowHide:function(){this.fsa.postEvent("hide")},onWindowDestroy:function(){this.fsa.postEvent("destroy")},onGridRender:function(){this.fsa.postEvent("grid-render")},onGridSelectionChange:function(){this.fsa.postEvent("grid-selection-change")},onAddButtonFileSelected:function(btn){this.fsa.postEvent("file-selected",btn)},onUploadButtonClick:function(){if(this.is_uploading){this.fsa.postEvent("stop-upload")}else{this.fsa.postEvent("start-upload")}},onRemoveButtonClick:function(){var selections=this.grid_panel.getSelectionModel().getSelections();this.fsa.postEvent("remove-files",selections)},onResetButtonClick:function(){this.fsa.postEvent("reset-queue")},onCloseButtonClick:function(){this[this.closeAction].call(this);if(this.Make_Reload==true){document.location.reload()}},onAjaxSuccess:function(response,options){var json_response={success:false,error:this.i18n.note_upload_error};try{var rt=response.responseText;var filter=rt.match(/^<pre>((?:.|\n)*)<\/pre>$/i);if(filter){rt=filter[1]}json_response=Ext.util.JSON.decode(rt)}catch(e){}var data={record:options.record,response:json_response};if("success" in json_response&&json_response.success){this.fsa.postEvent("file-upload-success",data)}else{this.fsa.postEvent("file-upload-error",data)}},onAjaxFailure:function(response,options){var data={record:options.record,response:{success:false,error:this.i18n.note_upload_failed}};this.fsa.postEvent("file-upload-failed",data)},startUpload:function(){this.fsa.postEvent("start-upload")},stopUpload:function(){this.fsa.postEvent("stop-upload")},getUrl:function(){return this.url},setUrl:function(url){this.url=url},getBaseParams:function(){return this.base_params},setBaseParams:function(params){this.base_params=params},getUploadAutostart:function(){return this.upload_autostart},setUploadAutostart:function(value){this.upload_autostart=value},getMakeReload:function(){return this.Make_Reload},setMakeReload:function(value){this.Make_Reload=value},getAllowCloseOnUpload:function(){return this.allow_close_on_upload},setAllowCloseOnUpload:function(value){this.allow_close_on_upload},getResetOnHide:function(){return this.reset_on_hide},setResetOnHide:function(value){this.reset_on_hide=value},getPermittedExtensions:function(){return this.permitted_extensions},setPermittedExtensions:function(value){this.permitted_extensions=value},isUploading:function(){return this.is_uploading},isNotEmptyQueue:function(){return this.grid_panel.getStore().getCount()>0},getQueuedCount:function(count_processing){var count=0;var store=this.grid_panel.getStore();store.each(function(r){if(r.get("state")==Ext.ux.UploadDialog.FileRecord.STATE_QUEUE){count++}if(count_processing&&r.get("state")==Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING){count++}});return count},hasUnuploadedFiles:function(){return this.getQueuedCount()>0}});var p=Ext.ux.UploadDialog.Dialog.prototype;p.i18n={title:_("upload_files"),state_col_title:_("upf_state"),state_col_width:70,filename_col_title:_("upf_filename"),filename_col_width:230,note_col_title:_("upf_note"),note_col_width:150,add_btn_text:_("upf_add"),add_btn_tip:_("upf_add_desc"),remove_btn_text:_("upf_remove"),remove_btn_tip:_("upf_remove_desc"),reset_btn_text:_("upf_reset"),reset_btn_tip:_("upf_reset_desc"),upload_btn_start_text:_("upf_upload"),upload_btn_start_tip:_("upf_upload_desc"),upload_btn_stop_text:_("upf_abort"),upload_btn_stop_tip:_("upf_abort_desc"),close_btn_text:_("upf_close"),close_btn_tip:_("upf_close_desc"),progress_waiting_text:_("upf_progress_wait"),progress_uploading_text:_("upf_uploading_desc"),error_msgbox_title:_("upf_error"),permitted_extensions_join_str:",",err_file_type_not_permitted:_("upf_err_filetype"),note_queued_to_upload:_("upf_queued"),note_processing:_("upf_uploading"),note_upload_failed:_("upf_err_failed"),note_upload_success:_("upf_success"),note_upload_error:_("upf_upload_err"),note_aborted:_("upf_aborted")};