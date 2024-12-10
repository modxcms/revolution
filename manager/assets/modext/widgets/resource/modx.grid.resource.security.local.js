/**
 * @class MODx.grid.ResourceSecurity
 * @extends MODx.grid.LocalGrid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-resource-security
 */
MODx.grid.ResourceSecurity = function(config = {}) {
    const accessCheckboxCol = new Ext.ux.grid.CheckColumn({
        header: _('access'),
        dataIndex: 'access',
        width: 40,
        sortable: true,
        hidden: !MODx.perm.resourcegroup_resource_edit
    });
    Ext.applyIf(config, {
        id: 'modx-grid-resource-security',
        fields: [
            'id',
            'name',
            'access'
        ],
        showActionsColumn: false,
        paging: false,
        remoteSort: false,
        autoHeight: true,
        plugins: accessCheckboxCol,
        columns: [
            {
                header: _('name'),
                dataIndex: 'name',
                width: 200,
                sortable: true,
                renderer: {
                    fn: function(value, metaData, record) {
                        const canEditResourceGroups = MODx.perm.resourcegroup_edit || MODx.perm.resourcegroup_resource_edit;
                        return canEditResourceGroups
                            ? this.renderLink(value, {
                                href: `?a=security/resourcegroup&id=${record.data.id}`,
                                target: '_blank'
                            })
                            : value
                        ;
                    },
                    scope: this
                }
            },
            accessCheckboxCol
        ]
    });
    MODx.grid.ResourceSecurity.superclass.constructor.call(this, config);
    this.propRecord = Ext.data.Record.create(config.fields);
    this.on('rowclick', MODx.fireResourceFormChange);
    this.store.sortInfo = {
        field: 'access',
        direction: 'DESC'
    };
};
Ext.extend(MODx.grid.ResourceSecurity, MODx.grid.LocalGrid);
Ext.reg('modx-grid-resource-security', MODx.grid.ResourceSecurity);
