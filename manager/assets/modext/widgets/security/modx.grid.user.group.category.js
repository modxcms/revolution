
MODx.grid.UserGroupCategory = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-grid-user-group-categories'
        ,url: MODx.config.connectors_url+'security/access/usergroup/category.php'
        ,baseParams: {
            action: 'getList'
            ,usergroup: config.usergroup
        }
        ,paging: true
        ,hideMode: 'offsets'
        ,fields: ['id','target','name','principal','authority','authority_name','policy','policy_name','context_key','menu']
        ,columns: [{
            header: _('category')
            ,dataIndex: 'name'
            ,width: 120
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
        }]
        ,tbar: [{
            text: _('category_add')
            ,scope: this
            ,handler: this.createAcl
        },'->',{
            xtype: 'modx-combo-category'
            ,id: 'modx-ugcat-category-filter'
            ,emptyText: _('filter_by_category')
            ,width: 200
            ,allowBlank: true
            ,listeners: {
                'select': {fn:this.filterCategory,scope:this}
            }
        },{
            xtype: 'modx-combo-policy'
            ,id: 'modx-ugcat-policy-filter'
            ,emptyText: _('filter_by_policy')
            ,allowBlank: true
            ,listeners: {
                'select': {fn:this.filterPolicy,scope:this}
            }
        },{
            text: _('clear_filter')
            ,id: 'modx-ugcat-clear-filter'
            ,handler: this.clearFilter
            ,scope: this
        }]
    });
    MODx.grid.UserGroupCategory.superclass.constructor.call(this,config);
    this.addEvents('createAcl','updateAcl');
};
Ext.extend(MODx.grid.UserGroupCategory,MODx.grid.Grid,{
    combos: {}
    ,windows: {}
    
    ,filterCategory: function(cb,rec,ri) {
        this.getStore().baseParams['category'] = rec.data['id'];
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,filterPolicy: function(cb,rec,ri) {
        this.getStore().baseParams['policy'] = rec.data['id'];
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    
    ,clearFilter: function(btn,e) {
        Ext.getCmp('modx-ugcat-category-filter').setValue('');
        this.getStore().baseParams['category'] = '';
        Ext.getCmp('modx-ugcat-policy-filter').setValue('');
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
                xtype: 'modx-window-user-group-category-create'
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
                xtype: 'modx-window-user-group-category-update'
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
Ext.reg('modx-grid-user-group-category',MODx.grid.UserGroupCategory);


MODx.window.CreateUGCat = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('category_add')
        ,url: MODx.config.connectors_url+'security/access/usergroup/category.php'
        ,action: 'create'
        ,height: 250
        ,width: 350
        ,fields: [{
            xtype: 'modx-combo-category'
            ,fieldLabel: _('category')
            ,name: 'target'
            ,hiddenName: 'target'
            ,editable: false
        },{
            xtype: 'modx-combo-authority'
            ,fieldLabel: _('minimum_role')
            ,name: 'authority'
            ,value: 0
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,baseParams: {
                action: 'getList'
                ,combo: '1'
            }
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
        }]
    });
    MODx.window.CreateUGCat.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUGCat,MODx.Window);
Ext.reg('modx-window-user-group-category-create',MODx.window.CreateUGCat);


MODx.window.UpdateUGCat = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('access_category_update')
        ,url: MODx.config.connectors_url+'security/access/usergroup/category.php'
        ,action: 'update'
        ,height: 250
        ,width: 350
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'modx-combo-category'
            ,fieldLabel: _('category')
            ,name: 'target'
            ,hiddenName: 'target'
            ,editable: false
        },{
            xtype: 'modx-combo-authority'
            ,fieldLabel: _('minimum_role')
            ,name: 'authority'
            ,value: 0
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,baseParams: {
                action: 'getList'
                ,combo: '1'
            }
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
        }]
    });
    MODx.window.UpdateUGCat.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateUGCat,MODx.Window);
Ext.reg('modx-window-user-group-category-update',MODx.window.UpdateUGCat);