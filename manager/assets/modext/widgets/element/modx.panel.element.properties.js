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
            xtype: 'modx-grid-element-properties'
            ,id: 'modx-grid-element-properties'
            ,autoHeight: true
            ,border: true
            ,panel: config.elementPanel
            ,elementId: config.elementId
            ,elementType: config.elementType
        }]
    });
    MODx.panel.ElementProperties.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ElementProperties,MODx.Panel);
Ext.reg('modx-panel-element-properties',MODx.panel.ElementProperties);