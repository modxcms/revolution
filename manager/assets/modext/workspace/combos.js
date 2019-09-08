/**
 * Displays a dropdown list of modTransportProviders
 * 
 * @class MODx.combo.Provider
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of options.
 * @xtype combo-provider
 */
MODx.combo.Provider = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'provider'
        ,hiddenName: 'provider'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Workspace/Providers/GetList'
            ,combo: true
        }
        ,editable: false
        ,pageSize: 20
    });
    MODx.combo.Provider.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Provider,MODx.combo.ComboBox);
Ext.reg('modx-combo-provider',MODx.combo.Provider);
