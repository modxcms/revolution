/**
 * Loads a grid of modContextGroups.
 *
 * @class MODx.grid.ContextGroup
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-context-groups
 */
MODx.grid.ContextGroup = function(config = {}) {
    Ext.applyIf(config, {
        title: _('context_groups'),
        id: 'modx-grid-context-group',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Context/Group/GetList'
        },
        fields: [
            'id',
            'name',
            'description',
            'rank',
            'contexts'
        ],
        paging: true,
        autosave: true,
        save_action: 'Context/Group/UpdateFromGrid',
        remoteSort: true,
        primaryKey: 'id',
        columns: [{
            header: _('name'),
            dataIndex: 'name',
            width: 200,
            sortable: true,
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        }, {
            header: _('description'),
            dataIndex: 'description',
            width: 400,
            sortable: false,
            editor: {
                xtype: 'textarea'
            }
        }, {
            header: _('rank'),
            dataIndex: 'rank',
            width: 80,
            align: 'center',
            sortable: true,
            editor: {
                xtype: 'numberfield'
            }
        }, {
            header: _('contexts'),
            dataIndex: 'contexts',
            width: 100,
            align: 'center',
            sortable: false
        }],
        tbar: [{
            text: _('create'),
            cls: 'primary-button',
            handler: this.createGroup,
            scope: this
        }]
    });
    MODx.grid.ContextGroup.superclass.constructor.call(this, config);
};
Ext.extend(MODx.grid.ContextGroup, MODx.grid.Grid, {
    getMenu: function() {
        return [{
            text: _('delete'),
            handler: this.removeGroup,
            scope: this
        }];
    },

    removeGroup: function() {
        MODx.msg.confirm({
            title: _('warning'),
            text: _('confirm_remove'),
            url: this.config.url,
            params: {
                action: 'Context/Group/Remove',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: this.afterAction,
                    scope: this
                }
            }
        });
    },

    createGroup: function(btn, e) {
        if (this.createWindow) {
            this.createWindow.destroy();
        }
        this.createWindow = MODx.load({
            xtype: 'modx-window-context-group-create',
            listeners: {
                success: {
                    fn: this.afterAction,
                    scope: this
                }
            }
        });
        this.createWindow.show(e.target);
    },

    afterAction: function() {
        this.refresh();
        const tree = Ext.getCmp('modx-resource-tree');
        if (tree) {
            tree.refresh();
        }
    }
});
Ext.reg('modx-grid-context-groups', MODx.grid.ContextGroup);

/**
 * @class MODx.window.CreateContextGroup
 * @extends MODx.Window
 * @xtype modx-window-context-group-create
 */
MODx.window.CreateContextGroup = function(config = {}) {
    Ext.applyIf(config, {
        title: _('context_group_create'),
        url: MODx.config.connector_url,
        action: 'Context/Group/Create',
        fields: [{
            xtype: 'textfield',
            fieldLabel: _('name'),
            name: 'name',
            anchor: '100%',
            allowBlank: false
        }, {
            xtype: 'textarea',
            fieldLabel: _('description'),
            name: 'description',
            anchor: '100%'
        }, {
            xtype: 'numberfield',
            fieldLabel: _('rank'),
            name: 'rank',
            anchor: '100%',
            value: 0
        }]
    });
    MODx.window.CreateContextGroup.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateContextGroup, MODx.Window);
Ext.reg('modx-window-context-group-create', MODx.window.CreateContextGroup);
