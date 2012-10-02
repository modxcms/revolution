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
        ,enableDD: !Ext.isEmpty(MODx.config.enable_dragdrop) ? true : false
        ,ddGroup: 'modx-treedrop-dd'
        ,title: ''
        ,url: MODx.config.connectors_url+'element/index.php'
        ,useDefaultToolbar: true
        ,baseParams: {
            currentElement: MODx.request.id || 0
            ,currentAction: MODx.request.a || 0
        }
        ,tbar: [{
            icon: MODx.config.manager_url+'templates/default/images/restyle/icons/template.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('new')+' '+_('template')}
            ,handler: function() {
                this.redirect('index.php?a='+MODx.action['element/template/create']);
            }
            ,scope: this
            ,hidden: MODx.perm.new_template ? false : true
        },{
            icon: MODx.config.manager_url+'templates/default/images/restyle/icons/tv.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('new')+' '+_('tv')}
            ,handler: function() {
                this.redirect('index.php?a='+MODx.action['element/tv/create']);
            }
            ,scope: this
            ,hidden: MODx.perm.new_tv ? false : true
        },{
            icon: MODx.config.manager_url+'templates/default/images/restyle/icons/chunk.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('new')+' '+_('chunk')}
            ,handler: function() {
                this.redirect('index.php?a='+MODx.action['element/chunk/create']);
            }
            ,scope: this
            ,hidden: MODx.perm.new_chunk ? false : true
        },{
            icon: MODx.config.manager_url+'templates/default/images/restyle/icons/snippet.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('new')+' '+_('snippet')}
            ,handler: function() {
                this.redirect('index.php?a='+MODx.action['element/snippet/create']);
            }
            ,scope: this
            ,hidden: MODx.perm.new_snippet ? false : true
        },{
            icon: MODx.config.manager_url+'templates/default/images/restyle/icons/plugin.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('new')+' '+_('plugin')}
            ,handler: function() {
                this.redirect('index.php?a='+MODx.action['element/plugin/create']);
            }
            ,scope: this
            ,hidden: MODx.perm.new_plugin ? false : true
        },{
            icon: MODx.config.manager_url+'templates/default/images/restyle/icons/folder.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('new_category')}
            ,handler: function() {
                this.createCategory(null,{target: this.getEl()});
            }
            ,scope: this
            ,hidden: MODx.perm.new_category ? false : true
        }]
    });
    MODx.tree.Element.superclass.constructor.call(this,config);
    this.on('afterSort',this.afterSort);
};
Ext.extend(MODx.tree.Element,MODx.tree.Tree,{
    forms: {}
    ,windows: {}
    ,stores: {}

    ,createCategory: function(n,e) {
        var r = {};
        if (this.cm.activeNode && this.cm.activeNode.attributes.data) {
            r['parent'] = this.cm.activeNode.attributes.data.id;
        }

        var w = MODx.load({
            xtype: 'modx-window-category-create'
            ,record: r
            ,listeners: {
                'success': {fn:function() {
                    var node = (this.cm.activeNode) ? this.cm.activeNode.id : 'n_category';
                    this.refreshNode(node,true);
                },scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }

    ,renameCategory: function(itm,e) {
        var r = this.cm.activeNode.attributes.data;
        var w = MODx.load({
            xtype: 'modx-window-category-rename'
            ,record: r
            ,listeners: {
                'success':{fn:function(r) {
                    var c = r.a.result.object;
                    var n = this.cm.activeNode;
                    n.setText(c.category+' ('+c.id+')');
                    Ext.get(n.getUI().getEl()).frame();
                    n.attributes.data.id = c.id;
                    n.attributes.data.category = c.category;
                },scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }
		
    ,removeCategory: function(itm,e) {
        var id = this.cm.activeNode.attributes.data.id;
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('category_confirm_delete')
            ,url: MODx.config.connectors_url+'element/category.php'
            ,params: {
                action: 'remove'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function() {
                    this.cm.activeNode.remove();
                },scope:this}
            }
        });
    }
	    
    ,duplicateElement: function(itm,e,id,type) {
        var r = {
            id: id
            ,type: type
            ,name: _('duplicate_of',{name: this.cm.activeNode.attributes.name})
        };
        var w = MODx.load({
            xtype: 'modx-window-element-duplicate'
            ,record: r
            ,listeners: {
                'success': {fn:function() {this.refreshNode(this.cm.activeNode.id);},scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }
	
    ,removeElement: function(itm,e) {
        var id = this.cm.activeNode.id.substr(2);
        var oar = id.split('_');
        MODx.debug(MODx.action);
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('remove_this_confirm',{
                type: oar[0]
                ,name: this.cm.activeNode.attributes.name
            })
            ,url: MODx.config.connectors_url+'element/'+oar[0]+'.php'
            ,params: {
                action: 'remove'
                ,id: oar[2]
            }
            ,listeners: {
                'success': {fn:function() {
                    this.cm.activeNode.remove();
                    /* if editing the element being removed */
                    if (MODx.request.a == MODx.action['element/'+oar[0]+'/update'] && MODx.request.id == oar[2]) {
                        location.href = 'index.php?a='+MODx.action['welcome'];
                    }
                },scope:this}
            }
        });
    }

    ,activatePlugin: function(itm,e) {
        var id = this.cm.activeNode.id.substr(2);
        var oar = id.split('_');
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'element/plugin.php'
            ,params: {
                action: 'activate'
                ,id: oar[2]
            }
            ,listeners: {
                'success': {fn:function() {
                    this.refreshParentNode();
                },scope:this}
            }
        });
    }

    ,deactivatePlugin: function(itm,e) {
        var id = this.cm.activeNode.id.substr(2);
        var oar = id.split('_');
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'element/plugin.php'
            ,params: {
                action: 'deactivate'
                ,id: oar[2]
            }
            ,listeners: {
                'success': {fn:function() {
                    this.refreshParentNode();
                },scope:this}
            }
        });
    }

    ,quickCreate: function(itm,e,type) {
        var r = {
            category: this.cm.activeNode.attributes.pk || ''
        };
        var w = MODx.load({
            xtype: 'modx-window-quick-create-'+type
            ,record: r
            ,listeners: {
                'success':{fn:function() {this.refreshNode(this.cm.activeNode.id);},scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.setValues(r);
        w.show(e.target);
    }
    
    ,quickUpdate: function(itm,e,type) {
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'element/'+type+'.php'
            ,params: {
                action: 'get'
                ,id: this.cm.activeNode.attributes.pk
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var w = MODx.load({
                        xtype: 'modx-window-quick-update-'+type
                        ,record: r.object
                        ,listeners: {
                            'success':{fn:function(r) {
                                this.refreshNode(this.cm.activeNode.id);
                            },scope:this}
                            ,'hide':{fn:function() {this.destroy();}}
                        }
                    });
                    w.setValues(r.object);
                    w.show(e.target);
                },scope:this}
            }
        });
    }
	
    ,_createElement: function(itm,e,type) {
        var id = this.cm.activeNode.id.substr(2);
        var oar = id.split('_');
        var type = oar[0] == 'type' ? oar[1] : oar[0];
        var cat_id = oar[0] == 'type' ? 0 : (oar[1] == 'category' ? oar[2] : oar[3]);
        var a = MODx.action['element/'+type+'/create'];
        this.redirect('index.php?a='+a+'&category='+cat_id);
        this.cm.hide();
        return false;
    }
    
    ,afterSort: function(o) {
        var tn = o.event.target.attributes;
        if (tn.type == 'category') {
            var dn = o.event.dropNode.attributes;
            if (tn.id != 'n_category' && dn.type == 'category') {
                o.event.target.expand();
            } else {
                this.refreshNode(o.event.target.attributes.id,true);
                this.refreshNode('n_type_'+o.event.dropNode.attributes.type,true);
            }
        }
    }
		
    ,_handleDrop: function(e) {
        var target = e.target;
        if (e.point == 'above' || e.point == 'below') {return false;}
        if (target.attributes.classKey != 'modCategory' && target.attributes.classKey != 'root') { return false; }

        if (!this.isCorrectType(e.dropNode,target)) {return false;}
        if (target.attributes.type == 'category' && e.point == 'append') {return true;}

        return target.getDepth() > 0;
    }
    
    ,isCorrectType: function(dropNode,targetNode) {
        var r = false;
        /* types must be the same */
        if(targetNode.attributes.type == dropNode.attributes.type) {
            /* do not allow anything to be dropped on an element */
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


    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} n The current node
     * @param {Ext.EventObject} e The event object run.
     */
    ,_showContextMenu: function(n,e) {
        n.select();
        this.cm.activeNode = n;
        this.cm.removeAll();
        if (n.attributes.menu && n.attributes.menu.items) {
            this.addContextMenuItem(n.attributes.menu.items);
            this.cm.show(n.getUI().getEl(),'t?');
        } else {
            var m = [];
            switch (n.attributes.classKey) {
                case 'root':
                    m = this._getRootMenu(n);
                    break;
                case 'modCategory':
                    m = this._getCategoryMenu(n);
                    break;
                default:
                    m = this._getElementMenu(n);
                    break;
            }

            this.addContextMenuItem(m);
            this.cm.showAt(e.xy);
        }
        e.stopEvent();
    }

    ,_getQuickCreateMenu: function(n,m) {
        var ui = n.getUI();
        var mn = [];
        var types = ['template','tv','chunk','snippet','plugin'];
        var t;
        for (var i=0;i<types.length;i++) {
            t = types[i];
            if (ui.hasClass('pnew_'+t)) {
                mn.push({
                    text: _(t)
                    ,scope: this
                    ,type: t
                    ,handler: function(itm,e) {
                        this.quickCreate(itm,e,itm.type);
                    }
                });
            }
        }
        m.push({
            text: _('quick_create')
            ,handler: function() {return false;}
            ,menu: {
                items: mn
            }
        });
        return m;
    }

    ,_getElementMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];
        
        m.push({
            text: '<b>'+a.text+'</b>'
            ,handler: function() { return false; }
            ,header: true
        });
        m.push('-');
        
        if (ui.hasClass('pedit')) {
            m.push({
                text: _('edit_'+a.type)
                ,type: a.type
                ,pk: a.pk
                ,handler: function(itm,e) {
                    location.href = 'index.php?a='
                        + MODx.action['element/'+itm.type+'/update']
                        + '&id='+itm.pk;
                }
            });
            m.push({
                text: _('quick_update_'+a.type)
                ,type: a.type
                ,handler: function(itm,e) {
                    this.quickUpdate(itm,e,itm.type);
                }
            });
            if (a.classKey == 'modPlugin') {
                if (a.active) {
                    m.push({
                        text: _('plugin_deactivate')
                        ,type: a.type
                        ,handler: this.deactivatePlugin
                    });
                } else {
                    m.push({
                        text: _('plugin_activate')
                        ,type: a.type
                        ,handler: this.activatePlugin
                    });
                }
            }
        }
        if (ui.hasClass('pnew')) {
            m.push({
                text: _('duplicate_'+a.type)
                ,pk: a.pk
                ,type: a.type
                ,handler: function(itm,e) {
                    this.duplicateElement(itm,e,itm.pk,itm.type);
                }
            });
        }
        if (ui.hasClass('pdelete')) {
            m.push({
                text: _('remove_'+a.type)
                ,handler: this.removeElement
            });
        }
        m.push('-');
        if (ui.hasClass('pnew')) {
            m.push({
                text: _('add_to_category_'+a.type)
                ,handler: this._createElement
            });
        }
        if (ui.hasClass('pnewcat')) {
            m.push({
                text: _('new_category')
                ,handler: this.createCategory
            });
        }
        return m;
    }

    ,_getCategoryMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];

        m.push({
            text: '<b>'+a.text+'</b>'
            ,handler: function() { return false; }
            ,header: true
        });
        m.push('-');
        if (ui.hasClass('pnewcat')) {
            m.push({
                text: _('category_create')
                ,handler: this.createCategory
            });
        }
        if (ui.hasClass('peditcat')) {
            m.push({
                text: _('category_rename')
                ,handler: this.renameCategory
            });
        }
        if (m.length > 2) {m.push('-');}

        if (a.elementType) {
            m.push({
                text: _('add_to_category_'+a.type)
                ,handler: this._createElement
            });
        }
        this._getQuickCreateMenu(n,m);

        if (ui.hasClass('pdelcat')) {
            m.push('-');
            m.push({
                text: _('category_remove')
                ,handler: this.removeCategory
            });
        }
        return m;
    }
    
    ,_getRootMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];

        if (ui.hasClass('pnew')) {
            m.push({
                text: _('new_'+a.type)
                ,handler: this._createElement
            });
            m.push({
                text: _('quick_create_'+a.type)
                ,type: a.type
                ,handler: function(itm,e) {
                    this.quickCreate(itm,e,itm.type);
                }
            });
        }

        if (ui.hasClass('pnewcat')) {
            if (ui.hasClass('pnew')) {m.push('-');}
            m.push({
                text: _('new_category')
                ,handler: this.createCategory
            });
        }
        
        return m;
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
    this.ident = config.ident || 'dupeel-'+Ext.id();
    var flds = [{
        xtype: 'hidden'
        ,name: 'id'
        ,id: 'modx-'+this.ident+'-id'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('element_name_new')
        ,name: config.record.type == 'template' ? 'templatename' : 'name'
        ,id: 'modx-'+this.ident+'-name'
        ,anchor: '90%'
    }];
    if (config.record.type == 'tv') {
        flds.push({
            xtype: 'xcheckbox'
            ,fieldLabel: _('element_duplicate_values')
            ,labelSeparator: ''
            ,name: 'duplicateValues'
            ,id: 'modx-'+this.ident+'-duplicate-values'
            ,anchor: '95%'
            ,inputValue: 1
            ,checked: false
        });
    }
    Ext.applyIf(config,{
        title: _('element_duplicate')
        ,url: MODx.config.connectors_url+'element/'+config.record.type+'.php'
        ,action: 'duplicate'
        ,fields: flds
        ,labelWidth: 150
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
    this.ident = config.ident || 'rencat-'+Ext.id();
    Ext.applyIf(config,{
        title: _('category_rename')
        ,height: 150
        ,width: 350
        ,url: MODx.config.connectors_url+'element/category.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
            ,value: config.record.id
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'category'
            ,id: 'modx-'+this.ident+'-category'
            ,width: 150
            ,value: config.record.category
            ,anchor: '90%'
        }]
    });
    MODx.window.RenameCategory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RenameCategory,MODx.Window);
Ext.reg('modx-window-category-rename',MODx.window.RenameCategory);
