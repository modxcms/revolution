/**
 * Loads the deprecated log page.
 *
 * @class MODx.page.DeprecatedLog
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-system-event
 */
MODx.page.DeprecatedLog = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-deprecated-log'
        ,buttons: [{
            text: _('clear')
            ,id: 'modx-abtn-clear'
            ,cls: 'primary-button'
            ,handler: this.clear
            ,scope: this
            ,hidden: !MODx.perm.error_log_erase
        },{
            text: _('cancel')
            ,id: 'modx-abtn-cancel'
        }]
        ,components: [{
            xtype: 'modx-panel-deprecated-log'
            ,record: config.record || {}
        }]
    });
    MODx.page.DeprecatedLog.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.DeprecatedLog,MODx.Component,{
    clear: function() {
        var panel = Ext.getCmp('modx-panel-deprecated-log');
        panel.el.mask(_('working'));
        MODx.Ajax.request({
            url: panel.config.url
            ,params: {
                action: 'System/DeprecatedLog/Clear'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.el.unmask();
                    MODx.msg.status({
                        title: _('success'),
                        message: _('deprecated_log_cleared')
                    });
                    var grid = Ext.getCmp('modx-grid-deprecated-log');
                    if (grid) {
                        grid.refresh();
                    }
                    // refresh grid
                },scope:panel}
            }
        });
    }
});
Ext.reg('modx-page-deprecated-log',MODx.page.DeprecatedLog);
