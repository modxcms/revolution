
MODx.grid.UserGroupCategory = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="desc">{permissions}</p>'
        )
    });
    Ext.applyIf(config,{
        id: 'modx-grid-user-group-categories'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/access/usergroup/category/getList'
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
            header: _('category')
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
            text: _('category_add')
            ,cls: 'primary-button'
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
            ,baseParams: {
                action: 'security/access/policy/getList'
                ,group: 'Object'
            }
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
        //this.refresh();
    }

    ,clearFilter: function(btn,e) {
        Ext.getCmp('modx-ugcat-category-filter').setValue('');
        this.getStore().baseParams['category'] = '';
        Ext.getCmp('modx-ugcat-policy-filter').setValue('');
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
    this.ident = config.ident || 'cugcat'+Ext.id();
    Ext.applyIf(config,{
        title: _('category_add')
        ,url: MODx.config.connector_url
        ,action: 'security/access/usergroup/category/create'
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
            xtype: 'modx-combo-category'
            ,fieldLabel: _('category')
            ,description: MODx.expandHelp ? '' : _('user_group_category_category_desc')
            ,id: 'modx-'+this.ident+'-category'
            ,name: 'target'
            ,hiddenName: 'target'
            ,editable: false
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-category'
            ,html: _('user_group_category_category_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'modx-combo-context'
            ,fieldLabel: _('context')
            ,description: MODx.expandHelp ? '' : _('user_group_category_context_desc')
            ,id: 'modx-'+this.ident+'-context'
            ,name: 'context_key'
            ,hiddenName: 'context_key'
            ,editable: false
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-context'
            ,html: _('user_group_category_context_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'modx-combo-authority'
            ,fieldLabel: _('minimum_role')
            ,description: MODx.expandHelp ? '' : _('user_group_category_policy_desc')
            ,id: 'modx-'+this.ident+'-authority'
            ,name: 'authority'
            ,value: 0
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-authority'
            ,html: _('user_group_category_authority_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,description: MODx.expandHelp ? '' : _('user_group_category_policy_desc')
            ,id: 'modx-'+this.ident+'-policy'
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,baseParams: {
                action: 'security/access/policy/getList'
                ,group: 'Element,Object'
                ,combo: '1'
            }
            ,anchor: '100%'
            ,listeners: {
                'select':{fn:this.onPolicySelect,scope:this}
            }
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-policy'
            ,html: _('user_group_category_policy_desc')
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
    MODx.window.CreateUGCat.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUGCat,MODx.Window,{
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
Ext.reg('modx-window-user-group-category-create',MODx.window.CreateUGCat);


MODx.window.UpdateUGCat = function(config) {
    config = config || {};
    this.ident = config.ident || 'updugcat'+Ext.id();

    Ext.applyIf(config,{
        title: _('access_category_update')
        ,action: 'security/access/usergroup/category/update'
    });
    MODx.window.UpdateUGCat.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateUGCat, MODx.window.CreateUGCat);
Ext.reg('modx-window-user-group-category-update',MODx.window.UpdateUGCat);
