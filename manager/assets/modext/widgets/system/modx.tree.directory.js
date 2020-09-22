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
    config.id = config.id || Ext.id();
    Ext.applyIf(config,{
        rootVisible: true
        ,rootName: 'Filesystem'
        ,rootId: '/'
        ,title: _('files')
        ,ddAppendOnly: true
        ,ddGroup: 'modx-treedrop-sources-dd'
        ,url: MODx.config.connector_url
        ,hideSourceCombo: false
        ,baseParams: {
            hideFiles: config.hideFiles || false
            ,hideTooltips: config.hideTooltips || false
            ,wctx: MODx.ctx || 'web'
            ,currentAction: MODx.request.a || 0
            ,currentFile: MODx.request.file || ''
            ,source: config.source || 0
        }
        ,action: 'browser/directory/getList'
        ,primaryKey: 'dir'
        ,useDefaultToolbar: true
        ,autoExpandRoot: false
        ,tbar: [{
            cls: 'x-btn-icon icon-folder'
            ,tooltip: {text: _('file_folder_create')}
            ,handler: this.createDirectory
            ,scope: this
            ,hidden: MODx.perm.directory_create ? false : true
        },{
            cls: 'x-btn-icon icon-page_white'
            ,tooltip: {text: _('file_create')}
            ,handler: this.createFile
            ,scope: this
            ,hidden: MODx.perm.file_create ? false : true
        },{
            cls: 'x-btn-icon icon-file_upload'
            ,tooltip: {text: _('upload_files')}
            ,handler: this.uploadFiles
            ,scope: this
            ,hidden: MODx.perm.file_upload ? false : true
        },'->',{
            cls: 'x-btn-icon icon-file_manager'
            ,tooltip: {text: _('modx_browser')}
            ,handler: this.loadFileManager
            ,scope: this
            ,hidden: MODx.perm.file_manager && !MODx.browserOpen ? false : true
        }]
        ,tbarCfg: {
            id: config.id+'-tbar'
        }
    });
    MODx.tree.Directory.superclass.constructor.call(this,config);
    this.addEvents({
        'beforeUpload': true
        ,'afterUpload': true
        ,'afterQuickCreate': true
        ,'afterRename': true
        ,'afterRemove': true
        ,'fileBrowserSelect': true
        ,'changeSource': true
        ,'afterSort': true
    });
    this.on('click',function(n,e) {
        n.select();
        this.cm.activeNode = n;
    },this);
    this.on('render',function() {
        var el = Ext.get(this.config.id);
        el.createChild({tag: 'div', id: this.config.id+'_tb'});
        el.createChild({tag: 'div', id: this.config.id+'_filter'});
        this.addSourceToolbar();

//        this.getRootNode().pseudoroot = true
//        console.log(this.getRootNode())

    },this);
    //this.addSourceToolbar();
    this.on('show',function() {
        if (!this.config.hideSourceCombo) {
            try { this.sourceCombo.show(); } catch (e) {}
        }
    },this);
    this._init();
    this.on('afterrender', this.showRefresh, this);
    this.on('afterSort',this._handleAfterDrop,this);
};
Ext.extend(MODx.tree.Directory,MODx.tree.Tree,{

    windows: {}

    /**
     * Build the contextual menu for the root node
     *
     * @param {Ext.data.Node} node
     *
     * @returns {Array}
     */
    ,getRootMenu: function(node) {
        var menu = [];
        if (MODx.perm.directory_create) {
            menu.push({
                text: _('file_folder_create')
                ,handler: this.createDirectory
                ,scope: this
            });
        }

        if (MODx.perm.file_create) {
            menu.push({
                text: _('file_create')
                ,handler: this.createFile
                ,scope: this
            });
        }

        if (MODx.perm.file_upload) {
            menu.push({
                text: _('upload_files')
                ,handler: this.uploadFiles
                ,scope: this
            });
        }

        if (node.ownerTree.el.hasClass('pupdate')) {
            // User is allowed to edit media sources
            menu.push([
                '-'
                ,{
                    text: _('update')
                    ,handler: function() {
                        MODx.loadPage('source/update', 'id=' + node.ownerTree.source);
                    }
                }
            ])
        }

//        if (MODx.perm.file_manager) {
//            menu.push({
//                text: _('modx_browser')
//                ,handler: this.loadFileManager
//                ,scope: this
//            });
//        }

        return menu;
    }

    /**
     * Override to handle root nodes contextual menus
     *
     * @param node
     * @param e
     */
    ,_showContextMenu: function(node,e) {
        this.cm.activeNode = node;
        this.cm.removeAll();
        var m;

        if (node.isRoot) {
            m = this.getRootMenu(node);
        } else if (node.attributes.menu && node.attributes.menu.items) {
            m = node.attributes.menu.items;
        }

        if (m && m.length > 0) {
            this.addContextMenuItem(m);
            this.cm.showAt(e.xy);
        }
        e.preventDefault();
        e.stopEvent();
    }

    /**
     * Create a refresh button on the root node
     *
     * @see MODx.Tree.Tree#_onAppend
     */
    ,showRefresh: function() {
        var node = this.getRootNode()
            ,inlineButtonsLang = this.getInlineButtonsLang(node)
            ,elId = node.ui.elNode.id+ '_tools'
            ,el = document.createElement('div');

        el.id = elId;
        el.className = 'modx-tree-node-tool-ct';
        node.ui.elNode.appendChild(el);

        MODx.load({
            xtype: 'modx-button'
            ,text: ''
            ,scope: this
            ,tooltip: new Ext.ToolTip({
                title: inlineButtonsLang.refresh
                ,target: this
            })
            ,node: node
            ,handler: function(btn,evt){
                evt.stopPropagation(evt);
                node.reload();
            }
            ,iconCls: 'icon-refresh'
            ,renderTo: elId
            ,listeners: {
                mouseover: function(button, e){
                    button.tooltip.onTargetOver(e);
                }
                ,mouseout: function(button, e){
                    button.tooltip.onTargetOut(e);
                }
            }
        });
    }

    ,addSourceToolbar: function() {
        this.sourceCombo = new MODx.combo.MediaSource({
            value: this.config.source || MODx.config.default_media_source
            ,listWidth: 236
            ,listeners: {
                select:{
                    fn: this.changeSource
                    ,scope: this
                }
                ,loaded: {
                    fn: function(combo) {
                        var rec = combo.store.getById(this.config.source);
                        var rn = this.getRootNode();
                        if (rn && rec) { rn.setText(rec.data.name); }
                    }
                    ,scope: this
                }
            }
        });
        this.searchBar = new Ext.Toolbar({
            renderTo: this.tbar
            ,id: this.config.id+'-sourcebar'
            ,items: [this.sourceCombo]
        });
        this.on('resize', function(){
            this.sourceCombo.setWidth(this.getWidth() - 12);
        }, this);
        if (this.config.hideSourceCombo) {
            try { this.sourceCombo.hide(); } catch (e) {}
        }
    }

    ,changeSource: function(sel) {
        this.cm.activeNode = '';
        var s = sel.getValue();
        var rn = this.getRootNode();
        if (rn) { rn.setText(sel.getRawValue()); }
        this.config.baseParams.source = s;
        this.fireEvent('changeSource',s);
        this.refresh();
    }

    /**
     * Expand the root node if appropriate
     */
    ,_init: function() {
        var treeState = Ext.state.Manager.get(this.treestate_id)
            ,rootPath = this.root.getPath('text');

        if (rootPath === treeState) {
            // Nothing to do
            return;
        }

        this.root.expand();
    }

    ,_initExpand: function() {
        var treeState = Ext.state.Manager.get(this.treestate_id);
        if (!Ext.isEmpty(this.config.openTo)) {
            this.selectPath('/'+_('files')+'/'+this.config.openTo,'text');
        } else {
            this.expandPath(treeState, 'text');
        }
    }

    ,_saveState: function(n) {
        if (!n.expanded && !n.isRoot) {
            // Node has been collapsed, grab its parent
            n = n.parentNode;
        }
        if (n.id == this.config.openTo) {
            n.select();
        }
        var p = n.getPath('text');
        Ext.state.Manager.set(this.treestate_id, p);
    }


    ,_handleAfterDrop: function(o,r) {
        var targetNode = o.event.target;
        var dropNode = o.event.dropNode;
        if (o.event.point == 'append' && targetNode) {
            var ui = targetNode.getUI();
            ui.addClass('haschildren');
            ui.removeClass('icon-resource');
        }
        if((MODx.request.a == MODx.action['resource/update']) && dropNode.attributes.pk == MODx.request.id){
            var parentFieldCmb = Ext.getCmp('modx-resource-parent');
            var parentFieldHidden = Ext.getCmp('modx-resource-parent-hidden');
            if(parentFieldCmb && parentFieldHidden){
                parentFieldHidden.setValue(dropNode.parentNode.attributes.pk);
                parentFieldCmb.setValue(dropNode.parentNode.attributes.text.replace(/(<([^>]+)>)/ig,""));
            }
        }
        targetNode.reload(true);
    }

    ,_handleDrag: function(dropEvent) {
        var from = dropEvent.dropNode.attributes.id;
        var to = dropEvent.target.attributes.id;
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                source: this.config.baseParams.source
                ,from: from
                ,to: to
                ,action: this.config.sortAction || 'browser/directory/sort'
                ,point: dropEvent.point
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var el = dropEvent.dropNode.getUI().getTextEl();
                    if (el) {Ext.get(el).frame();}
                    this.fireEvent('afterSort',{event:dropEvent,result:r});
                },scope:this}
                ,'failure': {fn:function(r) {
                    MODx.form.Handler.errorJSON(r);
                    this.refresh();
                    return false;
                },scope:this}
            }
        });
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

    ,editFile: function(itm,e) {
        MODx.loadPage('system/file/edit', 'file='+this.cm.activeNode.attributes.id+'&source='+this.config.source);
    }

    ,quickUpdateFile: function(itm,e) {
        var node = this.cm.activeNode;
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'browser/file/get'
                ,file:  node.attributes.id
                ,wctx: MODx.ctx || ''
                ,source: this.getSource()
            }
            ,listeners: {
                'success': {fn:function(response) {
                    var r = {
                        file: node.attributes.id
                        ,name: node.text
                        ,path: node.attributes.pathRelative
                        ,source: this.getSource()
                        ,content: response.object.content
                    };
                    var w = MODx.load({
                        xtype: 'modx-window-file-quick-update'
                        ,record: r
                        ,listeners: {
                            'hide':{fn:function() {this.destroy();}}
                        }
                    });
                    w.show(e.target);
                },scope:this}
            }
        });
    }

    ,createFile: function(itm,e) {
        var active = this.cm.activeNode
            ,dir = active && active.attributes && (active.isRoot || active.attributes.type == 'dir')
                ? active.attributes.id
                : '';

        MODx.loadPage('system/file/create', 'directory='+dir+'&source='+this.getSource());
    }

    ,quickCreateFile: function(itm,e) {
        var node = this.cm.activeNode;
        var r = {
            directory: node.attributes.id
            ,source: this.getSource()
        };
        var w = MODx.load({
            xtype: 'modx-window-file-quick-create'
            ,record: r
            ,listeners: {
                'success':{fn:function(r) {
                    this.fireEvent('afterQuickCreate');
                    this.refreshActiveNode();
                }, scope: this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }

    ,browser: null

    ,loadFileManager: function(btn,e) {
        var refresh = false;
        if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'modx-browser'
                ,hideFiles: MODx.config.modx_browser_tree_hide_files
                ,rootId: '/' // prevent JS error because ui.node.elNode is undefined when this is
                // ,rootVisible: false
                ,wctx: MODx.ctx
                ,source: this.config.baseParams.source
                ,listeners: {
                    'select': {fn: function(data) {
                        this.fireEvent('fileBrowserSelect',data);
                    },scope:this}
                }
            });
        } else {
            refresh = true;
        }
        if (this.browser) {
            this.browser.setSource(this.config.baseParams.source);
            if (refresh) {
                this.browser.win.tree.refresh();
            }
            this.browser.show();
        }
    }

    /* exside: what is this? cannot find it used anywhere and basically does what renameFile() does, no? */
    /* candidate for removal or depreciation */
    ,renameNode: function(field,nv,ov) {
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'browser/file/rename'
                ,new_name: nv
                ,old_name: ov
                ,file: this.treeEditor.editNode.id
                ,wctx: MODx.ctx || ''
                ,source: this.getSource()
            }
            ,listeners: {
               'success': {fn:function(r) {
                    this.fireEvent('afterRename');
                    this.refreshActiveNode();
                }, scope: this}
            }
        });
    }

    ,renameDirectory: function(item,e) {
        var node = this.cm.activeNode;
        var r = {
            old_name: node.text
            ,name: node.text
            ,path: node.attributes.pathRelative
            ,source: this.getSource()
        };
        var w = MODx.load({
            xtype: 'modx-window-directory-rename'
            ,record: r
            ,listeners: {
                'success':{fn:this.refreshParentNode,scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }

    ,renameFile: function(item,e) {
        var node = this.cm.activeNode;
        var r = {
            old_name: node.text
            ,name: node.text
            ,path: node.attributes.pathRelative
            ,source: this.getSource()
        };
        var w = MODx.load({
            xtype: 'modx-window-file-rename'
            ,record: r
            ,listeners: {
                // 'success':{fn:this.refreshParentNode,scope:this}
                'success': {fn:function(r) {
                    this.fireEvent('afterRename');
                    this.refreshParentNode();
                }, scope: this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }

    ,createDirectory: function(item,e) {
        var node = this.cm && this.cm.activeNode ? this.cm.activeNode : false;
        var r = {
            parent: node && node.attributes.type == 'dir' ? node.attributes.pathRelative : '/'
            ,source: this.getSource()
        };

        var w = MODx.load({
            xtype: 'modx-window-directory-create'
            ,record: r
            ,listeners: {
                'success': {
                    fn:function() {
                        var parent = Ext.getCmp('folder-parent').getValue();

                        if ((this.cm.activeNode && this.cm.activeNode.constructor.name === 'constructor') || parent === '' || parent === '/') {
                            this.refresh();
                        } else {
                            this.refreshActiveNode();
                        }
                    },scope:this
                }
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e ? e.target : Ext.getBody());
    }

    ,chmodDirectory: function(item,e) {
        var node = this.cm.activeNode;
        var r = {
            dir: node.attributes.path
            ,mode: node.attributes.perms
            ,source: this.getSource()
        };
        var w = MODx.load({
            xtype: 'modx-window-directory-chmod'
            ,record: r
            ,listeners: {
                'success':{fn:this.refreshActiveNode,scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }

    ,removeDirectory: function(item,e) {
        var node = this.cm.activeNode;
        MODx.msg.confirm({
            text: _('file_folder_remove_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'browser/directory/remove'
                ,dir: node.attributes.path
                ,wctx: MODx.ctx || ''
                ,source: this.getSource()
            }
            ,listeners: {
                success: {
                    fn: this._afterRemove
                    ,scope: this
                }
            }
        });
    }

    ,removeFile: function(item,e) {
        var node = this.cm.activeNode;
        MODx.msg.confirm({
            text: _('file_confirm_remove')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'browser/file/remove'
                ,file: node.attributes.pathRelative
                ,wctx: MODx.ctx || ''
                ,source: this.getSource()
            }
            ,listeners: {
                success: {
                    fn: this._afterRemove
                    ,scope: this
                }
            }
        });
    }

    /**
     * Operation executed after a node has been removed
     */
    ,_afterRemove: function() {
        this.fireEvent('afterRemove');
        this.refreshParentNode();
        this.cm.activeNode = null;
    }

    ,unpackFile: function(item,e) {
        var node = this.cm.activeNode;
        MODx.msg.confirm({
            text: _('file_download_unzip') + ' ' + node.attributes.id
            ,url: MODx.config.connectors_url
            ,params: {
                action: 'browser/file/unpack'
                ,file: node.attributes.id
                ,wctx: MODx.ctx || ''
                ,source: this.getSource()
                ,path: node.attributes.directory
            }
            ,listeners: {
                'success':{fn:this.refreshParentNode,scope:this}
            }
        });
    }

    ,downloadFile: function(item,e) {
        var node = this.cm.activeNode;
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'browser/file/download'
                ,file: node.attributes.pathRelative
                ,wctx: MODx.ctx || ''
                ,source: this.getSource()
            }
            ,listeners: {
                'success':{fn:function(r) {
                    if (!Ext.isEmpty(r.object.url)) {
                        location.href = MODx.config.connector_url+'?action=browser/file/download&download=1&file='+node.attributes.id+'&HTTP_MODAUTH='+MODx.siteId+'&source='+this.getSource()+'&wctx='+MODx.ctx;
                    }
                },scope:this}
            }
        });
    }

    ,copyRelativePath: function(item,e) {
        var node = this.cm.activeNode;

        var dummyRelativePathInput = document.createElement("input");
        document.body.appendChild(dummyRelativePathInput);
        dummyRelativePathInput.setAttribute('value', node.attributes.pathRelative);

        dummyRelativePathInput.select();
        document.execCommand("copy");

        document.body.removeChild(dummyRelativePathInput);
    }

    ,getSource: function() {
        return this.config.baseParams.source;
    }

    ,uploadFiles: function(btn,e) {
        if (!this.uploader) {
            this.uploader = new MODx.util.MultiUploadDialog.Dialog({
                url: MODx.config.connector_url
                ,base_params: {
                    action: 'browser/file/upload'
                    ,wctx: MODx.ctx || ''
                    ,source: this.getSource()
                }
                ,cls: 'ext-ux-uploaddialog-dialog modx-upload-window'
            });
            this.uploader.on('show',this.beforeUpload,this);
            this.uploader.on('uploadsuccess',this.uploadSuccess,this);
            this.uploader.on('uploaderror',this.uploadError,this);
            this.uploader.on('uploadfailed',this.uploadFailed,this);
        }
        this.uploader.base_params.source = this.getSource();
        this.uploader.show(btn);
    }

    ,uploadError: function(dlg,file,data,rec) {}

    ,uploadFailed: function(dlg,file,rec) {}

    ,uploadSuccess:function() {
        if (this.cm.activeNode) {
            var node = this.cm.activeNode;
            if (node.isLeaf) {
                var pn = (node.isLeaf() ? node.parentNode : node);
                if (pn) {
                    pn.reload();
                } else {
                    this.refreshActiveNode();
                }
                this.fireEvent('afterUpload',node);
            } else {
                this.refreshActiveNode();
            }
        } else {
            this.refresh();
            this.fireEvent('afterUpload');
        }
    }

    ,beforeUpload: function() {
        var path = this.config.openTo || this.config.rootId || '/';
        if (this.cm.activeNode) {
            path = this.getPath(this.cm.activeNode);
            if(this.cm.activeNode.isLeaf()) {
                path = this.getPath(this.cm.activeNode.parentNode);
            }
        }

        this.uploader.setBaseParams({
            action: 'browser/file/upload'
            ,path: path
            ,wctx: MODx.ctx || ''
            ,source: this.getSource()
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
        title: _('file_folder_create')
        // width: 430
        // ,height: 200
        ,url: MODx.config.connector_url
        ,action: 'browser/directory/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'source'
        },{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            fieldLabel: _('file_folder_parent')
            ,id: 'folder-parent'
            ,name: 'parent'
            ,xtype: 'textfield'
            ,anchor: '100%'
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
        // ,width: 430
        // ,height: 200
        ,url: MODx.config.connector_url
        ,action: 'browser/directory/chmod'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'source'
        },{
            name: 'dir'
            ,fieldLabel: _('name')
            ,xtype: 'statictextfield'
            ,anchor: '100%'
            ,submitValue: true
        },{
            fieldLabel: _('mode')
            ,name: 'mode'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        }]
    });
    MODx.window.ChmodDirectory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.ChmodDirectory,MODx.Window);
Ext.reg('modx-window-directory-chmod',MODx.window.ChmodDirectory);

/**
 * Generates the Rename Directory window
 *
 * @class MODx.window.RenameDirectory
 * @extends MODx.Window
 * @param {Object} config An object of configuration options.
 * @xtype modx-window-directory-rename
 */
MODx.window.RenameDirectory = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('rename')
        // ,width: 430
        // ,height: 200
        ,url: MODx.config.connector_url
        ,action: 'browser/directory/rename'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'source'
        },{
            fieldLabel: _('path')
            ,name: 'path'
            ,xtype: 'statictextfield'
            ,submitValue: true
            ,anchor: '100%'
        },{
            fieldLabel: _('old_name')
            ,name: 'old_name'
            ,xtype: 'statictextfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('new_name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        }]
    });
    MODx.window.RenameDirectory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RenameDirectory,MODx.Window);
Ext.reg('modx-window-directory-rename',MODx.window.RenameDirectory);

/**
 * Generates the Rename File window
 *
 * @class MODx.window.RenameFile
 * @extends MODx.Window
 * @param {Object} config An object of configuration options.
 * @xtype modx-window-file-rename
 */
MODx.window.RenameFile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('rename')
        // ,width: 430
        // ,height: 200
        ,url: MODx.config.connector_url
        ,action: 'browser/file/rename'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'source'
        },{
            fieldLabel: _('path')
            ,name: 'path'
            ,xtype: 'statictextfield'
            ,submitValue: true
            ,anchor: '100%'
        },{
            fieldLabel: _('old_name')
            ,name: 'old_name'
            ,xtype: 'statictextfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('new_name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            name: 'dir'
            ,xtype: 'hidden'
        }]
    });
    MODx.window.RenameFile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RenameFile,MODx.Window);
Ext.reg('modx-window-file-rename',MODx.window.RenameFile);

/**
 * Generates the Quick Update File window
 *
 * @class MODx.window.QuickUpdateFile
 * @extends MODx.Window
 * @param {Object} config An object of configuration options.
 * @xtype modx-window-file-quick-update
 */
MODx.window.QuickUpdateFile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('file_quick_update')
        ,width: 600
        // ,height: 640
        // ,autoHeight: false
        ,layout: 'anchor'
        ,url: MODx.config.connector_url
        ,action: 'browser/file/update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'source'
        },{
            xtype: 'hidden'
            ,name: 'file'
        },{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'statictextfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('path')
            ,name: 'path'
            ,xtype: 'statictextfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('content')
            ,xtype: 'textarea'
            ,name: 'content'
            ,anchor: '100%'
            ,height: 200
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateFile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateFile,MODx.Window);
Ext.reg('modx-window-file-quick-update',MODx.window.QuickUpdateFile);

/**
 * Generates the Quick Create File window
 *
 * @class MODx.window.QuickCreateFile
 * @extends MODx.Window
 * @param {Object} config An object of configuration options.
 * @xtype modx-window-file-quick-create
 */
MODx.window.QuickCreateFile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('file_quick_create')
        ,width: 600
        // ,height: 640
        // ,autoHeight: false
        ,layout: 'anchor'
        ,url: MODx.config.connector_url
        ,action: 'browser/file/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'source'
        },{
            fieldLabel: _('directory')
            ,name: 'directory'
            ,submitValue: true
            ,xtype: 'statictextfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            fieldLabel: _('content')
            ,xtype: 'textarea'
            ,name: 'content'
            ,anchor: '100%'
            ,height: 200
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
        /* this is the default config found also in widgets/core/modx.window.js, no need to redeclare here */
        /*,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: this.submit
        }]*/
    });
    MODx.window.QuickCreateFile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateFile,MODx.Window);
Ext.reg('modx-window-file-quick-create',MODx.window.QuickCreateFile);


