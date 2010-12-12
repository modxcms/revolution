
MODx.combo.FCAction = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [
                ['Create Resource',MODx.action['resource/create']]
                ,['Update Resource',MODx.action['resource/update']]
            ]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
        ,preventRender: true
        ,forceSelection: true
        ,enableKeyEvents: true
    });
    MODx.combo.FCAction.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.FCAction,MODx.combo.ComboBox);
Ext.reg('modx-combo-fc-action',MODx.combo.FCAction);