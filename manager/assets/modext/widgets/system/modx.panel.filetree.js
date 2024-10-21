/**
 * Light container with all available media sources trees
 *
 * @class MODx.panel.FileTree
 * @extends Ext.Container
 * @param {Object} config
 * @xtype modx-panel-filetree
 */
MODx.panel.FileTree = function(config = {}) {
    Ext.applyIf(config, {
        _treePrefix: 'source-tree-',
        autoHeight: true,
        defaults: {
            autoHeight: true,
            border: false
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
            url: MODx.config.connector_url,
            params: {
                action: 'Source/GetList',
                limit: 0
            },
            listeners: {
                success: {
                    fn: function(data) {
                        this.onSourceListReceived(data.results);
                    },
                    scope: this
                },
                failure: {
                    fn: function(data) {
                        // Check if this really is an error
                        if (data.total > 0 && data.results !== undefined) {
                            this.onSourceListReceived(data.results);
                        }
                        return false;
                    },
                    scope: this
                }
            }
        });
    },

    /**
     * Iterate over the given media sources list to add their trees
     *
     * @param {Array} sources
     */
    onSourceListReceived: function(sources) {
        sources.forEach(source => {
            const
                sourceTreeExists = this.getComponent(this._treePrefix + source.id),
                newSourceTree = !sourceTreeExists ? this.loadTree(source) : false
            ;
            if (newSourceTree) {
                this.add(newSourceTree);
            }
        });
        this.doLayout();
    },

    /**
     * Load the tree configuration for the given media source
     *
     * @param {Object} source
     * @returns {Object}
     */
    loadTree: function(source) {
        let expandSource = false;
        if (window.location.search) {
            const params = MODx.util.UrlParams.get();
            if (Object.hasOwn(params, 'source')) {
                expandSource = source.id === params.source;
            }
        }
        return MODx.load({
            xtype: 'modx-tree-directory',
            autoExpandRoot: expandSource,
            itemId: this._treePrefix + source.id,
            stateId: this._treePrefix + source.id,
            id: this._treePrefix + source.id,
            cls: source.cls || '',
            rootName: source.name,
            rootQtip: source.description || '',
            hideSourceCombo: true,
            source: source.id,
            rootIconCls: source.iconCls || '',
            tbar: false,
            tbarCfg: {
                hidden: true
            }
        });
    }
});
Ext.reg('modx-panel-filetree', MODx.panel.FileTree);
