
MODx.grid.UserGroupResourceGroup = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="desc">{permissions}</p>'
        )
    });
    Ext.applyIf(config,{
        id: 'modx-grid-user-group-resource-groups'
        ,url: MODx.config.connectors_url+'security/access/usergroup/resourcegroup.php'
        ,baseParams: {
            action: 'getList'
            ,usergroup: config.usergroup
        }
        ,paging: true
        ,hideMode: 'offsets'
        ,fields: ['id','target','name','principal','authority','authority_name','policy','policy_name','context_key','permissions','menu']
        ,grouping: true
        ,groupBy: 'authority_name'
        ,singleText: _('policy')
        ,pluralText: _('policies')
        ,sortBy: 'authority'
        ,sortDir: 'DESC'
        ,plugins: [this.exp]
        ,columns: [this.exp,{
            header: _('resource_group')
            ,dataIndex: 'name'
            ,width: 120
            ,sortable: true
        },{
            header: _('minimum_role')
            ,dataIndex: 'authority_name'
            ,width: 100
        },{
            header: _('policy')
            ,dataIndex: 'policy_name'
            ,width: 200
        },{
            header: _('context')
            ,dataIndex: 'context_key'
            ,width: 150
            ,sortable: true
        }]
        ,tbar: [{
            text: _('resource_group_add')
            ,scope: this
            ,handler: this.createAcl
        },'->',{
            xtype: 'modx-combo-resourcegroup'
            ,id: 'modx-ugrg-resourcegroup-filter'
            ,emptyText: _('filter_by_resource_group')
            ,width: 200
            ,allowBlank: true
            ,listeners: {
                'select': {fn:this.filterResourceGroup,scope:this}
            }
        },{
            xtype: 'modx-combo-policy'
            ,id: 'modx-ugrg-policy-filter'
            ,emptyText: _('filter_by_policy')
            ,baseParams: {
                action: 'getList'
                ,group: 'Object'
            }
            ,allowBlank: true
            ,listeners: {
                'select': {fn:this.filterPolicy,scope:this}
            }
        },{
            text: _('clear_filter')
            ,id: 'modx-ugrg-clear-filter'
            ,handler: this.clearFilter
            ,scope: this
        }]
    });
    MODx.grid.UserGroupResourceGroup.superclass.constructor.call(this,config);
    this.addEvents('createAcl','updateAcl');
};
Ext.extend(MODx.grid.UserGroupResourceGroup,MODx.grid.Grid,{
    combos: {}
    ,windows: {}
    
    ,filterResourceGroup: function(cb,rec,ri) {
        this.getStore().baseParams['resourceGroup'] = rec.data['id'];
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,filterPolicy: function(cb,rec,ri) {
        this.getStore().baseParams['policy'] = rec.data['id'];
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    
    ,clearFilter: function(btn,e) {
        Ext.getCmp('modx-ugrg-resourcegroup-filter').setValue('');
        this.getStore().baseParams['resourceGroup'] = '';
        Ext.getCmp('modx-ugrg-policy-filter').setValue('');
        this.getStore().baseParams['policy'] = '';
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,createAcl: function(itm,e) {
        var r = {
            principal: this.config.usergroup
        };
        if (!this.windows.createAcl) {
            this.windows.createAcl = MODx.load({
                xtype: 'modx-window-user-group-resourcegroup-create'
                ,record: r
                ,listeners: {
                    'success': {fn:function(r) {
                        this.refresh();
                        this.fireEvent('createAcl',r);
                    },scope:this}
                }
            });
        }
        this.windows.createAcl.setValues(r);
        this.windows.createAcl.show(e.target);
    }
    ,updateAcl: function(itm,e) {
        var r = this.menu.record;
        
        if (!this.windows.updateAcl) {
            this.windows.updateAcl = MODx.load({
                xtype: 'modx-window-user-group-resourcegroup-update'
                ,record: r
                ,listeners: {
                    'success': {fn:function(r) {
                        this.refresh();
                        this.fireEvent('updateAcl',r);
                    },scope:this}
                }
            });
        }
        this.windows.updateAcl.setValues(r);
        this.windows.updateAcl.show(e.target);
    }
});
Ext.reg('modx-grid-user-group-resource-group',MODx.grid.UserGroupResourceGroup);


MODx.window.CreateUGRG = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('resource_group_add')
        ,url: MODx.config.connectors_url+'security/access/usergroup/resourcegroup.php'
        ,action: 'create'
        ,height: 250
        ,width: 350
        ,fields: [{
            xtype: 'modx-combo-resourcegroup'
            ,fieldLabel: _('resource_group')
            ,name: 'target'
            ,hiddenName: 'target'
            ,editable: false
            ,anchor: '90%'
        },{
            xtype: 'modx-combo-authority'
            ,fieldLabel: _('minimum_role')
            ,name: 'authority'
            ,value: 0
            ,anchor: '90%'
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,baseParams: {
                action: 'getList'
                ,group: 'Resource,Object'
                ,combo: '1'
            }
            ,anchor: '90%'
        },{
            xtype: 'hidden'
            ,name: 'principal'
            ,hiddenName: 'principal'
        },{
            xtype: 'hidden'
            ,name: 'principal_class'
            ,value: 'modUserGroup'
        },{
            xtype: 'modx-combo-context'
            ,fieldLabel: _('context')
            ,name: 'context_key'
            ,hiddenName: 'context_key'
            ,editable: false
            ,anchor: '90%'
        }]
    });
    MODx.window.CreateUGRG.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUGRG,MODx.Window);
Ext.reg('modx-window-user-group-resourcegroup-create',MODx.window.CreateUGRG);


MODx.window.UpdateUGRG = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('access_rgroup_update')
        ,url: MODx.config.connectors_url+'security/access/usergroup/resourcegroup.php'
        ,action: 'update'
        ,height: 250
        ,width: 350
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'modx-combo-resourcegroup'
            ,fieldLabel: _('resource_group')
            ,name: 'target'
            ,hiddenName: 'target'
            ,editable: false
            ,anchor: '90%'
        },{
            xtype: 'modx-combo-authority'
            ,fieldLabel: _('minimum_role')
            ,name: 'authority'
            ,value: 0
            ,anchor: '90%'
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,baseParams: {
                action: 'getList'
                ,group: 'Resource,Object'
                ,combo: '1'
            }
            ,anchor: '90%'
        },{
            xtype: 'hidden'
            ,name: 'principal'
            ,hiddenName: 'principal'
        },{
            xtype: 'hidden'
            ,name: 'principal_class'
            ,value: 'modUserGroup'
        },{
            xtype: 'modx-combo-context'
            ,fieldLabel: _('context')
            ,name: 'context_key'
            ,hiddenName: 'context_key'
            ,editable: false
            ,anchor: '90%'
        }]
    });
    MODx.window.UpdateUGRG.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateUGRG,MODx.Window);
Ext.reg('modx-window-user-group-resourcegroup-update',MODx.window.UpdateUGRG);