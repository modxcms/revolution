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
    ,refreshTVs: function() {
        return false;
        var t = Ext.getCmp(this.config.templateField);
        if (!t && !this.config.template) { return false; }
        var template = this.config.template ? this.config.template : t.getValue();

        this.getUpdater().update({
            url: MODx.config.manager_url+'?a=resource/tvs'
            ,method: 'GET'
            ,params: {
               'class_key': this.config.class_key
               ,'template': template
               ,'resource': this.config.resource
            }
            ,scripts: true
            ,callback: function() {
                this.fireEvent('load');
                if (MODx.afterTVLoad) { MODx.afterTVLoad(); }
            }
            ,scope: this
        });
    }
});
Ext.reg('modx-panel-resource-tv',MODx.panel.ResourceTV);
