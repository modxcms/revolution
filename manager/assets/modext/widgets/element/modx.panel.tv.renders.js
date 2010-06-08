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
        ,items: [{
            xtype: 'modx-combo-browser'
            ,browserEl: 'tvbrowser'+config.tv
            ,name: 'tv'+config.tv
            ,id: 'tv'+config.tv
            ,value: config.value
            ,hideFiles: true
            ,listeners: {
                'select': {fn:function(data) {
                    this.fireEvent('select',data);
                },scope:this}
            }
        }] 
    });
    MODx.panel.ImageTV.superclass.constructor.call(this,config);
    this.addEvents({select: true});
};
Ext.extend(MODx.panel.ImageTV,MODx.Panel);
Ext.reg('modx-panel-tv-image',MODx.panel.ImageTV);

MODx.panel.FileTV = function(config) {
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
        ,items: [{
            xtype: 'modx-combo-browser'
            ,browserEl: 'tvbrowser'+config.tv
            ,name: 'tv'+config.tv
            ,id: 'tv'+config.tv
            ,value: config.value
            ,hideFiles: true
            ,listeners: {
                'select': {fn:function(data) {
                    this.fireEvent('select',data);                    
                },scope:this}
            }
        }] 
    });
    MODx.panel.FileTV.superclass.constructor.call(this,config);
    this.addEvents({select: true});
};
Ext.extend(MODx.panel.FileTV,MODx.Panel);
Ext.reg('modx-panel-tv-file',MODx.panel.FileTV);

MODx.checkTV = function(id) {
    var cb = Ext.get('tv'+id);
    Ext.get('tvh'+id).dom.value = cb.dom.checked ? cb.dom.value : '';     
};