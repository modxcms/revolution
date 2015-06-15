/**
 * Loads the help page
 * 
 * @class MODx.page.Help
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-help
 */


MODx.page.Help = function(config) {
    config = config || {};

    // Hijack the html content
    var content = document.getElementById('modx-page-help-content');
    var contentHTML = content.innerHTML;
    content.parentNode.removeChild(content);


    Ext.applyIf(config,{
        padding: 0
        ,margin: 0
        ,components: [{
            xtype: 'modx-panel'
            ,style: {
                margin: 0
            }
            ,padding: 10
            ,margin:0
            ,renderTo: 'help-content-here-people'
            ,html: contentHTML
            ,cls: 'nobg'
        }]
    });
    MODx.page.Help.superclass.constructor.call(this,config);

};
Ext.extend(MODx.page.Help,MODx.Component);
Ext.reg('modx-page-help',MODx.page.Help);
