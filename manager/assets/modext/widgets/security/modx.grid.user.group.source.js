
MODx.grid.UserGroupSource = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="desc">{permissions}</p>'
        )
    });
    Ext.applyIf(config,{
        id: 'modx-grid-user-group-sources'
        ,url: MODx.config.connectors_url+'security/access/usergroup/source.php'
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
            header: _('source')
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
        }]
        ,tbar: [{
            text: _('source_add')
            ,scope: this
            ,handler: this.createAcl
        },'->',{
            xtype: 'modx-combo-source'
            ,id: 'modx-ugsource-source-filter'
            ,emptyText: _('filter_by_source')
            ,width: 200
            ,allowBlank: true
            ,listeners: {
                'select': {fn:this.filterSource,scope:this}
            }
        },{
            xtype: 'modx-combo-policy'
            ,id: 'modx-ugsource-policy-filter'
            ,emptyText: _('filter_by_policy')
            ,allowBlank: true
            ,baseParams: {
                action: 'getList'
                ,group: 'MediaSource'
            }
            ,listeners: {
                'select': {fn:this.filterPolicy,scope:this}
            }
        },{
            text: _('clear_filter')
            ,id: 'modx-ugsource-clear-filter'
            ,handler: this.clearFilter
            ,scope: this
        }]
    });
    MODx.grid.UserGroupSource.superclass.constructor.call(this,config);
    this.addEvents('createAcl','updateAcl');
};
Ext.extend(MODx.grid.UserGroupSource,MODx.grid.Grid,{
    combos: {}
    ,windows: {}

    ,filterSource: function(cb,rec,ri) {
        this.getStore().baseParams['source'] = rec.data['id'];
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,filterPolicy: function(cb,rec,ri) {
        this.getStore().baseParams['policy'] = rec.data['id'];
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }

    ,clearFilter: function(btn,e) {
        Ext.getCmp('modx-ugsource-source-filter').setValue('');
        this.getStore().baseParams['source'] = '';
        Ext.getCmp('modx-ugsource-policy-filter').setValue('');
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
                xtype: 'modx-window-user-group-source-create'
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
                xtype: 'modx-window-user-group-source-update'
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
Ext.reg('modx-grid-user-group-source',MODx.grid.UserGroupSource);


MODx.window.CreateUGSource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('source_add')
        ,url: MODx.config.connectors_url+'security/access/usergroup/source.php'
        ,action: 'create'
        ,height: 250
        ,width: 350
        ,fields: [{
            xtype: 'modx-combo-source'
            ,fieldLabel: _('source')
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
                ,group: 'MediaSource'
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
            xtype: 'hidden'
            ,name: 'context_key'
            ,hiddenName: 'context_key'
            ,value: 'mgr'
        }]
    });
    MODx.window.CreateUGSource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUGSource,MODx.Window);
Ext.reg('modx-window-user-group-source-create',MODx.window.CreateUGSource);


MODx.window.UpdateUGSource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('access_source_update')
        ,url: MODx.config.connectors_url+'security/access/usergroup/source.php'
        ,action: 'update'
        ,height: 250
        ,width: 350
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'modx-combo-source'
            ,fieldLabel: _('source')
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
                ,group: 'MediaSource'
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
            xtype: 'hidden'
            ,name: 'context_key'
            ,hiddenName: 'context_key'
            ,value: 'mgr'
        }]
    });
    MODx.window.UpdateUGSource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateUGSource,MODx.Window);
Ext.reg('modx-window-user-group-source-update',MODx.window.UpdateUGSource);