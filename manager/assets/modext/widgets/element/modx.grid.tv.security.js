/**
 * Loads a grid of resource groups assigned to a resource.
 *
 * @class MODx.grid.TVSecurity
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-tv-security
 */
MODx.grid.TVSecurity = function(config = {}) {
    const accessCheckboxCol = new Ext.ux.grid.CheckColumn({
        header: _('access'),
        dataIndex: 'access',
        width: 40,
        sortable: false
    });
    Ext.applyIf(config, {
        id: 'modx-grid-tv-security',
        url: MODx.config.connector_url,
        showActionsColumn: false,
        fields: [
            'id',
            'name',
            'access',
            'menu'
        ],
        baseParams: {
            action: 'Element/TemplateVar/ResourceGroup/GetList',
            tv: config.tv
        },
        saveParams: {
            tv: config.tv
        },
        width: 800,
        paging: true,
        remoteSort: true,
        plugins: accessCheckboxCol,
        columns: [
            {
                header: _('name'),
                dataIndex: 'name',
                width: 200,
                sortable: true,
                renderer: {
                    fn: function(value, metaData, record) {
                        return this.userCanEditResourceGroups
                            ? this.renderLink(value, {
                                href: '?a=security/resourcegroup',
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
    MODx.grid.TVSecurity.superclass.constructor.call(this, config);

    this.setUserCanEdit(['edit_tv', 'save_tv']);
    this.setUserHasPermissions('editResourceGroups', ['resourcegroup_view', 'resourcegroup_resource_list']);
};
Ext.extend(MODx.grid.TVSecurity, MODx.grid.Grid);
Ext.reg('modx-grid-tv-security', MODx.grid.TVSecurity);
