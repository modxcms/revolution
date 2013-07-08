MODx.panel.FileTree = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        border:0,
        padding:0,
        margin:0,
        id: 'modx-leftbar-filetree'
    });
    MODx.panel.FileTree.superclass.constructor.call(this,config);

    MODx.Ajax.request({
        url: MODx.config.connectors_url
        ,params: {
            action: 'source/getList',
                 alan: 'god'
        }
        ,listeners: {
            success: {fn: function(){
                    console.log('data received');
                },scope:this},
            failure: {fn: function(){
                    console.error('FAIL',arguments);
                    return false;
                },scope: this}
        }
    })
};
Ext.extend(MODx.panel.FileTree,MODx.FormPanel,{

});
Ext.reg('modx-panel-filetree',MODx.panel.FileTree);

