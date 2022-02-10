Ext.ns('MODx.orm');
MODx.orm.Tree = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        height: 300
        ,width: 400
        ,useArrows:true
        ,autoScroll:true
        ,animate:true
        ,enableDD: false
        ,border: false
        ,containerScroll: true
        ,rootVisible: false
        ,root: new Ext.tree.AsyncTreeNode({
            expanded: true
            ,children: config.data
            ,listeners: {
                'load': {fn:function() {
                    this.expandAll();
                },scope:this}
            }
        })
        ,tbar: [{
            text: _('orm_attribute_add')
            ,handler: function(btn,e) { this.addAttribute(btn,e); }
            ,scope: this
        },{
            text: _('orm_container_add')
            ,handler: function(btn,e) { this.addContainer(btn,e); }
            ,scope: this
        }]
        ,menuConfig: { defaultAlign: 'tl-b?' ,enableScrolling: false }
    });
    MODx.orm.Tree.superclass.constructor.call(this,config);
    this.config = config;
    this.on('click',this.onClick,this);
    this.on('contextmenu',this._showContextMenu,this);
    this.cm = new Ext.menu.Menu(config.menuConfig);
};
Ext.extend(MODx.orm.Tree,Ext.tree.TreePanel,{
    windows: {}
    ,getSelectedNode: function() {
        return this.getSelectionModel().getSelectedNode();
    }
    ,addContextMenuItem: function(items) {
        var a = items, l = a.length;
        for(var i = 0; i < l; i++) {
            a[i].scope = this;
            this.cm.add(a[i]);
        }
    }
    ,_showContextMenu: function(node,e) {
        node.select();
        this.cm.activeNode = node;
        this.cm.removeAll();
        var m = [];
        if (node.attributes.leaf) {
            m.push({
                text: _('orm_attribute_remove')
                ,handler: this.removeAttribute
                ,scope: this
            });
        } else {
            m.push({
                text: _('orm_attribute_add_below')
                ,handler: function(itm,e) {
                    this.addAttribute(itm,e,this.cm.activeNode);
                }
                ,scope: this
            });
            m.push({
                text: _('orm_container_add_below')
                ,handler: function(itm,e) {
                    this.addContainer(itm,e,this.cm.activeNode);
                }
                ,scope: this
            });
            m.push('-');
            m.push({
                text: _('orm_container_rename')
                ,handler: function(itm,e) {
                    this.renameContainer(itm,e,this.cm.activeNode);
                }
                ,scope: this
            });
            m.push('-');
            m.push({
                text: _('orm_container_remove')
                ,handler: this.removeContainer
                ,scope: this
            });
        }

        if (m.length > 0) {
            this.addContextMenuItem(m);
            this.cm.showAt(e.xy);
        }
        e.stopEvent();
    }

    ,markFormPanelDirty: function(f) {
        var fp = Ext.getCmp(f ? f : this.config.formPanel);
        if (fp) {
            fp.markDirty();
        }
    }

    ,removeAttribute: function(btn,e) {
        var n = this.getSelectedNode();
        if (n) {
            Ext.Msg.confirm(_('orm_attribute_remove'),_('orm_attribute_remove_confirm'),function(e) {
                if (e == 'yes') {
                    n.remove();
                    this.markFormPanelDirty();
                }
            },this);
        }
    }
    ,removeContainer: function(btn,e) {
        var n = this.getSelectedNode();
        if (n) {
            Ext.Msg.confirm(_('orm_container_remove'),_('orm_container_remove_confirm'),function(e) {
                if (e == 'yes') {
                    n.remove();
                    this.markFormPanelDirty();
                }
            },this);
        }
    }
    ,addContainer: function(btn,e,node) {
        var r = {};
        if (node) { r.parent = node.id; }

        if (!this.windows.addContainer) {
            this.windows.addContainer = MODx.load({
                xtype: 'modx-orm-window-container-add'
                ,record: r
                ,tree: this
                ,listeners: {
                    'success': {fn:function(r) {
                        if (typeof(r.name) !== 'undefined') {
                            r.name = r.name.replace(/"/g, '');
                        }

                        var n = new Ext.tree.TreeNode({
                            text: Ext.util.Format.htmlEncode(r.name)
                            ,id: r.name
                            ,name: r.name
                            ,expanded: true
                            ,expandable: true
                            ,leaf: false
                            ,iconCls: 'icon-folder'
                        });

                        var nd = this.getSelectedNode();
                        var pn = nd && !nd.attributes.value ? nd : this.getRootNode();
                        pn.appendChild(n);
                        this.markFormPanelDirty();
                    },scope:this}
                }
            });
        }
        this.windows.addContainer.setValues(r);
        this.windows.addContainer.show(e.target);
    }
    ,renameContainer: function(btn,e,node) {
        var r = {};
        if (node) { r.parent = node.id; }

        if (!this.windows.renameContainer) {
            this.windows.renameContainer = MODx.load({
                xtype: 'modx-orm-window-container-rename'
                ,record: r
                ,listeners: {
                    'success': {fn:function(r) {
                        if (typeof(r.name) !== 'undefined') {
                            r.name = r.name.replace(/"/g, '');
                        }

                        var nd = this.getSelectedNode();
                        nd.setId(r.name);
                        nd.setText(r.name);
                        nd.attributes.name = r.name;
                        this.markFormPanelDirty();
                    },scope:this}
                }
            });
        }
        this.windows.renameContainer.setValues(r);
        this.windows.renameContainer.show(e.target);
    }

    ,renderItemText: function(item) {
        return item.text;
    }

    ,addAttribute: function(btn,e,node) {
        var r = {};
        if (node) { r.parent = node.id; }

        if (!this.windows.addAttribute) {
            this.windows.addAttribute = MODx.load({
                xtype: 'modx-orm-window-attribute-add'
                ,record: r
                ,tree: this
                ,listeners: {
                    'success': {fn:function(r) {
                        if (typeof(r.name) !== 'undefined') {
                            r.name = r.name.replace(/"/g, '');
                        }

                        var n = new Ext.tree.TreeNode({
                            text: Ext.util.Format.htmlEncode(r.name) + ' - <i>' + Ext.util.Format.htmlEncode(r.value) + '</i>'
                            ,id: r.id
                            ,name: r.name
                            ,leaf: true
                            ,value: r.value
                            ,iconCls: 'icon-terminal'
                        });

                        var nd = this.getSelectedNode();
                        var pn = nd && !nd.attributes.value ? nd : this.getRootNode();
                        pn.appendChild(n);
                        this.markFormPanelDirty();
                    },scope:this}
                }
            });
        }
        this.windows.addAttribute.setValues(r);
        this.windows.addAttribute.show(e.target);
    }
    ,encode: function(node) {
        if (!node) { node = this.getRootNode(); }
        var _encode = function(node) {
            var resultNode = {};
            var kids = node.childNodes;
            for (var i = 0;i < kids.length;i=i+1) {
                var n = kids[i];
                var c = _encode(n);
                if (n.attributes.value != null && n.attributes.value != undefined) {
                    resultNode[n.attributes.name] = n.attributes.value;
                } else {
                    resultNode[n.attributes.name] = c;
                }
            }
            return resultNode;
        };
        var nodes = _encode(node);
        return Ext.encode(nodes);
    }

    ,onClick: function(n) {
        var vs = n.attributes;
        if (vs.value != null && vs.value != undefined) {
            var f = Ext.getCmp(this.config.formPanel).getForm();
            f.findField(this.config.prefix+'_id').setValue(vs.id);
            f.findField(this.config.prefix+'_name').setValue(vs.name);
            f.findField(this.config.prefix+'_value').setValue(vs.value);
        }
    }

    ,childExistsOnSelected: function(id) {
        var n = this.getSelectedNode();
        var c;
        if (Ext.isEmpty(n)) {
            c = this.getNodeById(id);
            if (c && !Ext.isEmpty(c.parentNode.text)) { c = null; } /* ignore children */
        } else {
            c = n.findChild('id',id);
        }
        if (!Ext.isEmpty(c)) {
            return true;
        }
        return false;
    }
});
Ext.reg('modx-orm-tree',MODx.orm.Tree);


MODx.orm.Form = function(config) {
    Ext.applyIf(config,{
        layout: 'form'
        ,xtype: 'fieldset'
        ,labelWidth: 150
        ,autoHeight: true
        ,border: false
        ,bodyStyle: 'padding: 15px 0;'
        ,defaults: { msgTarget: 'side', border: false }
        ,buttonAlign: 'right'
        ,items: [{
            xtype: 'textfield'
            ,name: config.prefix+'_name'
            ,fieldLabel: _('name')
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,name: config.prefix+'_value'
            ,fieldLabel: _('value')
            ,anchor: '100%'
        },{
            xtype: 'hidden'
            ,name: config.prefix+'_id'
        }]
        ,buttons: [{
            text: _('set')
            ,cls: 'primary-button'
            ,handler: this.saveProperty
            ,scope: this
        }]
    });
    MODx.orm.Form.superclass.constructor.call(this,config);
    this.config = config;
}
Ext.extend(MODx.orm.Form,Ext.Panel,{
    saveProperty: function() {
        var fp = Ext.getCmp(this.config.formPanel);
        var t = Ext.getCmp(this.config.treePanel);
        if (!fp || !t) return false;
        var f = fp.getForm();
        var n = t.getSelectionModel().getSelectedNode();

        var txt = f.findField(this.config.prefix+'_name').getValue().replace(/"/g, '');
        var val = f.findField(this.config.prefix+'_value').getValue();
        n.attributes.id = f.findField(this.config.prefix+'_id').getValue();
        n.attributes.text = txt;
        n.attributes.name = txt;
        n.attributes.value = val;
        n.setText(txt+' - <i>'+Ext.util.Format.ellipsis(val,33)+'</i>');
        fp.markDirty();
        return true;
    }
});
Ext.reg('modx-orm-form',MODx.orm.Form);


MODx.window.AddOrmAttribute = function(config) {
    config = config || {};
    this.ident = config.ident || 'ormcattr'+Ext.id();
    Ext.applyIf(config,{
        title: _('orm_attribute_add')
        ,id: this.ident
        ,fields: [{
            xtype: 'hidden'
            ,name: 'parent'
            ,value: 0
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('value')
            ,name: 'value'
            ,id: 'modx-'+this.ident+'-value'
            ,anchor: '100%'
        }]
    });
    MODx.window.AddOrmAttribute.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddOrmAttribute,MODx.Window,{
    submit: function() {
        var v = this.fp.getForm().getValues();

        if (this.config.tree.childExistsOnSelected(v.name)) {
            this.fp.getForm().markInvalid({
                name: _('orm_attribute_ae')
            });
            return false;
        }

        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success',v)) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        }
        return false;
    }
});
Ext.reg('modx-orm-window-attribute-add',MODx.window.AddOrmAttribute);


MODx.window.AddOrmContainer = function(config) {
    config = config || {};
    this.ident = config.ident || 'ormccont'+Ext.id();
    Ext.applyIf(config,{
        title: _('orm_container_add')
        ,id: this.ident
        ,fields: [{
            xtype: 'hidden'
            ,name: 'parent'
            ,value: 0
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,anchor: '100%'
            ,allowBlank: false
        }]
    });
    MODx.window.AddOrmContainer.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddOrmContainer,MODx.Window,{
    submit: function() {
        var v = this.fp.getForm().getValues();

        if (this.config.tree.childExistsOnSelected(v.name)) {
            this.fp.getForm().markInvalid({
                name: _('orm_attribute_ae')
            });
            return false;
        }

        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success',v)) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        }
        return false;
    }
});
Ext.reg('modx-orm-window-container-add',MODx.window.AddOrmContainer);

MODx.window.RenameOrmContainer = function(config) {
    config = config || {};
    this.ident = config.ident || 'ormrcont'+Ext.id();
    Ext.applyIf(config,{
        title: _('orm_container_rename')
        ,id: this.ident
        ,fields: [{
            xtype: 'hidden'
            ,name: 'parent'
            ,value: 0
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,anchor: '100%'
            ,allowBlank: false
        }]
    });
    MODx.window.RenameOrmContainer.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RenameOrmContainer,MODx.Window,{
    submit: function() {
        var v = this.fp.getForm().getValues();

        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success',v)) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        }
        return false;
    }
});
Ext.reg('modx-orm-window-container-rename',MODx.window.RenameOrmContainer);
