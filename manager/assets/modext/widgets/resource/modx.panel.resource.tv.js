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
        ,bodyStyle: 'padding: 15px;'
        ,autoHeight: true
        ,autoLoad: this.autoload(config)
        ,width: '97%'
        ,templateField: 'modx-resource-template'
    });
    MODx.panel.ResourceTV.superclass.constructor.call(this,config);
    this.addEvents({ load: true });
};
Ext.extend(MODx.panel.ResourceTV,MODx.Panel,{
    /**
     * Autoloads the TV panel
     * @param {Object} config
     */
    autoload: function(config) {
        var t = Ext.getCmp(config.templateField);
        if (!t && !config.template) { return false; }
        var template = config.template ? config.template : t.getValue();
        var a = {
            url: MODx.config.manager_url+'index.php?a='+MODx.action['resource/tvs']
            ,method: 'GET'
            ,params: {
               'a': MODx.action['resource/tvs']
               ,'class_key': config.class_key
               ,'template': template
               ,'resource': config.resource
            }
            ,scripts: true
            ,callback: function() {
                if (MODx.afterTVLoad) { MODx.afterTVLoad(); }
                this.fireEvent('load');
                MODx.fireEvent('ready');
            }
            ,scope: this
        };
        return a;        	
    }
    
    ,refreshTVs: function() {
        var t = Ext.getCmp(this.config.templateField);
        if (!t && !this.config.template) { return false; }
        var template = this.config.template ? this.config.template : t.getValue();
        
        this.getUpdater().update({
            url: MODx.config.manager_url+'index.php?a='+MODx.action['resource/tvs']
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