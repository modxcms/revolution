/**
 * Loads the welcome page
 * 
 * @class MODx.page.Welcome
 * @extends MODx.Component
 * @param {Object} config An object of configuration options
 * @xtype page-welcome
 */
MODx.page.Welcome = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-welcome'
            ,dashboard: config.dashboard || {}
        }]
    });
    MODx.page.Welcome.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Welcome,MODx.Component);
Ext.reg('modx-page-welcome',MODx.page.Welcome);

MODx.loadWelcomePanel = function(url) {
    if (!url) return;
    MODx.helpWindow = new Ext.Window({
        title: _('welcome_title')
        ,width: 850
        ,height: 500
        ,modal: true
        ,layout: 'fit'
        ,items: [{
            xtype: 'container'
            ,layout: {
                type: 'vbox'
                ,align: 'stretch'
            }
            ,width: '100%'
            ,height: '100%'
            ,items: [{
                autoEl: {
                    tag: 'iframe'
                    ,src: url
                    ,width: '100%'
                    ,height: '100%'
                    ,frameBorder: 0
                    ,onload: 'parent.MODx.helpWindow.getEl().unmask();'
                }
            }]
        }]
    });
    MODx.helpWindow.show(Ext.getBody());
};