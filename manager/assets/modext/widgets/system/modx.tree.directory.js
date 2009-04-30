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
		,root_name: _('files')
		,title: _('files')
		,enableDrag: false
		,enableDrop: false
		,ddAppendOnly: true
		,url: MODx.config.connectors_url+'browser/directory.php'
		,baseParams: {
			prependPath: config.prependPath || null
			,hideFiles: config.hideFiles || false
		}
		,action: 'getList'
        ,primaryKey: 'dir'
	});
	MODx.tree.Directory.superclass.constructor.call(this,config);
    this.addEvents({
        'beforeUpload': true
        ,'afterUpload': true
    });
};
Ext.extend(MODx.tree.Directory,MODx.tree.Tree,{
	windows: {}
    ,_showContextMenu: function(node,e) {
        node.select();
        this.cm.activeNode = node;
        
        var m = this.cm;
        var nar = node.id.split('_');
        
        m.removeAll();
        if (node.attributes.menu) {
            this.addContextMenuItem(node.attributes.menu.items);
        }
        this.uploader = new Ext.ux.UploadPanel({
             contextmenu: this.cm
            ,buttonsAt: 'tbar'
            ,singleUpload: false
            ,enableProgress: true
            ,maxFileSize: 10485760
            ,baseParams: {
                action: 'upload'
                ,prependPath: this.config.prependPath || null
                ,prependUrl: this.config.prependUrl || null
            }
        });
        this.uploader.on({
            beforeupload:{scope:this, fn:this.onBeforeUpload}
            ,allfinished:{scope:this, fn:this.onAllFinished}
        });
        this.uploader.setUrl(MODx.config.connectors_url+'browser/file.php');
        
        m.add('-');
        m.add({
            text: 'Refresh Directory'
            ,scope: this
            ,handler: this.refreshActiveNode
        });
        m.add('-');
        m.add(new Ext.menu.Adapter(this.uploader,{
             hideOnClick:false
            ,cmd:'upload-panel'
        }));
        
        m.show(node.ui.getEl(),'t?');
        m.activeNode = node;
    }
    
    ,onAllFinished:function() {
        var node = this.cm.activeNode;
        (node.isLeaf() ? node.parentNode : node).reload();
        this.fireEvent('afterUpload',node);
    } // eo function onAllFinished
    
    ,onBeforeUpload:function(uploadPanel) {
        var node = this.cm.activeNode;
        var path = this.getPath(node);
        if(node.isLeaf()) {
            path = path.replace(/\/[^\/]+$/, '', path);
        }
        this.uploader.setPath(path);
        this.fireEvent('beforeUpload',node);
    } // eo function onBeforeUpload
    
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
    } // eo function getPath
    
    
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
	
	,createDirectory: function(item,e) {
		var node = this.cm.activeNode;
        var r = {parent: node.id};
        if (!this.windows.create) {
    		this.windows.create = MODx.load({
    			xtype: 'modx-window-directory-create'
    			,record: r
                ,prependPath: this.config.prependPath || null
                ,listeners: {
                    'success':{fn:this.refresh,scope:this}
                }
    		});
        } else {
            this.windows.create.setValues(r);
        }
		this.windows.create.show(e.target);
	}
	
	,chmodDirectory: function(item,e) {
		var node = this.cm.activeNode;
        var r = {dir: node.id};
        if (!this.windows.chmod) {
            this.windows.chmod = MODx.load({
    			xtype: 'modx-window-directory-chmod'
    			,record: r
                ,prependPath: this.config.prependPath || null
                ,listeners: {
                    'success':{fn:this.refresh,scope:this}
                }
    		});
        } else {
            this.windows.chmod.setValues(r);
        }
		this.windows.chmod.show(e.target);
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