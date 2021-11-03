/**
 * Loads the Resource TV Panel
 *
 * @class MODx.panel.ResourceTV
 * @extends MODx.Panel
 * @param {Object} config
 * @xtype panel-resource-tv
 */
MODx.panel.ResourceTV = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-resource-tv'
        ,title: _('template_variables')
        ,class_key: ''
        ,resource: ''
        ,cls: MODx.config.tvs_below_content == 1 ? 'x-panel-body tvs-wrapper below-content' : 'tvs-wrapper x-panel-body'
        ,autoHeight: true
        ,applyTo: 'modx-resource-tvs-div'
        ,header: false
        ,templateField: 'modx-resource-template'
    });
    MODx.panel.ResourceTV.superclass.constructor.call(this,config);
    this.addEvents({ load: true });
};
Ext.extend(MODx.panel.ResourceTV,MODx.Panel,{
    autoload: function() {
        return false;
    }
});
Ext.reg('modx-panel-resource-tv',MODx.panel.ResourceTV);
