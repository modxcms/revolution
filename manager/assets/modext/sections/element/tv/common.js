/**
 * Displays a dropdown list of widgets
 * 
 * @class MODx.combo.TVWidget
 * @extends MODx.combo.ComboBox
 * @xtype modx-combo-tv-widget
 */
MODx.combo.TVWidget = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'widget'
        ,hiddenName: 'widget'
        ,displayField: 'name'
        ,valueField: 'value'
        ,fields: ['value','name']
        ,editable: false
        ,url: MODx.config.connectors_url+'element/tv/renders.php'
        ,baseParams: {
            action: 'getOutputs'
        }
        ,value: 'default'
    });
    MODx.combo.TVWidget.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.TVWidget,MODx.combo.ComboBox);
Ext.reg('modx-combo-tv-widget',MODx.combo.TVWidget);

/**
 * Displays a tv input type
 * 
 * @class MODx.combo.TVInputType
 * @extends Ext.form.ComboBox
 * @xtype modx-combo-tv-input-type
 */
MODx.combo.TVInputType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'type'
        ,hiddenName: 'type'
        ,displayField: 'name'
        ,valueField: 'value'
        ,editable: false
        ,fields: ['value','name']
        ,url: MODx.config.connectors_url+'element/tv/renders.php'
        ,baseParams: {
            action: 'getInputs'
        }
        ,value: 'text'
    });
    MODx.combo.TVInputType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.TVInputType,MODx.combo.ComboBox);
Ext.reg('modx-combo-tv-input-type',MODx.combo.TVInputType);
