/**
 * Loads the error log page
 *
 * @class MODx.page.ErrorLog
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-system-event
 */
MODx.page.ErrorLog = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-error-log'
        ,buttons: [{
            text: _('clear')
            ,handler: this.clear
            ,scope: this
            ,hidden: MODx.hasEraseErrorLog ? false : true
        },'-',{
            text: _('ext_refresh')
            ,handler: this.refreshLog
            ,scope: this
            ,hidden: config.record.tooLarge
        },'-',{
            text: _('cancel')
        }]
        ,components: [{
            xtype: 'modx-panel-error-log'
            ,record: config.record || {}
        }]
    });
    MODx.page.ErrorLog.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ErrorLog,MODx.Component,{
    clear: function() {
        var panel = Ext.getCmp('modx-panel-error-log');
        panel.el.mask(_('working'));
        MODx.Ajax.request({
            url: panel.config.url
            ,params: {
                action: 'system/errorlog/clear'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.el.unmask();
                    if (!r.object.tooLarge && this.config.record.tooLarge) {
                        location.href = location.href;
                    } else {
                        this.getForm().setValues(r.object);
                    }
                },scope:panel}
            }
        });
    }
    ,refreshLog: function() {
        var panel = Ext.getCmp('modx-panel-error-log');
        panel.el.mask(_('working'));
        MODx.Ajax.request({
            url: panel.config.url
            ,params: {
                action: 'system/errorlog/get'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.el.unmask();
                    if (r.object.tooLarge) {
                        location.href = location.href;
                    } else {
                        this.getForm().setValues(r.object);
                    }
                },scope:panel}
            }
        });
    }
});
Ext.reg('modx-page-error-log',MODx.page.ErrorLog);
