Ext.onReady(function() {
	MODx.load({ xtype: 'modx-page-access-policies' });
});

/**
 * Loads the access policies page
 * 
 * @class MODx.page.AccessPolicies
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-access-policies
 */
MODx.page.AccessPolicies = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        components: [{ 
            xtype: 'modx-panel-access-policies'
            ,renderTo: 'modx-panel-access-policies-div'
        }] 
        ,buttons: [{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
    });
	MODx.page.AccessPolicies.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.AccessPolicies,MODx.Component);
Ext.reg('modx-page-access-policies',MODx.page.AccessPolicies);