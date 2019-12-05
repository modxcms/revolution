/**
 * @class MODx.grid.ResourceSecurity
 * @extends MODx.grid.LocalGrid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-resource-security
 */
MODx.grid.ResourceSecurity = function(config) {
    config = config || {};
    var ac = new Ext.ux.grid.CheckColumn({
        header: _('access')
        ,dataIndex: 'access'
        ,width: 40
        ,sortable: true
        ,hidden: MODx.perm.resourcegroup_resource_edit != 1
    });
    Ext.applyIf(config,{
        id: 'modx-grid-resource-security'
        ,fields: ['id','name','access']
        ,showActionsColumn: false
        ,paging: false
        ,remoteSort: false
        ,autoHeight: true
        ,plugins: ac
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=security/resourcegroup'
                    ,target: '_blank'
                });
            }, scope: this }
        },ac]
    });
    MODx.grid.ResourceSecurity.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create(config.fields);
    this.on('rowclick',MODx.fireResourceFormChange);
    this.store.sortInfo = {
        field: 'access',
        direction: 'DESC'
    };
};
Ext.extend(MODx.grid.ResourceSecurity,MODx.grid.LocalGrid);
Ext.reg('modx-grid-resource-security',MODx.grid.ResourceSecurity);
