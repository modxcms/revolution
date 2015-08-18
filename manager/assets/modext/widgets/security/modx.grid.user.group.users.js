/**
 * Loads a grid of users in a group
 * 
 * @class MODx.grid.UsergroupUsers
 * @extends MODx.grid.User
 * @param {Object} config An object of options.
 * @xtype modx-grid-usergroup-users
 */
MODx.grid.UsergroupUsers = function(config) {
    config = config || {};

    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/user/getList'
            ,usergroup: config['usergroup'] ? config['usergroup'] : ''
        }
        ,tbar: [{
            text: _('user_new')
            ,handler: this.createUser
            ,scope: this
            ,cls:'primary-button'
        },{
            text: _('bulk_actions')
            ,menu: [{
                text: _('selected_activate')
                ,handler: this.activateSelected
                ,scope: this
            },{
                text: _('selected_deactivate')
                ,handler: this.deactivateSelected
                ,scope: this
            },{
                text: _('selected_remove')
                ,handler: this.removeSelected
                ,scope: this
            }]
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-user-search-' + config['usergroup']
            ,cls: 'x-form-filter'
            ,emptyText: _('search_ellipsis')
            ,listeners: {
                'change': {fn: this.search, scope: this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: this.blur
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,cls: 'x-form-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });
    MODx.grid.UsergroupUsers.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.UsergroupUsers,MODx.grid.User,{
    clearFilter: function() {
        this.getStore().baseParams = {
            action: 'security/user/getList'
            ,usergroup: this.config['usergroup'] ? this.config['usergroup'] : ''
        };
        Ext.getCmp('modx-user-search-' + this.config['usergroup']).reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
});
Ext.reg('modx-grid-usergroup-users',MODx.grid.UsergroupUsers);
