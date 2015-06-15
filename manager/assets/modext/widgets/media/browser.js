/**
 * This is an attempt to extract the MODx.Browser.Window as a whole "component/page"
 *
 * @param {Object} config
 *
 * @extends Ext.Container
 * @xtype modx-media-view
 */
MODx.Media = function(config) {
    config = config || {};

    this.ident = config.ident || Ext.id();

    // DataView
    this.view = MODx.load({
        xtype: 'modx-browser-view'
        ,source: config.source || MODx.config.default_media_source
        ,allowedFileTypes: config.allowedFileTypes || ''
        ,wctx: config.wctx || 'web'
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,id: this.ident+'-view'
    });

    // Hide the "new window" toolbar button
    MODx.browserOpen = true;
    // Tree navigation
    this.tree = MODx.load({
        xtype: 'modx-tree-directory'
        ,onUpload: function() {
            this.view.run();
        }
        ,scope: this
        ,source: config.source || MODx.config.default_media_source
        ,hideFiles: config.hideFiles || false
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,rootId: config.rootId || '/'
        ,rootName: _('files')
        ,rootVisible: config.rootVisible == undefined || !Ext.isEmpty(config.rootId)
        ,id: this.ident+'-tree'
        ,hideSourceCombo: config.hideSourceCombo || false
        ,useDefaultToolbar: false
        ,listeners: {
            afterUpload: {
                fn: function() {
                    this.view.run();
                }
                ,scope: this
            }
            ,changeSource: {
                fn: function(s) {
                    this.config.source = s;
                    this.view.config.source = s;
                    this.view.baseParams.source = s;
                    this.view.dir = '/';
                    this.view.run();
                }
                ,scope: this
            }
            ,nodeclick: {
                fn: function(n, e) {
                    n.select();
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
                ,scope: this
            }
            ,afterrender: {
                fn: function(tree) {
                    tree.root.expand();
                }
                ,scope: this
            }
        }
    });
    this.tree.on('click', function(node, e) {
        this.load(node.id);
    }, this);

    Ext.applyIf(config, {
        cls: 'modx-browser container'
        ,layout: 'border'
        ,width: '98%'
        ,height: '95%'
        ,items: [{
            region: 'west'
            ,width: 250
            ,items: this.tree
            ,id: this.ident+'-browser-tree'
            ,cls: 'modx-browser-tree shadowbox'
            ,autoScroll: true
            ,split: true
        },{
            region: 'center'
            ,layout: 'fit'
            ,items: this.view
            ,id: this.ident+'-browser-view'
            ,cls: 'modx-browser-view-ct'
            ,autoScroll: true
            ,border: false
            ,tbar: this.getToolbar()
        },{
            region: 'east'
            ,width: 250
            ,id: this.ident+'-img-detail-panel'
            ,cls: 'modx-browser-details-ct'
            ,split: true
            //,collapsed: true
        }]
    });
    MODx.Media.superclass.constructor.call(this, config);
    this.config = config;
};
Ext.extend(MODx.Media, Ext.Container, {
    returnEl: null

    /**
     * Filter the DataView results
     */
    ,filter : function() {
        var filter = Ext.getCmp(this.ident+'filter');
        this.view.store.filter('name', filter.getValue(), true);
        this.view.select(0);
    }

    ,setReturn: function(el) {
        // @todo make sure this is never used
        console.log('MODx.Media#setReturn', el);
        this.returnEl = el;
    }

    /**
     * Load the given directory in the DataView
     *
     * @param {String} dir
     */
    ,load: function(dir) {
        dir = dir || (Ext.isEmpty(this.config.openTo) ? '' : this.config.openTo);
        this.view.run({
            dir: dir
            ,source: this.config.source
            ,allowedFileTypes: this.config.allowedFileTypes || ''
            ,wctx: this.config.wctx || 'web'
        });
        this.sortStore();
    }

    /**
     * Sort the DataView results
     */
    ,sortStore: function(){
        var v = Ext.getCmp(this.ident+'sortSelect').getValue();
        this.view.store.sort(v, v == 'name' ? 'asc' : 'desc');
        this.view.select(0);
    }

    /**
     * Switch viewmode
     */
    ,changeViewmode: function() {
        var v = Ext.getCmp(this.ident+'viewSelect').getValue();
        this.view.setTemplate(v);
        this.view.select(0);
    }

    /**
     * Remove any filter applied to the DataView
     */
    ,reset: function() {
        if (this.rendered) {
            Ext.getCmp(this.ident+'filter').reset();
            this.view.getEl().dom.scrollTop = 0;
        }
        this.view.store.clearFilter();
        this.view.select(0);
    }

    /**
     * Get the tree toolbar configuration
     *
     * @returns {Array}
     */
    ,getToolbar: function() {
        return [{
            text: _('filter')+':'
            ,xtype: 'label'
        },{
            xtype: 'textfield'
            ,id: this.ident+'filter'
            ,selectOnFocus: true
            ,width: 200
            ,listeners: {
                render: {
                    fn: function(){
                        Ext.getCmp(this.ident+'filter').getEl().on('keyup', function() {
                            this.filter();
                        }, this, {buffer: 500});
                    }
                    ,scope: this
                }
            }
        },{
            text: _('sort_by')+':'
            ,xtype: 'label'
        },{
            id: this.ident+'sortSelect'
            ,xtype: 'combo'
            ,typeAhead: true
            ,triggerAction: 'all'
            ,width: 100
            ,editable: false
            ,mode: 'local'
            ,displayField: 'desc'
            ,valueField: 'name'
            ,lazyInit: false
            ,value: MODx.config.modx_browser_default_sort || 'name'
            ,store: new Ext.data.SimpleStore({
                fields: ['name', 'desc'],
                data : [
                    ['name', _('name')]
                    ,['size', _('file_size')]
                    ,['lastmod', _('last_modified')]
                ]
            })
            ,listeners: {
                select: {
                    fn: this.sortStore
                    ,scope: this
                }
            }
        }, '-', {
            text: _('files_viewmode')+':'
            ,xtype: 'label'
        }, '-', {
            id: this.ident+'viewSelect'
            ,xtype: 'combo'
            ,typeAhead: false
            ,triggerAction: 'all'
            ,width: 100
            ,editable: false
            ,mode: 'local'
            ,displayField: 'desc'
            ,valueField: 'type'
            ,lazyInit: false
            ,value: MODx.config.modx_browser_default_viewmode || 'grid'
            ,store: new Ext.data.SimpleStore({
                fields: ['type', 'desc'],
                data : [['grid', _('files_viewmode_grid')],['list', _('files_viewmode_list')]]
            })
            ,listeners: {
                'select': {fn:this.changeViewmode, scope:this}
            }
        }];
    }
});
Ext.reg('modx-media-view', MODx.Media);
