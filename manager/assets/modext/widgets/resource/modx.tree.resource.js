/**
 * Generates the Resource Tree in Ext
 * 
 * @class MODx.tree.Resource
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-resource
 */
MODx.tree.Resource = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		rootVisible: false
		,expandFirst: true
		,enableDrag: true
		,enableDrop: true
		,sortBy: 'menuindex'
		,title: ''
		,remoteToolbar: true
		,url: MODx.config.connectors_url+'resource/index.php'
	});
	MODx.tree.Resource.superclass.constructor.call(this,config);
    if (config.el) {
        var el = Ext.get(config.el);
        el.createChild({ tag: 'div', id: 'modx_resource_tree_tb' });
        el.createChild({ tag: 'div', id: 'modx_resource_tree_filter' });
    }
};
Ext.extend(MODx.tree.Resource,MODx.tree.Tree,{
	forms: {}
	,windows: {}
	,stores: {}
	
	,_initExpand: function() {
		var treeState = Ext.state.Manager.get(this.treestate_id);
		if (treeState === undefined) {
			if (this.root) { this.root.expand(); }
			var wn = this.getNodeById('web_0');
			if (wn && this.config.expandFirst) {
				wn.select();
				wn.expand();
			}
		} else {
            this.expandPath(treeState);
        }
	}
	
	,duplicateResource: function(item,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		
		var r = { 
		    resource: id
		    ,is_folder: node.getUI().hasClass('folder')
	    };
		if (this.windows.duplicate) {
           this.windows.duplicate.destroy(); 
        }
        this.windows.duplicate = MODx.load({
        	xtype: 'modx-window-resource-duplicate'
            ,resource: id
            ,is_folder: node.getUI().hasClass('folder')
            ,listeners: {
            	'success': {fn:function() { this.refreshNode(node.id); },scope:this}
            }
        });
		this.windows.duplicate.setValues(r);
		this.windows.duplicate.show(e.target);
	}
	
    ,preview: function(item,e) {
        window.open(this.cm.activeNode.attributes.preview_url);
    }
    
	,deleteDocument: function(item,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		MODx.msg.confirm({
			title: _('resource_delete')
			,text: _('resource_delete_confirm')
			,url: MODx.config.connectors_url+'resource/index.php'
			,params: {
				action: 'delete'
				,id: id
			}
			,listeners: {
				'success': {fn:function() { this.refreshNode(node.id); },scope:this}
			}
		});
	}
	
	,undeleteDocument: function(item,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		MODx.Ajax.request({
			url: MODx.config.connectors_url+'resource/index.php'
			,params: {
				action: 'undelete'
				,id: id
			}
			,listeners: {
				'success': {fn:function() { this.refreshNode(node.id); },scope:this}
			}
		});
	}
	
	,publishDocument: function(item,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		MODx.msg.confirm({
			title: _('resource_publish')
			,text: _('resource_publish_confirm')
			,url: MODx.config.connectors_url+'resource/index.php'
			,params: {
				action: 'publish'
				,id: id
			}
			,listeners: {
				'success': {fn:function() { this.refreshNode(node.id); },scope:this}
			}
		});
	}
	
	,unpublishDocument: function(item,e) {
		var node = this.cm.activeNode;
		var id = node.id.split('_'); id = id[1];
		MODx.msg.confirm({
			title: _('resource_unpublish')
			,text: _('resource_unpublish_confirm')
			,url: MODx.config.connectors_url+'resource/index.php'
			,params: {
				action: 'unpublish'
				,id: id
			}
			,listeners: {
				'success': {fn:function() { this.refreshNode(node.id); },scope:this}
			}
		});
	}
	
	,emptyRecycleBin: function(item,e) {
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
				action: 'emptyRecycleBin'
			}
			,listeners: {
				'success':{fn:this.refresh,scope:this}
			}
       	});
	}
	
	,showFilter: function(itm,e) {
		if (this._filterVisible) { return false; }
		
		var t = Ext.get('modx_resource_tree_filter');
		var fbd = t.createChild({tag: 'div'});
		var tb = new Ext.Toolbar(fbd);
		var cb = new Ext.form.ComboBox({
			store: new Ext.data.SimpleStore({
				fields: ['name','value']
				,data: [
					[_('menu_order'),'menuindex']
					,[_('page_title'),'pagetitle']
					,[_('publish_date'),'pub_date']
					,[_('createdon'),'createdon']
					,[_('editedon'),'editedon']
				]
			})
			,displayField: 'name'
			,valueField: 'value'
			,editable: false
			,mode: 'local'
			,triggerAction: 'all'
			,selectOnFocus: false
			,width: 100
			,value: this.config.sortBy
            ,listeners: {
                'select': {fn:this.filterSort,scope:this}
            }
		});
		tb.add(_('sort_by')+':');
		tb.addField(cb);
		tb.add('-',{
			scope: this
			,cls: 'x-btn-icon'
			,icon: MODx.config.template_url+'images/icons/close.gif'
			,handler: this.hideFilter
		});
		this.filterBar = tb;
		this._filterVisible = true;
	}
	
	,filterSort: function(cb,r,i) {
		this.config.sortBy = cb.getValue();
		this.getLoader().baseParams = {
			action: this.config.action
			,sortBy: this.config.sortBy
		};
		this.refresh();
	}
	
	,hideFilter: function(itm,e) {
		this.filterBar.destroy();
		this._filterVisible = false;
	}
	
	
    ,_handleDrop:  function(e){
        var dropNode = e.dropNode;
        var targetParent = e.target;

        if (targetParent.findChild('id',dropNode.attributes.id) !== null) { return false; }        
        var ap = true;
        if (targetParent.attributes.type == 'context' && e.point != 'append') {
        	ap = false;
        }
        
        return dropNode.attributes.text != 'root' && dropNode.attributes.text !== '' 
            && targetParent.attributes.text != 'root' && targetParent.attributes.text !== ''
            && ap;
    }
});
Ext.reg('modx-tree-resource',MODx.tree.Resource);