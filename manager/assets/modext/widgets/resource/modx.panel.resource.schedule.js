MODx.panel.ResourceSchedule = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-resource-schedule'
		,cls: 'container'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('site_schedule')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-resource-schedule-header'
        },{
            layout: 'form'
            ,items: [{
                html: '<p>'+_('site_schedule_desc')+'</p>'
				,bodyCssClass: 'panel-desc'
                ,border: false
            },{
                xtype: 'modx-grid-resource-schedule'
				,cls:'main-wrapper'
                ,preventRender: true
            }]
        }]
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
        ,url: MODx.config.connectors_url+'resource/event.php'
        ,baseParams: {
            action: 'getList'
            ,mode: 'pub_date'
        }
        ,fields: ['id','pagetitle','class_key'
            ,{name: 'pub_date', type:'date',format: 'D M d, Y'}
            ,{name: 'unpub_date', type:'date',format: 'D M d, Y'}
            ,'menu']
        ,paging: true
        ,autosave: true
        ,columns: [
            { header: _('id') ,dataIndex: 'id' ,width: 40 }
            ,{ header: _('pagetitle') ,dataIndex: 'pagetitle' ,width: 40 }
            ,{ 
                header: _('publish_date')
                ,dataIndex: 'pub_date'
                ,width: 150
                ,editor: { xtype: 'datefield' ,format: MODx.config.manager_date_format }
            },{ 
                header: _('unpublish_date')
                ,dataIndex: 'unpub_date'
                ,width: 150
                ,editor: { xtype: 'datefield' ,format: MODx.config.manager_date_format }
            }
        ]
        ,tbar: [{
            text: _('showing_pub')
            ,scope: this
            ,handler: this.toggle
            ,enableToggle: true
            ,tooltip: _('click_to_change')
            ,id: 'btn-toggle'
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
        this.refresh();
    }
});
Ext.reg('modx-grid-resource-schedule',MODx.grid.ResourceSchedule);
