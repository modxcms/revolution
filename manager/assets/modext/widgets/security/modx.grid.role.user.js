/**
 * Loads a grid of Role and User pairs.
 * 
 * @deprecated
 * 
 * @class MODx.grid.AccessResourceGroup
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-roleuser
 */
MODx.grid.RoleUser = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('role_users')
        ,url: MODx.config.connectors_url+'security/role.php'
        ,fields: ['id','username','fullname','email']
        ,baseParams: {
                action: 'getUsers'
                ,role: config.role
        }
        ,autosave: true
        ,paging: true
        ,columns: [
            { header: _('id') ,dataIndex: 'id' ,width: 40 }
            ,{ header: _('username') ,dataIndex: 'username' ,width: 175 }
            ,{ header: _('name') ,dataIndex: 'fullname' ,width: 175 }
            ,{ header: _('email') ,dataIndex: 'email' ,width: 200 }
        ]
        ,tbar: this.getToolbar()
    });
    MODx.grid.RoleUser.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.RoleUser,MODx.grid.Grid,{
    combos: {}
    /**
     * Runs a confirm dialog, and if proceeding, removes the modUser.   
     */
    ,removeUser: function() {
        MODx.msg.confirm({
            title: _('role_user_remove')
            ,text: _('role_user_confirm_remove')
            ,url: this.config.url
            ,params: {
                action: 'removeUser'
                ,user: this.menu.record.id
                ,role: this.config.role
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
    /**
     * Adds a user to the role based upon the combobox value.
     */
    ,addUser: function(btn,e) {
        var user = Ext.getCmp('rugrid-combo-user').getValue();
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'addUser'
                ,role: this.config.role
                ,user: user
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getStore().baseParams = {
                        action: 'getUsers'
                        ,role: this.config.role
                    };
                    Ext.getCmp('rugrid-combo-usergroup').setValue('');
                    this.refresh();
                },scope:this}
            }
        });
    }
    /**
     * Loads the context menu for the user-role pairing.
     */
    ,_loadMenu: function() {
        this.menu = new Ext.menu.Menu({ defaultAlign: 'tl-b?' });
        this.menu.add({
            text: _('role_user_remove')
            ,handler: this.removeUser
            ,scope: this
        });
    }
    /**
     * Returns the custom toolbar for the grid.
     */
	,getToolbar: function() {		
		return [
			_('role_user_add')+': '
			,{
                xtype: 'modx-combo-user'
                ,id: 'rugrid-combo-user'
            },{
				xtype: 'button'
				,text: 'Add'
				,scope: this
				,handler: this.addUser
			}
			,'->'
			,_('group')+': '
			,{
                xtype: 'modx-combo-usergroup'
                ,id: 'rugrid-combo-usergroup'
                ,listeners: {
                    'select': {fn:function(btn,e) {
                        this.store.baseParams = {
                            action: 'getUsers'
                            ,role: this.config.role
                            ,group: Ext.getCmp('rugrid-combo-usergroup').getValue()
                        };
                        this.refresh();
                    },scope:this}
                }
            }
			,'-'
			,{
				xtype: 'button'
				,text: _('clear_filter')
				,scope: this
				,handler: function(btn,e) {
					this.getStore().baseParams = { 
						action: 'getUsers'
						,role: this.config.role
					};
                    Ext.getCmp('rugrid-combo-usergroup').setValue('');
					this.getStore().load();
				}
			}		
		];
	}
});
Ext.reg('modx-grid-roleuser',MODx.grid.RoleUser);