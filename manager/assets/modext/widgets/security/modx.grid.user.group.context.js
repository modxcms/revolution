
MODx.grid.UserGroupContext = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="desc">{permissions}</p>'
        )
    });
    Ext.applyIf(config,{
        id: 'modx-grid-user-group-contexts'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/access/usergroup/context/getList'
            ,usergroup: config.usergroup
        }
        ,fields: ['id','target','principal','authority','authority_name','policy','policy_name','permissions','cls']
        ,paging: true
        ,hideMode: 'offsets'
        ,grouping: true
        ,groupBy: 'authority_name'
        ,singleText: _('policy')
        ,pluralText: _('policies')
        ,sortBy: 'authority'
        ,sortDir: 'ASC'
        ,remoteSort: true
        ,plugins: [this.exp]
        ,columns: [this.exp,{
            header: _('context')
            ,dataIndex: 'target'
            ,width: 120
            ,sortable: true
        },{
            header: _('minimum_role')
            ,dataIndex: 'authority_name'
            ,width: 100
            ,sortable: false
        },{
            header: _('policy')
            ,dataIndex: 'policy_name'
            ,width: 200
            ,sortable: true
        }]
        ,tbar: [{
            text: _('context_add')
            ,cls:'primary-button'
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
            ,baseParams: {
                action: 'security/access/policy/getList'
                ,group: 'Admin'
            }
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

    ,getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.cls;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {

        } else {
            if (p.indexOf('pedit') != -1) {
                m.push({
                    text: _('access_context_update')
                    ,handler: this.updateAcl
                });
            }
            if (p.indexOf('premove') != -1) {
                if (m.length > 0) { m.push('-'); }
                m.push({
                    text: _('access_context_remove')
                    ,handler: this.remove.createDelegate(this,["confirm_remove","security/access/usergroup/context/remove"])
                });
            }
        }

        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }

    ,filterContext: function(cb,rec,ri) {
        this.getStore().baseParams['context'] = rec.data['key'];
        this.getBottomToolbar().changePage(1);
        //this.refresh();
    }
    ,filterPolicy: function(cb,rec,ri) {
        this.getStore().baseParams['policy'] = rec.data['id'];
        this.getBottomToolbar().changePage(1);
        //this.refresh();
    }

    ,clearFilter: function(btn,e) {
        Ext.getCmp('modx-ugc-context-filter').setValue('');
        this.getStore().baseParams['context'] = '';
        Ext.getCmp('modx-ugc-policy-filter').setValue('');
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
    this.ident = config.ident || 'cugactx'+Ext.id();
    Ext.applyIf(config,{
        title: _('ugc_mutate')
        ,url: MODx.config.connector_url
        ,action: 'security/access/usergroup/context/create'
        // ,width: 600
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'modx-combo-context'
            ,fieldLabel: _('context')
            ,description: MODx.expandHelp ? '' : _('user_group_context_context_desc')
            ,id: 'modx-'+this.ident+'-context'
            ,name: 'target'
            ,hiddenName: 'target'
            ,editable: false
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-context'
            ,html: _('user_group_context_context_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'modx-combo-authority'
            ,fieldLabel: _('minimum_role')
            ,description: MODx.expandHelp ? '' : _('user_group_context_authority_desc')
            ,id: 'modx-'+this.ident+'-authority'
            ,name: 'authority'
            ,value: 0
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-authority'
            ,html: _('user_group_context_authority_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,description: MODx.expandHelp ? '' : _('user_group_context_policy_desc')
            ,id: 'modx-'+this.ident+'-policy'
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,baseParams: {
                action: 'security/access/policy/getList'
                ,group: 'Admin,Object'
                ,combo: '1'
            }
            ,allowBlank: false
            ,anchor: '100%'
            ,listeners: {
                'select':{fn:this.onPolicySelect,scope:this}
            }
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-policy'
            ,html: _('user_group_context_policy_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'hidden'
            ,name: 'principal'
            ,hiddenName: 'principal'
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
                ,grow: false
                ,anchor: '100%'
                ,height: 150
                ,width: '97%'
                ,readOnly: true
            }]
        }]
    });
    MODx.window.CreateUGAccessContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUGAccessContext,MODx.Window,{
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
Ext.reg('modx-window-user-group-context-create',MODx.window.CreateUGAccessContext);


MODx.window.UpdateUGAccessContext = function(config) {
    config = config || {};
    this.ident = config.ident || 'uugactx'+Ext.id();
    Ext.applyIf(config,{
        title: _('ugc_mutate')
        ,action: 'security/access/usergroup/context/update'
    });
    MODx.window.UpdateUGAccessContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateUGAccessContext,MODx.window.CreateUGAccessContext);
Ext.reg('modx-window-user-group-context-update',MODx.window.UpdateUGAccessContext);

