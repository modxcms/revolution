/**
 * @class MODx.panel.Welcome
 * @extends MODx.Panel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-welcome
 */
MODx.panel.Welcome = function(config) {
    dashboardName = config.dashboard.id == 1 ? MODx.config.site_name : MODx.config.site_name+' - '+config.dashboard.name;
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-welcome'
        ,cls: 'container'
        ,baseCls: 'modx-formpanel'
        ,layout: 'form'
        ,defaults: {
            collapsible: false
            ,autoHeight: true
        }
        ,items: [{
            html: dashboardName
            ,id: 'modx-welcome-header'
            ,xtype: 'modx-header'
        },{
            applyTo: 'modx-dashboard'
            ,border: false
        }]
    });
    MODx.panel.Welcome.superclass.constructor.call(this,config);
    this.setup();
};
Ext.extend(MODx.panel.Welcome, MODx.Panel,{
    setup: function() {
        if (this.config.dashboard && this.config.dashboard.hide_trees) {
            Ext.getCmp('modx-layout').hideLeftbar(false);
        }
        var newsContainer = Ext.get('modx-news-feed-container');
        if (newsContainer) {
            this.loadFeed(newsContainer, 'news');
        }
        var securityContainer = Ext.get('modx-security-feed-container');
        if (securityContainer) {
            this.loadFeed(securityContainer, 'security');
        }
        MODx.fireEvent('ready');
    },

    loadFeed: function(container, feed) {
        MODx.Ajax.request({
                url: MODx.config.connector_url + '?action=system/dashboard/widget/feed&feed=' + feed
                ,listeners: {
                    success: {
                        fn: function(response) {
                            if (response.success) {
                                container.update(response.object.html);
                            }
                            else if (response.message.length > 0) {
                                container.update('<p class="error">' + response.message + '</p>');
                            }
                        }, scope: this
                    }
                    ,failure: {
                        fn: function(response) {
                            var message = response.message.length > 0 ? response.message : _('error_loading_feed');
                            container.update('<p class="error">' + message + '</p>');
                        }, scope: this
                    }
                }
            }
        );
    }
});
Ext.reg('modx-panel-welcome', MODx.panel.Welcome);
