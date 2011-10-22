/**
 * Loads the Search page
 * 
 * @class MODx.page.Search
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-page-search
 */
MODx.page.Search = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-search'
            ,renderTo: 'modx-panel-search-div'
            ,record: config.record || {}
        }]
    });
	MODx.page.Search.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Search,MODx.Component);
Ext.reg('modx-page-search',MODx.page.Search);