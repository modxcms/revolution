MODx.panel.FileTree = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        border:0,
        padding:0,
        margin:0,
        id: 'modx-leftbar-filetree',
        listeners: {
            render: {fn: this.getSourceList,scope:this}
        }
    });
    MODx.panel.FileTree.superclass.constructor.call(this,config);

 //   this.on('render',this.o)
};
Ext.extend(MODx.panel.FileTree,MODx.FormPanel,{
    sourceTrees: []

    ,getSourceList: function(){
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
    }

    ,onSourceListReceived: function(sources){
        for(var k=0;k<sources.length;k++){
            var source = sources[k];
            if(!this.sourceTrees[source.name]){
                this.sourceTrees[source.name] = MODx.load({
                    xtype: 'modx-tree-directory'
                    ,id: 'source-tree-' + source.id
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
        this.doLayout();
      //  this.render();
    }
});
Ext.reg('modx-panel-filetree',MODx.panel.FileTree);

