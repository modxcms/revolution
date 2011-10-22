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
        ,url: MODx.config.connectors_url + 'system/menu.php'
        ,action: 'getNodes'
        ,primaryKey: 'text'
        ,useDefaultToolbar: true
        ,ddGroup: 'modx-menu'
        ,tbar: [{
            text: _('menu_create')
            ,handler: this.createMenu
            ,scope: this
        }]
    });
    MODx.tree.Menu.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.Menu, MODx.tree.Tree, {
    windows: {}
	
    ,createMenu: function(n,e) {
        var r = {};
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
        this.windows.create_menu.setValues(r);
        this.windows.create_menu.show(e.target);
    }
	
    ,updateMenu: function(n,e) {
        var r = this.cm.activeNode.attributes.data;
        Ext.apply(r,{
            action_id: r.action
            ,new_text: r.text
        });
        if (!this.windows.update_menu) {
            this.windows.update_menu = MODx.load({
                xtype: 'modx-window-menu-update'
                ,record: r
                ,listeners: {
                    'success': {fn:function(r) { this.refresh(); },scope:this}
                }
            });
        }
        this.windows.update_menu.setValues(r);
        this.windows.update_menu.show(e.target);
    }
	
    ,removeMenu: function(n,e) {
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('menu_confirm_remove')
            ,url: this.config.url
            ,params: {
                action: 'remove'
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
                    text: _('action_place_here')
                    ,handler: this.createMenu
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
    Ext.applyIf(config,{
        title: _('menu_create')
        ,width: 480
        ,height: 400
        ,url: MODx.config.connectors_url+'system/menu.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'parent'
        },{
            fieldLabel: _('lexicon_key')
            ,name: 'text'
            ,xtype: 'textfield'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,xtype: 'textfield'
            ,allowBlank: true
            ,anchor: '100%'
        },{
            fieldLabel: _('action')
            ,name: 'action_id'
            ,hiddenName: 'action_id'
            ,xtype: 'modx-combo-action'
            ,id: 'modx-cmen-action'
            ,anchor: '100%'
        },{
            fieldLabel: _('icon')
            ,name: 'icon'
            ,xtype: 'textfield'
            ,allowBlank: true
            ,anchor: '100%'
        },{
            fieldLabel: _('parameters')
            ,name: 'params'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('handler')
            ,name: 'handler'
            ,xtype: 'textarea'
            ,anchor: '100%'
            ,grow: false
        },{
            fieldLabel: _('permissions')
            ,name: 'permissions'
            ,xtype: 'textfield'
            ,anchor: '100%'
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
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-menu-update
 */
MODx.window.UpdateMenu = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('menu_update')
        ,width: 480
        ,height: 400
        ,url: MODx.config.connectors_url+'system/menu.php'
        ,action: 'update'
        ,fields: [{
            name: 'parent'
            ,xtype: 'hidden'
        },{
            name: 'text'
            ,xtype: 'hidden'
        },{
            fieldLabel: _('lexicon_key')
            ,name: 'new_text'
            ,xtype: 'textfield'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,xtype: 'textfield'
            ,allowBlank: true
            ,anchor: '100%'
        },{
            fieldLabel: _('action')
            ,name: 'action_id'
            ,hiddenName: 'action_id'
            ,xtype: 'modx-combo-action'
            ,id: 'modx-umen-action'
        },{
            fieldLabel: _('icon')
            ,name: 'icon'
            ,xtype: 'textfield'
            ,allowBlank: true
            ,anchor: '100%'
        },{
            fieldLabel: _('parameters')
            ,name: 'params'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('handler')
            ,name: 'handler'
            ,xtype: 'textarea'
            ,anchor: '100%'
            ,grow: false
        },{
            fieldLabel: _('permissions')
            ,name: 'permissions'
            ,xtype: 'textfield'
            ,anchor: '100%'
        }]
    });
    MODx.window.UpdateMenu.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateMenu,MODx.Window);
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
        ,url: MODx.config.connectors_url+'system/menu.php'
        ,fields: ['text','text_lex']
        ,displayField: 'text_lex'
        ,valueField: 'text'
        ,listWidth: 300
        ,editable: false
    });
    MODx.combo.Menu.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Menu,MODx.combo.ComboBox);
Ext.reg('modx-combo-menu',MODx.combo.Menu);
