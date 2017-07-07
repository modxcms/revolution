MODx.panel.Dashboards = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-dashboards'
        ,cls: 'container'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: _('dashboards')
            ,id: 'modx-dashboards-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            layout: 'form'
            ,title: _('dashboards')
            ,items: [{
                html: '<p>'+_('dashboards.intro_msg')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-dashboards'
                ,cls: 'main-wrapper'
                ,preventRender: true
            }]
        },{
            layout: 'form'
            ,title: _('widgets')
            ,items: [{
                html: '<p>'+_('widgets.intro_msg')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-dashboard-widgets'
                ,cls: 'main-wrapper'
                ,preventRender: true
            }]
        }],{
            stateful: true
            ,stateId: 'modx-dashboards-tabpanel'
            ,stateEvents: ['tabchange']
            ,getState:function() {
                return {activeTab:this.items.indexOf(this.getActiveTab())};
            }
        })]
    });
    MODx.panel.Dashboards.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Dashboards,MODx.FormPanel);
Ext.reg('modx-panel-dashboards',MODx.panel.Dashboards);

MODx.grid.Dashboards = function(config) {
    config = config || {};

    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/dashboard/getlist'
        }
        ,fields: ['id','name','description','cls']
        ,paging: true
        ,autosave: true
        ,save_action: 'system/dashboard/updatefromgrid'
        ,remoteSort: true
        ,sm: this.sm
        ,columns: [this.sm,{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 50
            ,sortable: true
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,sortable: true
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 300
            ,sortable: false
            ,editor: { xtype: 'textarea' }
        }]
        ,tbar: [{
            text: _('dashboard_create')
            ,cls:'primary-button'
            ,handler: this.createDashboard
            ,scope: this
        },{
            text: _('bulk_actions')
            ,menu: [{
                text: _('selected_remove')
                ,handler: this.removeSelected
                ,scope: this
            }]
        },'->',{
            xtype: 'modx-combo-usergroup'
            ,name: 'usergroup'
            ,id: 'modx-user-filter-usergroup'
            ,itemId: 'usergroup'
            ,emptyText: _('user_group_filter')+'...'
            ,baseParams: {
                action: 'security/group/getlist'
                ,addAll: true
            }
            ,value: ''
            ,width: 200
            ,listeners: {
                'select': {fn:this.filterUsergroup,scope:this}
            }
        },{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-dashboard-search'
            ,cls: 'x-form-filter'
            ,emptyText: _('search_ellipsis')
            ,listeners: {
                'change': {fn: this.search, scope: this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this.getValue());
                            this.blur();
                            return true;}
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,text: _('filter_clear')
            ,id: 'modx-filter-clear'
            ,cls: 'x-form-filter-clear'
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this},
                'mouseout': { fn: function(evt){
                    this.removeClass('x-btn-focus');
                }
                }
            }
        }]
    });
    MODx.grid.Dashboards.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Dashboards,MODx.grid.Grid,{
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.cls;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
            m.push({
                text: _('selected_remove')
                ,handler: this.removeSelected
                ,scope: this
            });
        } else {
            if (p.indexOf('pupdate') != -1) {
                m.push({
                    text: _('dashboard_update')
                    ,handler: this.updateDashboard
                });
            }
            if (p.indexOf('pduplicate') != -1) {
                m.push({
                    text: _('dashboard_duplicate')
                    ,handler: this.duplicateDashboard
                });
            }
            if (p.indexOf('premove') != -1 && r.data.id != 1 && r.data.name != 'Default') {
                if (m.length > 0) m.push('-');
                m.push({
                    text: _('dashboard_remove')
                    ,handler: this.removeDashboard
                });
            }
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }

    ,createDashboard: function() {
        MODx.loadPage('system/dashboards/create');
    }
    ,removeSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('dashboard_remove_multiple')
            ,text: _('dashboard_remove_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'system/dashboard/removeMultiple'
                ,dashboards: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }

    ,removeDashboard: function() {
        MODx.msg.confirm({
            title: _('dashboard_remove')
            ,text: _('dashboard_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'system/dashboard/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
            	'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,duplicateDashboard: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'system/dashboard/duplicate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,updateDashboard: function() {
        MODx.loadPage('system/dashboards/update', 'id='+this.menu.record.id);
    }

    ,filterUsergroup: function(cb,nv,ov) {
        this.getStore().baseParams.usergroup = Ext.isEmpty(nv) || Ext.isObject(nv) ? cb.getValue() : nv;
        this.getBottomToolbar().changePage(1);
        //this.refresh();
        return true;
    }
    ,search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.query = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        this.getBottomToolbar().changePage(1);
        //this.refresh();
        return true;
    }
    ,clearFilter: function() {
    	this.getStore().baseParams = {
            action: 'system/dashboard/getList'
    	};
        Ext.getCmp('modx-dashboard-search').reset();
        Ext.getCmp('modx-user-filter-usergroup').reset();
    	this.getBottomToolbar().changePage(1);
        //this.refresh();
    }
});
Ext.reg('modx-grid-dashboards',MODx.grid.Dashboards);
