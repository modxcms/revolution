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
                xtype: 'modx-window-access-resource-group'
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
                xtype: 'modx-window-access-resource-group'
                ,id: r.id
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
MODx.window.AccessResourceGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('ugrg_mutate')
        ,autoHeight: true
        ,width: 350
        ,type: 'modAccessResourceGroup'
        ,id: 0
    });
    MODx.window.AccessResourceGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AccessResourceGroup,MODx.Window,{
    combos: {}
    ,_loadForm: function() {
        if (this.checkIfLoaded(this.config.record)) { return false; }
        if (this.config.id) {
            MODx.Ajax.request({
                url: MODx.config.connectors_url+'security/access/index.php'
                ,params: {
                    action: 'getAcl'
                    ,id: this.config.id
                    ,type: this.config.type
                }
                ,listeners: {
                	'success': {fn:this.prepareForm,scope:this}
                }
            });
        } else {
            this.prepareForm(null,null);
        }
    }
    
    ,prepareForm: function(r) {
        var data = {};
        if (r) {
            r = Ext.decode(r.responseText);
            if (r.success) {
                data = r.object;
                this.config.baseParams = {
                    action: 'updateAcl'
                    ,type: this.config.type
                };
            }
        }
        this.options.values = data;
                
        this.fp = this.createForm({
            url: this.config.url || MODx.config.connectors_url+'security/access/index.php'
            ,baseParams: this.config.baseParams || { action: 'addAcl', type: this.config.type }
            ,items: [
                {
                    fieldLabel: _('resource_group')
                    ,name: 'target'
                    ,hiddenName: 'target'
                    ,id: 'modx-arg-target'
                    ,xtype: 'modx-combo-resourcegroup'
                    ,value: data.target
                },{
                    fieldLabel: _('user_group')
                    ,name: 'principal'
                    ,hiddenName: 'principal'
                    ,id: 'modx-arg-principal'
                    ,xtype: 'modx-combo-usergroup'
                    ,value: data.principal || ''
                    ,baseParams: {
                        action: 'getList'
                        ,combo: '1'
                    }
                },{
                    fieldLabel: _('authority')
                    ,name: 'authority'
                    ,id: 'modx-arg-authority'
                    ,xtype: 'textfield'
                    ,width: 40
                    ,value: data.authority
                },{
                    fieldLabel: _('policy')
                    ,name: 'policy'
                    ,hiddenName: 'policy'
                    ,id: 'modx-arg-policy'
                    ,xtype: 'modx-combo-policy'
                    ,value: data.policy || ''
                    ,baseParams: {
                        action: 'getList'
                        ,combo: '1'
                    }
                },{
                    fieldLabel: _('context')
                    ,name: 'context_key'
                    ,hiddenName: 'context_key'
                    ,id: 'modx-arg-context-key'
                    ,xtype: 'modx-combo-context'
                    ,value: data.context_key || ''
                },{
                    name: 'principal_class'
                    ,id: 'modx-arg-principal-class'
                    ,xtype: 'hidden'
                    ,value: 'modUserGroup'
                },{
                    name: 'id'
                    ,id: 'modx-arg-id'
                    ,xtype: 'hidden'
                    ,value: data.id
                }
            ]
        });
        
        this.renderForm();
    }
});
Ext.reg('modx-window-access-resource-group',MODx.window.AccessResourceGroup);