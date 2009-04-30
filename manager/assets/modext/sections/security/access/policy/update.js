/**
 * Loads the access policy update page
 * 
 * @class MODx.page.UpdateAccessPolicy
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-access-policy
 */
MODx.page.UpdateAccessPolicy = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{ 
            xtype: 'modx-panel-access-policy'
            ,renderTo: 'modx-panel-access-policy'
            ,policy: config.policy
        }]
    });
    MODx.page.UpdateAccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateAccessPolicy,MODx.Component);
Ext.reg('modx-page-access-policy',MODx.page.UpdateAccessPolicy);