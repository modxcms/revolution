/**
 * Generates a Tree for managing the Top Menu
 *
 * @class MODx.tree.Menu
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-menu
 */
MODx.tree.Menu = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        root_id: 'n_'
        ,root_name: _('menu_top')
        ,rootVisible: true
        ,expandFirst: true
        ,enableDrag: true
        ,enableDrop: true
        ,url: MODx.config.connector_url
        ,action: 'system/menu/getNodes'
        ,sortAction: 'system/menu/sort'
        ,primaryKey: 'text'
        ,useDefaultToolbar: true
        ,ddGroup: 'modx-menu'
        ,tbar: [{
            text: _('menu_create')
            ,cls:'primary-button'
            ,handler: this.createMenu
            ,scope: this
        }]
    });
    MODx.tree.Menu.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.Menu, MODx.tree.Tree, {
    windows: {}

    ,createMenu: function(n,e) {
        var r = {
            parent: ''
        };
        if (this.cm && this.cm.activeNode && this.cm.activeNode.attributes && this.cm.activeNode.attributes.data) {
            r['parent'] = this.cm.activeNode.attributes.data.text;
        }
        if (!this.windows.create_menu) {
            this.windows.create_menu = MODx.load({
                xtype: 'modx-window-menu-create'
                ,record: r
                ,listeners: {
                    'success': {fn:function(r) { this.refresh(); },scope:this}
                }
            });
        }
        this.windows.create_menu.reset();
        this.windows.create_menu.setValues(r);
        this.windows.create_menu.show(e.target);
    }

    ,updateMenu: function(n,e) {
        var r = this.cm.activeNode.attributes.data;
        Ext.apply(r,{
            action_id: r.action
            ,new_text: r.text
        });
        this.windows.update_menu = MODx.load({
            xtype: 'modx-window-menu-update'
            ,record: r
            ,listeners: {
                'success': {fn:function(r) { this.refresh(); },scope:this}
            }
        });
        this.windows.update_menu.setValues(r);
        this.windows.update_menu.show(e.target);
    }

    ,removeMenu: function(n,e) {
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('menu_confirm_remove')
            ,url: this.config.url
            ,params: {
                action: 'system/menu/remove'
                ,text: this.cm.activeNode.attributes.pk
            }
            ,listeners: {
                'success':{fn:this.refresh,scope:this}
            }
        });
    }

    ,getMenu: function(n,e) {
        var m = [];
        switch (n.attributes.type) {
            case 'menu':
                m.push({
                    text: _('menu_update')
                    ,handler: this.updateMenu
                });
                m.push('-');
                m.push({
                    text: _('menu_remove')
                    ,handler: this.removeMenu
                });
                break;
            default:
                m.push({
                    text: _('menu_create')
                    ,handler: this.createMenu
                });
                break;
        }
        return m;
    }
});
Ext.reg('modx-tree-menu',MODx.tree.Menu);

/**
 * Generates the Create Menu window
 *
 * @class MODx.window.CreateMenu
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-menu-create
 */
MODx.window.CreateMenu = function(config) {
    config = config || {};
    this.ident = config.ident || 'modx-cmenu-'+Ext.id();
    Ext.applyIf(config,{
        title: _('menu_create')
        ,width: 600
        // ,height: 400
        ,url: MODx.config.connector_url
        ,action: 'system/menu/create'
        ,fields: [{
            xtype: 'modx-combo-menu'
            ,name: 'parent'
            ,hiddenName: 'parent'
            ,anchor: '100%'
            ,fieldLabel: _('parent')
        },{
            layout: 'column'
            ,border: false
            ,style: 'padding-top: 15px;'
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,anchor: '100%'
                ,border: false
            }
            ,items: [{
                columnWidth: .5
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'previous_text'
                    ,value: config.record && config.record.text ? config.record.text : ''
                },{
                    fieldLabel: _('lexicon_key')
                    ,description: MODx.expandHelp ? '' : _('lexicon_key_desc')
                    ,name: 'text'
                    ,xtype: 'textfield'
                    ,allowBlank: false
                    ,anchor: '100%'
                    ,id: this.ident+'-text'
                    //,readOnly: config.update ? true : false
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-text'
                    ,html: _('lexicon_key_desc')
                    ,cls: 'desc-under'
                },{
                    fieldLabel: _('description')
                    ,description: MODx.expandHelp ? '' : _('description_desc')
                    ,name: 'description'
                    ,xtype: 'textfield'
                    ,allowBlank: true
                    ,anchor: '100%'
                    ,id: this.ident+'-description'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-description'
                    ,html: _('description_desc')
                    ,cls: 'desc-under'
                },{
                    fieldLabel: _('handler')
                    ,description: MODx.expandHelp ? '' : _('handler_desc')
                    ,name: 'handler'
                    ,xtype: 'textarea'
                    ,anchor: '100%'
                    ,grow: false
                    ,id: this.ident+'-handler'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-handler'
                    ,html: _('handler_desc')
                    ,cls: 'desc-under'
                },{
                    fieldLabel: _('permissions')
                    ,description: MODx.expandHelp ? '' : _('permissions_desc')
                    ,name: 'permissions'
                    ,xtype: 'textfield'
                    ,anchor: '100%'
                    ,id: this.ident+'-permissions'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-permissions'
                    ,html: _('permissions_desc')
                    ,cls: 'desc-under'
                }]
            },{
                columnWidth: .5
                ,items: [{
                    fieldLabel: _('action')
                    ,description: MODx.expandHelp ? '' : _('action_desc')
                    ,name: 'action_id'
                    ,hiddenName: 'action_id'
                    ,xtype: 'textfield'
                    ,anchor: '100%'
                    ,id: this.ident+'-action-id'
                    //,allowBlank: false
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-action-id'
                    ,html: _('action_desc')
                    ,cls: 'desc-under'
                },{
                    fieldLabel: _('parameters')
                    ,description: MODx.expandHelp ? '' : _('parameters_desc')
                    ,name: 'params'
                    ,xtype: 'textfield'
                    ,anchor: '100%'
                    ,id: this.ident+'-params'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-params'
                    ,html: _('parameters_desc')
                    ,cls: 'desc-under'
                },{
                    fieldLabel: _('namespace')
                    ,description: MODx.expandHelp ? '' : _('namespace_desc')
                    ,name: 'namespace'
                    ,xtype: 'textfield'
                    ,anchor: '100%'
                    ,value: 'core'
                    ,id: this.ident+'-namespace'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-namespace'
                    ,html: _('namespace_desc')
                    ,cls: 'desc-under'
                },{
                    fieldLabel: _('icon')
                    ,description: MODx.expandHelp ? '' : _('icon_desc')
                    ,name: 'icon'
                    ,xtype: 'textfield'
                    ,anchor: '100%'
                    ,id: this.ident+'-icon'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-icon'
                    ,html: _('icon_desc')
                    ,cls: 'desc-under'
                }]
            }]
        }]
    });
    MODx.window.CreateMenu.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateMenu,MODx.Window);
Ext.reg('modx-window-menu-create',MODx.window.CreateMenu);

/**
 * Generates the Update Menu window
 *
 * @class MODx.window.UpdateMenu
 * @extends MODx.window.CreateMenu
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-menu-update
 */
MODx.window.UpdateMenu = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('menu_update')
        ,action: 'system/menu/update'
    });
    MODx.window.UpdateMenu.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateMenu,MODx.window.CreateMenu);
Ext.reg('modx-window-menu-update',MODx.window.UpdateMenu);

/**
 * Displays a dropdown of modMenus
 *
 * @class MODx.combo.Menu
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of options.
 * @xtype modx-combo-menu
 */
MODx.combo.Menu = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'menu'
        ,hiddenName: 'menu'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/menu/getlist'
            ,combo: true
            ,limit: 0
            ,showNone: true
        }
        ,fields: ['text','text_lex']
        ,displayField: 'text_lex'
        ,valueField: 'text'
        // ,listWidth: 300
        ,editable: false
    });
    MODx.combo.Menu.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Menu,MODx.combo.ComboBox);
Ext.reg('modx-combo-menu',MODx.combo.Menu);
