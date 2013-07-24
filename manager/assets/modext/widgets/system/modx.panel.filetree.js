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
            action: 'source/getList'
        }
        ,listeners: {
            success: {fn: function(data){
                    console.log('data received');
                    this.onSourceListReceived(data.results);
                },scope:this},
            failure: {fn: function(data){
                    // Check if this really is an error
                    if(data.total > 0 && data.results != undefined){
                        this.onSourceListReceived(data.results);
                    }
                    return false;
                },scope: this}
        }
    })
};
Ext.extend(MODx.panel.FileTree,MODx.FormPanel,{

    sourceTrees: []


    ,onSourceListReceived: function(sources){

        for(var k=0;k<sources.length;k++){
            var source = sources[k];
            if(!this.sourceTrees[source.name]){
                this.sourceTrees[source.name] = MODx.load({
                    xtype: 'modx-tree-directory'
                    ,rootName: source.name
                    ,hideSourceCombo: true
                    ,source: source.id
                    ,tbar: false
                    ,tbarCfg: {
                        hidden: true
                    }
                })
            }

            this.add(this.sourceTrees[source.name]);
        }

        this.render();
    }



});
Ext.reg('modx-panel-filetree',MODx.panel.FileTree);

