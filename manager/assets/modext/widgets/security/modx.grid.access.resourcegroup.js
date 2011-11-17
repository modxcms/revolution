/**
 * Loads a grid of modAccessResourceGroups.
 * 
 * @class MODx.grid.AccessResourceGroup
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-access-resource-group
 */
MODx.grid.AccessResourceGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('ugrg_grid_title')
        ,url: MODx.config.connectors_url+'security/access/index.php'
        ,baseParams: {
            action: 'getList'
            ,type: config.type || 'modAccessResourceGroup'
        }
        ,fields: ['id','target','target_name','principal_class','principal','principal_name','authority','policy','policy_name','context_key','menu']
        ,type: 'modAccessResourceGroup'
        ,paging: true
        ,columns: [
            { header: _('id') ,dataIndex: 'id' ,width: 40 }
            ,{ header: _('resource_group_id') ,dataIndex: 'target' ,width: 40 }
            ,{ header: _('resource_group') ,dataIndex: 'target_name' ,width: 150 }
            ,{ header: _('user_group_id') ,dataIndex: 'principal' ,width: 40 }
            ,{ header: _('user_group') ,dataIndex: 'principal_name' ,width: 150 }
            ,{ header: _('authority') ,dataIndex: 'authority' ,width: 75 }
            ,{ header: _('policy') ,dataIndex: 'policy_name' ,width: 175 }
            ,{ header: _('context') ,dataIndex: 'context_key' ,width: 60 }
        ]
        ,tbar: this.getToolbar()
    });
    MODx.grid.AccessResourceGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.AccessResourceGroup,MODx.grid.Grid,{
    windows: {}
    ,combos: {}
    
    ,createArg: function(btn,e) {
        var r = this.menu.record;
        if (!this.windows.create_arg) {
            this.windows.create_arg = MODx.load({
                xtype: 'modx-window-access-resource-group-create'
                ,record: r
                ,listeners: {
                    'success': {fn: function(frm,a) {
                        this.getStore().baseParams = {
                            action: 'getList'
                            ,type: this.config.type
                            ,target: this.combos.rg.getValue()
                            ,principal: this.combos.rg.getValue()
                            ,principal_class: 'modUserGroup'
                        };
                        this.refresh();
                    },scope:this}
                }
            });
        } else {
            this.windows.create_arg.setValues(r);
        }
        this.windows.create_arg.show(e.target);
    }
    
    ,editAcl: function(itm,e) {
        var r = this.menu.record;
        if (!this.windows.update_arg) {
            this.windows.update_arg = MODx.load({
                xtype: 'modx-window-access-resource-group-update'
                ,acl: r.id
                ,record: r
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.windows.update_arg.setValues(r);
        }
        this.windows.update_arg.show(e.target);
    }
    
    ,removeAcl: function(itm,e) {
        MODx.msg.confirm({
            title: _('ugrg_remove')
            ,text: _('access_confirm_remove')
            ,url: this.config.url
            ,params: {
                action: 'removeAcl'
                ,id: this.menu.record.id
                ,type: this.config.type
            }
            ,listeners: {
            	'success': {fn:this.refresh,scope:this}
            }
        });
    }
    
    ,clearFilter: function(btn,e) {
        this.getStore().baseParams = { 
            action: 'getList'
            ,type: this.config.type
            ,target: ''
            ,principal: ''
            ,principal_class: 'modUserGroup'
        };
        this.combos.ug.setValue('');
        this.combos.rg.setValue('');
        this.getStore().load();
    }
    
    ,getToolbar: function() {
        this.combos.ug = MODx.load({ xtype: 'modx-combo-usergroup' });
        this.combos.ug.on('select',function(btn,e) {
            this.getStore().baseParams = {
                action: 'getList'
                ,type: this.config.type
                ,target: this.combos.rg.getValue()
                ,principal: this.combos.ug.getValue()
            };
            this.getStore().reload();
        },this);
        
        this.combos.rg = MODx.load({ xtype: 'modx-combo-resourcegroup' });
        this.combos.rg.on('select',function(btn,e) {
            this.getStore().baseParams = {
                action: 'getList'
                ,type: this.config.type
                ,target: this.combos.rg.getValue()
                ,principal: this.combos.ug.getValue()
            };
            this.getStore().reload();
        },this);
        
        return [
            _('resource_group')+': '
            ,this.combos.rg
            ,'-'
            ,_('user_group') + ': '
            ,this.combos.ug
            ,'-'
            ,{
                text: _('clear_filter')
                ,scope: this
                ,handler: this.clearFilter
            }
            ,'->'
            ,{
                text: _('add')
                ,scope: this
                ,handler: this.createArg
            }
        ];
    }
});
Ext.reg('modx-grid-access-resource-group',MODx.grid.AccessResourceGroup);

/** 
 * Generates the modAccessResourceGroup window.
 *  
 * @class MODx.window.AccessResourceGroup
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-access-resource-group
 */
MODx.window.CreateAccessResourceGroup = function(config) {
    config = config || {};
    this.ident = config.ident || 'caccrg'+Ext.id();
    Ext.applyIf(config,{
        title: _('ugrg_mutate')
        ,url: MODx.config.connectors_url+'security/access/index.php'
        ,baseParams: { action: 'addAcl', type: 'modAccessResourceGroup' }
        ,autoHeight: true
        ,width: 350
        ,type: 'modAccessResourceGroup'
        ,acl: 0
        ,fields: [{
            xtype: 'modx-combo-resourcegroup'
            ,fieldLabel: _('resource_group')
            ,name: 'target'
            ,hiddenName: 'target'
            ,id: 'modx-'+this.ident+'-target'
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-usergroup'
            ,fieldLabel: _('user_group')
            ,name: 'principal'
            ,hiddenName: 'principal'
            ,id: 'modx-'+this.ident+'-principal'
            ,baseParams: {
                action: 'getList'
                ,combo: '1'
            }
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('authority')
            ,name: 'authority'
            ,id: 'modx-'+this.ident+'-authority'
            ,width: 40
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,id: 'modx-'+this.ident+'-policy'
            ,baseParams: {
                action: 'getList'
                ,combo: '1'
            }
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-context'
            ,fieldLabel: _('context')
            ,name: 'context_key'
            ,hiddenName: 'context_key'
            ,id: 'modx-'+this.ident+'-context-key'
            ,anchor: '100%'
        },{
            xtype: 'hidden'
            ,name: 'principal_class'
            ,id: 'modx-'+this.ident+'-principal-class'
            ,value: 'modUserGroup'
        }]
    });
    MODx.window.CreateAccessResourceGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateAccessResourceGroup,MODx.Window);
Ext.reg('modx-window-access-resource-group-create',MODx.window.CreateAccessResourceGroup);


MODx.window.UpdateAccessResourceGroup = function(config) {
    config = config || {};
    var r = config.record;
    this.ident = config.ident || 'uaccrg'+Ext.id();
    Ext.applyIf(config,{
        title: _('ugrg_mutate')
        ,url: MODx.config.connectors_url+'security/access/index.php'
        ,baseParams: { action: 'updateAcl', type: 'modAccessResourceGroup' }
        ,autoHeight: true
        ,width: 350
        ,type: 'modAccessResourceGroup'
        ,acl: 0
        ,fields: [{
            xtype: 'modx-combo-resourcegroup'
            ,fieldLabel: _('resource_group')
            ,name: 'target'
            ,hiddenName: 'target'
            ,id: 'modx-'+this.ident+'-target'
            ,value: r.target
        },{
            xtype: 'modx-combo-usergroup'
            ,fieldLabel: _('user_group')
            ,name: 'principal'
            ,hiddenName: 'principal'
            ,id: 'modx-'+this.ident+'-principal'
            ,value: r.principal || ''
            ,baseParams: {
                action: 'getList'
                ,combo: '1'
            }
        },{
            xtype: 'textfield'
            ,fieldLabel: _('authority')
            ,name: 'authority'
            ,id: 'modx-'+this.ident+'-authority'
            ,width: 40
            ,value: r.authority
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,id: 'modx-'+this.ident+'-policy'
            ,value: r.policy || ''
            ,baseParams: {
                action: 'getList'
                ,combo: '1'
            }
        },{
            xtype: 'modx-combo-context'
            ,fieldLabel: _('context')
            ,name: 'context_key'
            ,hiddenName: 'context_key'
            ,id: 'modx-'+this.ident+'-context-key'
            ,value: r.context_key || ''
        },{
            xtype: 'hidden'
            ,name: 'principal_class'
            ,id: 'modx-'+this.ident+'-principal-class'
            ,value: 'modUserGroup'
        },{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
            ,value: r.id
        }]
    });
    MODx.window.UpdateAccessResourceGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateAccessResourceGroup,MODx.Window);
Ext.reg('modx-window-access-resource-group-update',MODx.window.UpdateAccessResourceGroup);
