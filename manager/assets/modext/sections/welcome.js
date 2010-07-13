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
            ,renderTo: 'modx-panel-welcome-div'
            ,displayConfigCheck: config.displayConfigCheck
            ,user: MODx.user.id
            ,newsEnabled: config.newsEnabled
            ,securityEnabled: config.securityEnabled
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
        ,html: '<iframe onload="parent.MODx.helpWindow.getEl().unmask();" src="' + url + '" width="100%" height="100%" frameborder="0"></iframe>'
        ,listeners: {
            show: function(o) {
                o.getEl().mask(_('help_loading'));
            }
        }
    });
    MODx.helpWindow.show(Ext.getBody());
};