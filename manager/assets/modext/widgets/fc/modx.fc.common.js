
MODx.combo.FCAction = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [
                [_('fc.action_create'),'Resource/Create']
                ,[_('fc.action_update'),'Resource/Update']
                ,[_('fc.action_resource_wildcard'),'resource/*']
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
