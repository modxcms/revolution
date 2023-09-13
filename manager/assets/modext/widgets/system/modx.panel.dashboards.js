/**
 * @class MODx.panel.Dashboards
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-dashboards
 */
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
        }])]
    });
    MODx.panel.Dashboards.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Dashboards,MODx.FormPanel);
Ext.reg('modx-panel-dashboards',MODx.panel.Dashboards);

/**
 * @class MODx.grid.Dashboards
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-dashboards
 */
MODx.grid.Dashboards = function(config = {}) {
    const queryValue = this.applyRequestFilter(0, 'query', 'tab', true);
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'System/Dashboard/GetList',
            usergroup: MODx.request.usergroup || null
        }
        ,fields: [
            'id',
            'name',
            'description',
            'cls'
        ]
        ,paging: true
        ,autosave: true
        ,save_action: 'System/Dashboard/UpdateFromGrid'
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
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=system/dashboards/update&id=' + record.data.id
                });
            }, scope: this }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 300
            ,sortable: false
            ,editor: { xtype: 'textarea' }
        }]
        ,tbar: [
            {
                text: _('create')
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
                ,itemId: 'filter-usergroup'
                ,emptyText: _('user_group_filter')
                ,baseParams: {
                    action: 'Security/Group/GetList'
                    ,addAll: true
                }
                ,value: MODx.request.usergroup || null
                ,width: 200
                ,listeners: {
                    select: {
                        fn: function (cmp, record, selectedIndex) {
                            this.applyGridFilter(cmp, 'usergroup');
                        },
                        scope: this
                    }
                }
            },
            this.getQueryFilterField(`filter-query:${queryValue}`),
            this.getClearFiltersButton('filter-usergroup, filter-query')
        ]
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
                    text: _('edit')
                    ,handler: this.updateDashboard
                });
            }
            if (p.indexOf('pduplicate') != -1) {
                m.push({
                    text: _('duplicate')
                    ,handler: this.duplicateDashboard
                });
            }
            if (p.indexOf('premove') != -1 && r.data.id != 1 && r.data.name != 'Default') {
                if (m.length > 0) m.push('-');
                m.push({
                    text: _('delete')
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

    ,updateDashboard: function() {
        MODx.loadPage('system/dashboards/update', 'id='+this.menu.record.id);
    }

    ,duplicateDashboard: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'System/Dashboard/Duplicate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,removeDashboard: function() {
        MODx.msg.confirm({
            title: _('delete')
            ,text: _('dashboard_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'System/Dashboard/Remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,removeSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('selected_remove')
            ,text: _('dashboard_remove_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'System/Dashboard/RemoveMultiple'
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

});
Ext.reg('modx-grid-dashboards',MODx.grid.Dashboards);
