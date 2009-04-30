MODx.panel.ElementProperties = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-element-properties'
        ,title: _('properties')
        ,bodyStyle: 'padding: 1.5em;'
        ,defaults: { collapsible: false ,autoHeight: true ,border: false }
        ,items: [{
            html: '<p>'+_('element_properties_desc')+'</p>'
        },{
            id: 'modx-grid-element-properties-ct'
            ,autoHeight: true
        }]
    });
    MODx.panel.ElementProperties.superclass.constructor.call(this,config);
    /* load after b/c of safari/ie focus bug */
    (function() {
    MODx.load({
        xtype: 'modx-grid-element-properties'
        ,id: 'modx-grid-element-properties'
        ,autoHeight: true
        ,panel: config.elementPanel
        ,elementId: config.elementId
        ,elementType: config.elementType
        ,renderTo: 'modx-grid-element-properties-ct'
    });
    }).defer(50,this);
};
Ext.extend(MODx.panel.ElementProperties,MODx.Panel);
Ext.reg('modx-panel-element-properties',MODx.panel.ElementProperties);