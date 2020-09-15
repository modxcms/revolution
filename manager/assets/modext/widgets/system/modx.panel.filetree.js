/**
 * Light container with all available media sources trees
 *
 * @class MODx.panel.FileTree
 * @extends Ext.Container
 * @param {Object} config
 * @xtype modx-panel-filetree
 */
MODx.panel.FileTree = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        _treePrefix: 'source-tree-'
        ,autoHeight: true
        ,defaults: {
            autoHeight: true
            ,border: false
        }
    });
    MODx.panel.FileTree.superclass.constructor.call(this, config);
    this.on('render', this.getSourceList, this);
};
Ext.extend(MODx.panel.FileTree, Ext.Container, {
    /**
     * Query the media sources list
     */
    getSourceList: function() {
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'source/getList'
                ,limit: 0
            }
            ,listeners: {
                success: {
                    fn: function(data) {
                        this.onSourceListReceived(data.results);
                    }
                    ,scope:this
                }
                ,failure: {
                    fn: function(data) {
                        // Check if this really is an error
                        if (data.total > 0 && data.results != undefined) {
                            this.onSourceListReceived(data.results);
                        }
                        return false;
                    }
                    ,scope: this
                }
            }
        })
    }

    /**
     * Iterate over the given media sources list to add their trees
     *
     * @param {Array} sources
     */
    ,onSourceListReceived: function(sources) {
        for (var k = 0; k < sources.length; k++) {
            var source = sources[k]
                ,exists = this.getComponent(this._treePrefix + source.id);

            if (!exists) {
                var tree = this.loadTree(source);
            }

            this.add(tree);
            tree = exists = void 0;
        }
        this.doLayout();
    }

    /**
     * Load the tree configuration for the given media source
     *
     * @param {Object} source
     * @returns {Object}
     */
    ,loadTree: function(source) {
        var params = {};
        if (location.search) {
            var parts = location.search.substring(1).split('&');

            for (var i = 0; i < parts.length; i++) {
                var nv = parts[i].split('=');
                if (!nv[0]) continue;
                params[nv[0]] = nv[1] || true;
            }
        }
        var activeSource = params.source,
            expandSource = false;

        if (source.id == activeSource) {
            expandSource = true;
        }

        return MODx.load({
            xtype: 'modx-tree-directory'
            ,autoExpandRoot: expandSource
            ,itemId: this._treePrefix + source.id
            ,stateId: this._treePrefix + source.id
            ,id: this._treePrefix + source.id
            ,cls: source.cls || ''
            ,rootName: source.name
            ,rootQtip: source.description || ''
            ,hideSourceCombo: true
            ,source: source.id
            ,rootIconCls: source.iconCls || ''
            ,tbar: false
            ,tbarCfg: {
                hidden: true
            }
        });
    }
});
Ext.reg('modx-panel-filetree', MODx.panel.FileTree);

