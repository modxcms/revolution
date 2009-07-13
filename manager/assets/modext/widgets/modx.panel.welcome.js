/**
 * @class MODx.panel.Welcome
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-welcome
 */
MODx.panel.Welcome = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-welcome'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+MODx.config.site_name+'</h2>'
            ,id: 'modx-welcome-header'
            ,cls: 'modx-page-header'
            ,border: false
        },{
            title: _('configcheck_title')
            ,contentEl: 'modx-config'
            ,style: 'padding: .5em;'
            ,bodyStyle: 'padding: 1.5em;'
            ,collapsible: true
            ,titleCollapse: true
            ,hidden: !config.displayConfigCheck
        },{
            xtype: 'portal'
            ,id: 'modx-welcome-portal'
            ,items: [{
                columnWidth: .46
                ,id: 'modx-welcome-col-left'
                ,defaults: {
                    height: 300
                    ,autoHeight: false
                    ,autoScroll: true
                    ,titleCollapse: true
                }
                ,items: [{
                    title: _('modx_news')
                    ,contentEl: 'modx-news'
                },{
                    title: _('recent_docs')
                    ,id: 'modx-recent'
                    ,collapsed: true
                    ,bodyStyle: 'padding: 1.5em;'
                    ,items: [{
                        html: '<p>'+_('activity_message')
                        ,border: false
                    },{
                        xtype: 'modx-grid-user-recent-resource'
                        ,user: config.user
                        ,preventRender: true
                    }]
                },{
                    title: _('online')
                    ,contentEl: 'modx-online'
                    ,collapsed: true
                    ,bodyStyle: 'padding: 1.5em;'
                }]
            },{
                columnWidth: .46
                ,id: 'modx-welcome-col-right'
                ,defaults: {
                    height: 300
                    ,autoHeight: false
                    ,autoScroll: true
                    ,titleCollapse: true
                }
                ,items: [{
                    title: _('security_notices')
                    ,contentEl: 'modx-security'
                },{
                    title: _('info')
                    ,contentEl: 'modx-info'
                    ,bodyStyle: 'padding: 1.5em;'
                    ,collapsed: true
                }]
            }]
        }]
    });
    MODx.panel.Welcome.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Welcome,MODx.FormPanel);
Ext.reg('modx-panel-welcome',MODx.panel.Welcome);