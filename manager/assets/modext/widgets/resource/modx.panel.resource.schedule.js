/**
 * Loads the Resource Schedule panel
 *
 * @class MODx.panel.ResourceSchedule
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration options
 * @xtype modx-panel-resource-schedule
 */
MODx.panel.ResourceSchedule = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-resource-schedule'
		,cls: 'container'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: _('site_schedule')
            ,id: 'modx-resource-schedule-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('site_schedule')
            ,layout: 'form'
            ,items: [{
                html: '<p>'+_('site_schedule_desc')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-resource-schedule'
				,cls:'main-wrapper'
                ,preventRender: true
            }]
        }])]
    });
    MODx.panel.ResourceSchedule.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ResourceSchedule,MODx.FormPanel);
Ext.reg('modx-panel-resource-schedule',MODx.panel.ResourceSchedule);

/**
 * Loads a grid of Publish/Unpublish events for a resource.
 *
 * @class MODx.grid.ResourceSchedule
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-resource-schedule
 */
MODx.grid.ResourceSchedule = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('site_schedule')
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Resource/Event/GetList'
            ,mode: 'pub_date'
        }
        ,fields: [
            'id'
            ,'pagetitle'
            ,'class_key'
            ,{name: 'pub_date', type: 'date'}
            ,{name: 'unpub_date', type:'date'}
            ,'menu'
        ]
        ,showActionsColumn: false
        ,paging: true
        ,save_action: 'Resource/Event/UpdateFromGrid'
        ,autosave: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
        },{
            header: _('pagetitle')
            ,dataIndex: 'pagetitle'
            ,width: 40
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=resource/update&id=' + record.data.id
                    ,target: '_blank'
                });
            }, scope: this }
        },{
            header: _('publish_date')
            ,dataIndex: 'pub_date'
            ,width: 150
            ,editor: {
                xtype: 'xdatetime'
                ,dateFormat: MODx.config.manager_date_format
                ,timeFormat: MODx.config.manager_time_format
                ,ctCls: 'x-datetime-inline-editor'
            }
            ,renderer: Ext.util.Format.dateRenderer(MODx.config.manager_date_format + ' ' + MODx.config.manager_time_format)
        },{
            header: _('unpublish_date')
            ,dataIndex: 'unpub_date'
            ,width: 150
            ,editor: {
                xtype: 'xdatetime'
                ,dateFormat: MODx.config.manager_date_format
                ,timeFormat: MODx.config.manager_time_format
                ,ctCls: 'x-datetime-inline-editor'
            }
            ,renderer: Ext.util.Format.dateRenderer(MODx.config.manager_date_format + ' ' + MODx.config.manager_time_format)
        }]
        ,tbar: [{
            text: _('showing_pub')
            ,scope: this
            ,handler: this.toggle
            ,enableToggle: true
            ,tooltip: _('click_to_change')
            ,id: 'btn-toggle'
            ,cls:'primary-button'
        }]
    });
    MODx.grid.ResourceSchedule.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ResourceSchedule,MODx.grid.Grid,{
    toggle: function(btn,e) {
        var s = this.getStore();
        if (btn.pressed) {
            s.setBaseParam('mode','unpub_date');
            btn.setText(_('showing_unpub'));
        } else {
            s.setBaseParam('mode','pub_date');
            btn.setText(_('showing_pub'));
        }
        this.getBottomToolbar().changePage(1);
        s.removeAll();
    }
});
Ext.reg('modx-grid-resource-schedule',MODx.grid.ResourceSchedule);
