MODx.Layout.Default = function(config,getStore) {
    config = config || {};
    Ext.applyIf(config,{
    });

    MODx.Layout.Default.superclass.constructor.call(this,config);
    return this;
};
Ext.extend(MODx.Layout.Default,MODx.Layout);
Ext.reg('modx-layout',MODx.Layout.Default);