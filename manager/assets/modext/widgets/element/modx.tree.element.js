/**
 * Generates the Element Tree
 * 
 * @class MODx.tree.Element
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-element
 */
MODx.tree.Element = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		rootVisible: false
		,enableDrag: true
		,enableDrop: true
		,title: ''
		,url: MODx.config.connectors_url+'layout/tree/element.php'
	});
	MODx.tree.Element.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.Element,MODx.tree.Tree,{
	forms: {}
	,windows: {}
	,stores: {}
		
	,createCategory: function(node,e) {
		var r = {
			'parent': this.cm.activeNode.attributes.category
		};
		
		if (!this.windows.createCategory) {
			this.windows.createCategory = MODx.load({
				xtype: 'modx-window-category-create'
				,record: r
				,listeners: {
					'success': {fn:function() { this.refreshNode(this.cm.activeNode.id); },scope:this}
				}
			});
		}
		this.windows.createCategory.setValues(r);
		this.windows.createCategory.show(e.target);
	}

	,renameCategory: function(node,e) {
        var id = this.cm.activeNode.id.substr(2).split('_'); id = id[1];
        
        if (!this.windows.renameCategory) {
            this.windows.renameCategory = MODx.load({
                xtype: 'modx-window-category-rename'
                ,record: { 
                    id: id
                    ,name: this.cm.activeNode.text
                }
                ,listeners: {
                	'success':{fn:function() { this.refreshNode(this.cm.activeNode.id); },scope:this}
                }
            });
        }
        this.windows.renameCategory.show(e.target);
    }
		
	,removeCategory: function(item,e) {
		var id = this.cm.activeNode.id.substr(2).split('_');
        id = id.length > 2 ? id[2] : id[1];
		MODx.msg.confirm({
			title: _('warning')
			,text: _('category_confirm_delete')
			,url: MODx.config.connectors_url+'element/category.php'
			,params: {
				action: 'remove'
				,id: id
			}
			,listeners: {
				'success': {fn:function() { this.refreshNode(this.cm.activeNode.id); },scope:this}
			}
		});
	}
	    
    ,duplicateElement: function(itm,e,id,type) {
        var r = {
            id: id
            ,type: type
            ,name: _('duplicate_of',{name: this.cm.activeNode.text})
        };
        
        if (!this.windows.duplicateElement) {
            this.windows.duplicateElement = MODx.load({
                xtype: 'modx-window-element-duplicate'
                ,record: r
                ,listeners: {
                	'success': {fn:function() { this.refreshNode(this.cm.activeNode.id); },scope:this}
                }
            });
        } else {
            var u = MODx.config.connectors_url+'element/'+type+'.php';
            this.windows.duplicateElement.fp.getForm().url = u;
        }
        this.windows.duplicateElement.setValues(r);
        this.windows.duplicateElement.show(e.target);
    }
	
	,removeElement: function(itm,e) {
		var id = this.cm.activeNode.id.substr(2);
		var oar = id.split('_');
		MODx.msg.confirm({
			title: _('warning')
			,text: _('remove_this_confirm')+' '+oar[0]+'?'
			,url: MODx.config.connectors_url+'element/'+oar[0]+'.php'
			,params: {
				action: 'delete'
				,id: oar[2]
			}
			,listeners: {
				'success': {fn:function() { this.refreshNode(this.cm.activeNode.id); },scope:this}
			}
		});
	}
    
    ,quickCreateChunk: function(itm,e) {
        var r = {
            category: this.cm.activeNode.attributes.pk || ''
        };
        
        if (!this.windows.quickCreateChunk) {
            this.windows.quickCreateChunk = MODx.load({
                xtype: 'modx-window-quick-create-chunk'
                ,record: r
                ,listeners: {
                    'success':{fn:function() { this.refreshNode(this.cm.activeNode.id); },scope:this}
                }
            });
        }
        this.windows.quickCreateChunk.setValues(r);
        this.windows.quickCreateChunk.show(e.target);
    }
    
    ,quickUpdateChunk: function(itm,e) {
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'element/chunk.php'
            ,params: {
                action: 'get'
                ,id: this.cm.activeNode.attributes.pk
            }
            ,listeners: {
                'success': {fn:function(r) {                           
                    if (!this.windows.quickUpdateChunk) {
                        this.windows.quickUpdateChunk = MODx.load({
                            xtype: 'modx-window-quick-update-chunk'
                            ,record: r.object
                            ,listeners: {
                                'success':{fn:function() { this.refreshNode(this.cm.activeNode.id); },scope:this}
                            }
                        });
                    }
                    this.windows.quickUpdateChunk.setValues(r.object);
                    this.windows.quickUpdateChunk.show(e.target);
                },scope:this}
            }
        });
    }
	
	,_createElement: function(item,e) {
		var id = this.cm.activeNode.id.substr(2);
		var oar = id.split('_');
		var type = oar[0] == 'type' ? oar[1] : oar[0];
		var cat_id = oar[0] == 'type' ? 0 : (oar[1] == 'category' ? oar[2] : oar[3]);
        var a = MODx.action['element/'+type+'/create'];
		this.redirect('index.php?a='+a+'&category='+cat_id);
		this.cm.hide();
		return false;
	}
		
	,_handleDrop: function(e) {
		var target = e.target;
		if(e.point == 'above' || e.point == 'below') {
			target = e.target.parentNode;
		}
        if (!this.isCorrectType(e.dropNode,target)) { return false; }
		
		return e.target.getDepth() > 0;
	}
    
    ,isCorrectType: function(dropNode,targetNode) {
        var r = false;
        /* types must be the same */
        if(targetNode.attributes.type == dropNode.attributes.type) {
            /* do not allow nesting of categories or anything to be dropped on an element */
            if(!(targetNode.parentNode && 
        		((dropNode.attributes.cls == 'folder' 
    				&& targetNode.attributes.cls == 'folder'
    				&& dropNode.parentNode.id == targetNode.parentNode.id
    			) || targetNode.attributes.cls == 'file'))) {
                r = true;
            }
        }
        return r;
    }
});
Ext.reg('modx-tree-element',MODx.tree.Element);


/** 
 * Generates the Duplicate Element window
 * 
 * @class MODx.window.DuplicateElement
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-element-duplicate
 */
MODx.window.DuplicateElement = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('element_duplicate')
        ,url: MODx.config.connectors_url+'element/'+config.record.type+'.php'
        ,action: 'duplicate'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-dupel-id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('element_name_new')
            ,name: 'name'
            ,id: 'modx-dupel-name'
            ,width: 250
        }]
    });
    MODx.window.DuplicateElement.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateElement,MODx.Window);
Ext.reg('modx-window-element-duplicate',MODx.window.DuplicateElement);



/** 
 * Generates the Rename Category window.
 *  
 * @class MODx.window.RenameCategory
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-category-rename
 */
MODx.window.RenameCategory = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('category_rename')
        ,height: 150
        ,width: 350
        ,url: MODx.config.connectors_url+'element/category.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-rencat-id'
            ,value: config.record.id
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'category'
            ,id: 'modx-rencat-category'
            ,width: 150
            ,value: config.record.name
        }]
    });
    MODx.window.RenameCategory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RenameCategory,MODx.Window);
Ext.reg('modx-window-category-rename',MODx.window.RenameCategory);