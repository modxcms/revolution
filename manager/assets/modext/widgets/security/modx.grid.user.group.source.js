
MODx.grid.UserGroupSource = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl: new Ext.Template('<p class="desc">{permissions}</p>'),
        lazyRender: false,
        enableCaching: false
    });
    Ext.applyIf(config,{
        id: 'modx-grid-user-group-sources'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/access/usergroup/source/getList'
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
        ,sortDir: 'ASC'
        ,remoteSort: true
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
            ,cls:'primary-button'
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
                action: 'security/access/policy/getList'
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
        //this.refresh();
    }
    ,filterPolicy: function(cb,rec,ri) {
        this.getStore().baseParams['policy'] = rec.data['id'];
        this.getBottomToolbar().changePage(1);
        //this.refresh();
    }

    ,clearFilter: function(btn,e) {
        Ext.getCmp('modx-ugsource-source-filter').setValue('');
        this.getStore().baseParams['source'] = '';
        Ext.getCmp('modx-ugsource-policy-filter').setValue('');
        this.getStore().baseParams['policy'] = '';
        this.getBottomToolbar().changePage(1);
        //this.refresh();
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
    this.ident = config.ident || 'cugsrc'+Ext.id();
    Ext.applyIf(config,{
        title: _('source_add')
        ,url: MODx.config.connector_url
        ,action: 'security/access/usergroup/source/create'
        // ,height: 250
        // ,width: 500
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
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
        },{
            xtype: 'modx-combo-source'
            ,fieldLabel: _('source')
            ,description: MODx.expandHelp ? '' : _('user_group_source_source_desc')
            ,id: 'modx-'+this.ident+'-source'
            ,name: 'target'
            ,hiddenName: 'target'
            ,editable: false
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-source'
            ,html: _('user_group_source_source_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'modx-combo-authority'
            ,fieldLabel: _('minimum_role')
            ,description: MODx.expandHelp ? '' : _('user_group_source_authority_desc')
            ,id: 'modx-'+this.ident+'-authority'
            ,name: 'authority'
            ,value: 0
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-authority'
            ,html: _('user_group_source_authority_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,description: MODx.expandHelp ? '' : _('user_group_source_policy_desc')
            ,id: 'modx-'+this.ident+'-policy'
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,baseParams: {
                action: 'security/access/policy/getList'
                ,group: 'MediaSource'
            }
            ,anchor: '100%'
            ,listeners: {
                'select':{fn:this.onPolicySelect,scope:this}
            }
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-policy'
            ,html: _('user_group_source_policy_desc')
            ,cls: 'desc-under'
        },{
            id: 'modx-'+this.ident+'-permissions-list-ct'
            ,cls: 'modx-permissions-list'
            ,defaults: {border: false}
            ,autoHeight: true
            ,hidden: true
            ,anchor: '100%'
            ,items: [{
                html: '<h4>'+_('permissions_in_policy')+'</h4>'
                ,id: 'modx-'+this.ident+'-permissions-list-header'
            },{
                id: 'modx-'+this.ident+'-permissions-list'
                ,cls: 'modx-permissions-list-textarea'
                ,xtype: 'textarea'
                ,name: 'permissions'
                ,grow: false
                ,anchor: '100%'
                ,height: 100
                ,width: '97%'
                ,readOnly: true
            }]
        }]
    });
    MODx.window.CreateUGSource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUGSource,MODx.Window,{
    onPolicySelect: function(cb,rec,idx) {
        var s = cb.getStore();
        if (!s) return;

        var r = s.getAt(idx);
        var lc = Ext.getCmp('modx-'+this.ident+'-permissions-list-ct');
        if (r && idx>0) {
            lc.show();
            var pl = Ext.getCmp('modx-'+this.ident+'-permissions-list');
            var o = rec.data.permissions.join(', ');
            pl.setValue(o);
        } else {
            lc.hide();
        }
        this.doLayout();
    }
});
Ext.reg('modx-window-user-group-source-create',MODx.window.CreateUGSource);


MODx.window.UpdateUGSource = function(config) {
    config = config || {};
    this.ident = config.ident || 'updugsrc'+Ext.id();
    Ext.applyIf(config,{
        title: _('access_source_update')
        ,action: 'security/access/usergroup/source/update'
    });
    MODx.window.UpdateUGSource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateUGSource,MODx.window.CreateUGSource);
Ext.reg('modx-window-user-group-source-update',MODx.window.UpdateUGSource);
