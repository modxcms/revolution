/**
 * Renders an input for an image TV
 * 
 * @class MODx.panel.ImageTV
 * @extends MODx.Panel
 * @param {Object} config An object of configuration properties
 * @xtype panel-tv-image
 */
MODx.panel.ImageTV = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        layout: 'form'
        ,autoHeight: true
        ,border: false
        ,hideLabels: true
        ,defaults: {
            autoHeight: true
            ,border: false
        }
        ,width: 400
        ,items: [{
            xtype: 'modx-combo-browser'
            ,browserEl: 'tvbrowser'+config.tv
            ,name: 'tv'+config.tv
            ,id: 'tv'+config.tv
            ,value: config.value
            ,hideFiles: true
            ,listeners: {
                'change': {fn:triggerDirtyField,scope:this}
                ,'select': {fn:function(data) {
                    
                },scope:this}
            }
            ,width: 200
        }] 
    });
    MODx.panel.ImageTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ImageTV,MODx.Panel);
Ext.reg('modx-panel-tv-image',MODx.panel.ImageTV);