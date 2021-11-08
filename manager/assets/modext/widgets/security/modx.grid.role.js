/**
 * Loads a grid of roles.
 *
 * @class MODx.grid.Role
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-grid-role
 */
MODx.grid.Role = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('roles')
        ,id: 'modx-grid-role'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/Role/GetList'
        }
        ,fields: ['id','name','description','authority','perm']
        ,paging: true
        ,autosave: true
        ,save_action: 'Security/Role/UpdateFromGrid'
        ,clicksToEdit: 1
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 50
            ,sortable: true
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,id: 'role--name'
            ,width: 150
            ,sortable: true
            ,editor: {
                xtype: 'textfield'
            }
            ,renderer: {
                fn: function(value, metaData, record) {
                    if (!MODx.perm.save_role || !MODx.perm.edit_role || this.isProtected(record.data.authority)) {
                        metaData.css = 'editor-disabled';
                        // console.log('metaData: ', metaData);
                        // console.log('this', this);
                    }
                    return value;
                },
                scope: this
            }
            ,listeners: {
                dblclick: function(col, gp, rowIndex, e) {
                    // console.log('dblclick, col: ', col);
                    // console.log('dblclick, gp: ', gp);
                    // console.log('dblclick, e: ', e);

                }
            }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,id: 'role--description'
            ,width: 350
            ,editor: {
                xtype: 'textarea'
            }
            ,renderer: {
                fn: function(value, metaData, record) {
                    if (!MODx.perm.save_role || !MODx.perm.edit_role || this.isProtected(record.data.authority)) {
                        metaData.css = 'editor-disabled';
                    }
                    return value;
                },
                scope: this
            }
        },{
            header: _('authority')
            ,dataIndex: 'authority'
            ,id: 'role--authority'
            ,width: 60
            ,sortable: true
            ,editor: {
                xtype: 'textfield'
            }
            ,renderer: {
                fn: function(value, metaData, record, rowIndex, colIndex) {
                    const isProtected = this.isProtected(record.data.authority);
                    if (!MODx.perm.save_role || !MODx.perm.edit_role || isProtected) {
                        metaData.css = 'editor-disabled';
                        if (isProtected) {
                            console.log(`protected row #${rowIndex} metaData:`, metaData);
                        }
                    }
                    return value;
                },
                scope: this
            }
        }]
        ,tbar: [{
            text: _('create')
            ,hidden: !MODx.perm.new_role || !MODx.perm.save_role
            ,cls:'primary-button'
            ,handler: this.createRole
            ,scope: this
        }]
    });
    MODx.grid.Role.superclass.constructor.call(this,config);

    this.on('render', function() {
        if (!MODx.perm.save_role || !MODx.perm.edit_role) {
            const   colModel = this.getColumnModel(),
                    editableCols = ['role--name', 'role--description', 'role--authority']
            ;
            editableCols.forEach(colId => {
                const colIndex = colModel.getIndexById(colId);
                colModel.setEditable(colIndex, false);
            });
        }
    });
};
Ext.extend(MODx.grid.Role,MODx.grid.Grid,{

    /**
     * @property {Function} isProtected - Used to exclude the permanent roles of
     * 'Super User' (0) and 'Member' (9999) for all permissions levels
     *
     * @param {Number} authority - The authority level for the role being tested
     * @return {Boolean}
     */
    isProtected: function(authority) {
        const protected = [0, 9999];
        return protected.includes(authority);
    }

    ,actionsColumnRenderer: function(value, metaData, record, rowIndex, colIndex, store) {
        // console.log(`actionsColumnRenderer called from grid.role`);
        // console.log('actionsColumnRenderer store: ',store);
        // console.log('actionsColumnRenderer value: ',value);
        // console.log('actionsColumnRenderer rowIndex: ',rowIndex);
        // console.log('actionsColumnRenderer record: ',record);
        // console.log('actionsColumnRenderer in lexicon.grid, rowIndex: ',rowIndex);
        // console.log('actionsColumnRenderer in lexicon.grid, arguments: ',arguments);
        // console.log('actionsColumnRenderer metaData: ',metaData);

        if (MODx.perm.delete_role && !this.isProtected(record.data.authority)) {
            return this.superclass().actionsColumnRenderer.apply(this, arguments);
        }
    }

    ,getMenu: function() {
        const record = this.getSelectionModel().getSelected();
        let m = [];
        if (MODx.perm.delete_role && !this.isProtected(record.data.authority)) {
            m.push({
                text: _('delete'),
                handler: this.remove.createDelegate(this,['role_remove_confirm', 'Security/Role/Remove'])
            });
        }
        return m;
    }

    ,createRole: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-role-create'
            ,listeners: {
                'success': {fn: function() {
                    this.refresh();
                },scope:this}
            }
        });
    }
});
Ext.reg('modx-grid-role',MODx.grid.Role);

/**
 * @class MODx.window.CreateRole
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-role-create
 */
MODx.window.CreateRole = function(config) {
    config = config || {};
    this.ident = config.ident || 'crole'+Ext.id();
    Ext.applyIf(config,{
        title: _('create')
        ,url: MODx.config.connector_url
        ,action: 'Security/Role/Create'
        ,fields: [{
            name: 'name'
            ,fieldLabel: _('name')
            ,id: 'modx-'+this.ident+'-name'
            ,xtype: 'textfield'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-name'
            ,html: _('role_desc_name')
            ,cls: 'desc-under'
        },{
            name: 'authority'
            ,fieldLabel: _('authority')
            ,xtype: 'textfield'
            ,id: 'modx-'+this.ident+'-authority'
            ,allowBlank: false
            ,allowNegative: false
            ,value: 0
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-authority'
            ,html: _('role_desc_authority')
            ,cls: 'desc-under'
        },{
            name: 'description'
            ,fieldLabel: _('description')
            ,id: 'modx-'+this.ident+'-description'
            ,xtype: 'textarea'
            ,anchor: '100%'
            ,grow: true
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-description'
            ,html: _('role_desc_description')
            ,cls: 'desc-under'
        }]
        ,keys: []
    });
    MODx.window.CreateRole.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateRole,MODx.Window);
Ext.reg('modx-window-role-create',MODx.window.CreateRole);
