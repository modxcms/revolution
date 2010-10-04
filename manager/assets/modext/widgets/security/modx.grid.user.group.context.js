
MODx.grid.UserGroupContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-grid-user-group-contexts'
        ,url: MODx.config.connectors_url+'security/access/usergroup/context.php'
        ,baseParams: {
            action: 'getList'
            ,usergroup: config.usergroup
        }
        ,fields: ['id','target','principal','authority','authority_name','policy','policy_name','menu']
        ,paging: true
        ,hideMode: 'offsets'
        ,columns: [{
            header: _('context')
            ,dataIndex: 'target'
            ,width: 120
        },{
            header: _('minimum_role')
            ,dataIndex: 'authority_name'
            ,width: 100
        },{
            header: _('policy')
            ,dataIndex: 'policy_name'
            ,width: 200
        }]
        ,tbar: [{
            text: _('context_add')
            ,scope: this
            ,handler: this.createAcl
        },'->',{
            xtype: 'modx-combo-context'
            ,id: 'modx-ugc-context-filter'
            ,emptyText: _('filter_by_context')
            ,allowBlank: true
            ,listeners: {
                'select': {fn:this.filterContext,scope:this}
            }
        },{
            xtype: 'modx-combo-policy'
            ,id: 'modx-ugc-policy-filter'
            ,emptyText: _('filter_by_policy')
            ,allowBlank: true
            ,listeners: {
                'select': {fn:this.filterPolicy,scope:this}
            }
        },{
            text: _('clear_filter')
            ,id: 'modx-ugc-clear-filter'
            ,handler: this.clearFilter
            ,scope: this
        }]
    });
    MODx.grid.UserGroupContext.superclass.constructor.call(this,config);
    this.addEvents('createAcl','updateAcl');
};
Ext.extend(MODx.grid.UserGroupContext,MODx.grid.Grid,{
    combos: {}
    ,windows: {}
    
    ,filterContext: function(cb,rec,ri) {
        this.getStore().baseParams['context'] = rec.data['key'];
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,filterPolicy: function(cb,rec,ri) {
        this.getStore().baseParams['policy'] = rec.data['id'];
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    
    ,clearFilter: function(btn,e) {
        Ext.getCmp('modx-ugc-context-filter').setValue('');
        this.getStore().baseParams['context'] = '';
        Ext.getCmp('modx-ugc-policy-filter').setValue('');
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
                xtype: 'modx-window-user-group-context-create'
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
                xtype: 'modx-window-user-group-context-update'
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
Ext.reg('modx-grid-user-group-context',MODx.grid.UserGroupContext);


MODx.window.CreateUGAccessContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('ugc_mutate')
        ,url: MODx.config.connectors_url+'security/access/usergroup/context.php'
        ,action: 'create'
        ,height: 250
        ,width: 350
        ,fields: [{
            xtype: 'modx-combo-context'
            ,fieldLabel: _('context')
            ,name: 'target'
            ,hiddenName: 'target'
            ,editable: false
            ,allowBlank: false
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
                ,combo: '1'
            }
            ,allowBlank: false
            ,anchor: '90%'
        },{
            xtype: 'hidden'
            ,name: 'principal'
            ,hiddenName: 'principal'
        }]
    });
    MODx.window.CreateUGAccessContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUGAccessContext,MODx.Window);
Ext.reg('modx-window-user-group-context-create',MODx.window.CreateUGAccessContext);


MODx.window.UpdateUGAccessContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('ugc_mutate')
        ,url: MODx.config.connectors_url+'security/access/usergroup/context.php'
        ,action: 'update'
        ,height: 250
        ,width: 350
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'modx-combo-context'
            ,fieldLabel: _('context')
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
        }]
    });
    MODx.window.UpdateUGAccessContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateUGAccessContext,MODx.Window);
Ext.reg('modx-window-user-group-context-update',MODx.window.UpdateUGAccessContext);

