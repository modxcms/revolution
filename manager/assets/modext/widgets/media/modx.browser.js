Ext.namespace('MODx.browser');

MODx.Browser = function(config = {}) {
    if (MODx.browserOpen && !config.multiple) {
        return false;
    }
    if (!config.multiple) {
        MODx.browserOpen = true;
    }
    Ext.applyIf(config, {
        onSelect: function(data) {},
        scope: this,
        source: config.source || 1,
        cls: 'modx-browser',
        closeAction: 'hide'
    });
    MODx.Browser.superclass.constructor.call(this, config);
    this.config = config;

    this.win = new MODx.browser.Window(config);
    this.win.reset();
};
Ext.extend(MODx.Browser, Ext.Component, {
    show: function(el) {
        if (this.win) {
            this.win.show(el);
        }
    },
    hide: function() {
        if (this.win) {
            this.win.hide();
        }
    },
    setSource: function(source) {
        this.config.source = source;
        this.win.tree.config.baseParams.source = source;
        this.win.view.config.baseParams.source = source;
    }

});
Ext.reg('modx-browser', MODx.Browser);

MODx.browser.View = function(config = {}) {
    this.ident = `${config.ident}-view` || `modx-browser-${Ext.id()}-view`;

    this._initTemplates();

    Ext.applyIf(config, {
        url: MODx.config.connector_url,
        id: this.ident,
        fields: [
            { name: 'name', sortType: Ext.data.SortTypes.asUCString },
            'cls',
            'url',
            'relativeUrl',
            'fullRelativeUrl',
            'image',
            'original_width',
            'original_height',
            'image_width',
            'image_height',
            'thumb',
            'thumb_width',
            'thumb_height',
            'pathname',
            'pathRelative',
            'ext',
            'disabled',
            'preview',
            { name: 'size', type: 'float' },
            'lastmod',
            'lastmod_raw',
            'menu', 'visibility'
        ],
        baseParams: {
            action: 'Browser/Directory/GetFiles',
            prependPath: config.prependPath || null,
            prependUrl: config.prependUrl || null,
            source: config.source || 1,
            // @todo: this overrides the media source configuration
            allowedFileTypes: config.allowedFileTypes || '',
            wctx: config.wctx || 'web',
            dir: config.openTo || ''
        },
        tpl: MODx.config.modx_browser_default_viewmode === 'list' ? this.templates.list : this.templates.thumb,
        itemSelector: MODx.config.modx_browser_default_viewmode === 'list' ? 'div.modx-browser-list-item' : 'div.modx-browser-thumb-wrap',
        thumbnails: [],
        lazyLoad: function() {
            const height = this.getEl().parent().getHeight() + 100;
            for (let i = 0; i < this.thumbnails.length; i++) {
                const image = this.thumbnails[i];
                if (image !== undefined) {
                    const rect = image.getBoundingClientRect();
                    if (rect.top >= 0 && rect.left >= 0 && rect.top <= height) {
                        image.src = image.getAttribute('data-src');
                        delete (this.thumbnails[i]);
                    }
                }
            }
        },
        refresh: function() {
            MODx.DataView.prototype.refresh.call(this);
            this.thumbnails = Array.prototype.slice.call(document.querySelectorAll('img[data-src]'));
            this.lazyLoad();
        },
        listeners: {
            selectionchange: {
                fn: this.showDetails,
                scope: this,
                buffer: 100
            },
            dblclick: config.onSelect || {
                fn: Ext.emptyFn,
                scope: this
            },
            render: {
                fn: this.sortStore,
                scope: this
            },
            afterrender: {
                fn: function() {
                    this.getEl().parent().on('scroll', function() {
                        this.lazyLoad();
                    }, this);
                    if (this.tree !== undefined && this.tree.uploader !== undefined) {
                        this.tree.uploader.addDropZone(this.ownerCt, this);
                    }
                    MODx.config.browserview = this;
                },
                scope: this
            }
        },
        prepareData: this.formatData.createDelegate(this),
        multiSelect: true
    });
    MODx.browser.View.superclass.constructor.call(this, config);
};
Ext.extend(MODx.browser.View, MODx.DataView, {
    templates: {},

    run: function(params = {}) {
        if (params.dir) {
            this.dir = params.dir;
        }
        Ext.applyIf(params, {
            action: 'Browser/Directory/GetFiles',
            dir: this.dir,
            source: this.config.source || MODx.config.default_media_source
        });
        this.mask = new Ext.LoadMask(Ext.getBody(), { msg: _('loading') });
        this.mask.show();
        this.store.load({
            params: params,
            callback: function(rec, options, success) {
                this.mask.hide();
                this.refresh();
                // reset the bottom filepath bar
                Ext.getCmp(`${this.ident}-filepath`).setValue('');

                this.select(0);
            },
            scope: this
        });
    },

    editFile: function(item, e) {
        const
            node = this.cm.activeNode,
            data = this.lookup[node.id]
        ;
        MODx.loadPage('system/file/edit', `file=${data.pathRelative}&source=${this.config.source}`);
    },

    quickUpdateFile: function(item, e) {
        const
            node = this.cm.activeNode,
            data = this.lookup[node.id]
        ;
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'Browser/File/Get',
                file: data.pathRelative,
                wctx: MODx.ctx || '',
                source: this.config.source
            },
            listeners: {
                success: {
                    fn: function(response) {
                        const
                            record = {
                                file: data.pathRelative,
                                name: data.name,
                                path: decodeURIComponent(data.pathRelative),
                                source: this.config.source,
                                content: response.object.content
                            },
                            window = MODx.load({
                                xtype: 'modx-window-file-quick-update',
                                record: record,
                                listeners: {
                                    hide: {
                                        fn: function() {
                                            this.destroy();
                                        }
                                    }
                                }
                            })
                        ;
                        window.show(e.target);
                    },
                    scope: this
                }
            }
        });
    },

    renameFile: function(item, e) {
        const
            node = this.cm.activeNode,
            data = this.lookup[node.id],
            record = {
                old_name: data.name,
                name: data.name,
                path: data.pathRelative,
                source: this.config.source
            },
            window = MODx.load({
                xtype: 'modx-window-file-rename',
                record: record,
                listeners: {
                    success: {
                        fn: function(response) {
                            this.config.tree.refreshParentNode();
                            this.run();
                        },
                        scope: this
                    },
                    hide: {
                        fn: function() {
                            this.destroy();
                        }
                    }
                }
            })
        ;
        window.show(e.target);
    },

    downloadFile: function(item, e) {
        const
            node = this.cm.activeNode,
            data = this.lookup[node.id]
        ;
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'Browser/File/Download',
                file: data.pathRelative,
                wctx: MODx.ctx || '',
                source: this.config.source
            },
            listeners: {
                failure: {
                    fn: function(response) {
                        MODx.msg.alert(_('alert'), response.message);
                    },
                    scope: this
                },
                success: {
                    fn: function(response) {
                        if (!Ext.isEmpty(response.object.url)) {
                            window.location.href = `${MODx.config.connector_url}?action=Browser/File/Download&download=1&file=${response.object.url}&HTTP_MODAUTH=${MODx.siteId}&source=${this.config.source}&wctx=${MODx.ctx}`;
                        }
                    },
                    scope: this
                }
            }
        });
    },

    copyRelativePath: function(item, e) {
        const
            node = this.cm.activeNode,
            data = this.lookup[node.id],
            dummyRelativePathInput = document.createElement('input')
        ;
        document.body.appendChild(dummyRelativePathInput);
        dummyRelativePathInput.setAttribute('value', data.pathRelative);
        dummyRelativePathInput.select();
        document.execCommand('copy');
        document.body.removeChild(dummyRelativePathInput);
    },

    removeFile: function() {
        const
            files = [],
            filesNames = [],
            selected = this.getSelectedRecords()
        ;
        selected.forEach(file => {
            if (Object.hasOwn(file, 'id') && !Ext.isEmpty(file.id)) {
                files.push(file.id);
                filesNames.push(file.data.name);
            }
        });

        MODx.msg.confirm({
            text: _('file_remove_confirm', {
                file: filesNames.pop()
            }),
            url: MODx.config.connector_url,
            params: {
                action: 'Browser/File/Remove',
                file: files.pop(),
                source: this.config.source,
                wctx: this.config.wctx || 'web'
            },
            listeners: {
                success: {
                    fn: function(r) {
                        if (this.config.tree) {
                            if (this.config.tree.cm.activeNode && this.config.tree.cm.activeNode.id.match(/.*?\/$/)) {
                                this.config.tree.refreshParentNode();
                            } else {
                                this.config.tree.refresh();
                            }
                        }
                        this.run();
                    },
                    scope: this
                }
            }
        });
    },

    setTemplate: function(tpl) {
        if (tpl === 'list') {
            this.tpl = this.templates.list;
            this.itemSelector = 'div.modx-browser-list-item';
        } else {
            this.tpl = this.templates.thumb;
            this.itemSelector = 'div.modx-browser-thumb-wrap';
        }
        this.refresh();
        this.select(0);
    },

    setVisibility: function(item, e) {
        const
            node = this.cm.activeNode,
            data = this.lookup[node.id],
            record = {
                path: decodeURIComponent(data.pathRelative),
                visibility: data.visibility,
                source: this.config.source
            },
            window = MODx.load({
                xtype: 'modx-window-set-visibility',
                record: record,
                listeners: {
                    success: {
                        fn: function() {
                            this.run();
                        },
                        scope: this
                    },
                    hide: {
                        fn: function() {
                            this.destroy();
                        }
                    }
                }
            })
        ;
        window.show(e.target);
    },

    sortStore: function() {
        const sortBy = MODx.config.modx_browser_default_sort || 'name';
        this.store.sort(sortBy, sortBy === 'name' ? 'ASC' : 'DESC');
        this.select(0);
    },

    showDetails: function() {
        const
            node = this.getSelectedNodes(),
            detailPanel = Ext.getCmp(`${this.config.ident}-img-detail-panel`).body,
            okBtn = Ext.getCmp(`${this.ident}-ok-btn`),
            keys = Object.keys(node)
        ;
        if (node && node.length > 0) {
            const selectedNode = node[keys[keys.length - 1]];
            if (okBtn) {
                okBtn.enable();
            }
            const data = this.lookup[selectedNode.id];
            // sync the selected file in browser view and tree
            // we have to take care of the tree loosing sync after a file is deleted
            // and this.config.tree.getNodeById(data.pathRelative) being undefined
            if (this.config.tree.getNodeById(data.pathRelative)) {
                // this is necessary to prevent the whole tree from refreshing
                // e.g. like this we set the correct activeNode which is then used to determine the parent node
                this.config.tree.cm.activeNode = this.config.tree.getNodeById(data.pathRelative);
                // and this to have the visual syncing of selected items in browser view and tree
                this.config.tree.getSelectionModel().select(this.config.tree.getNodeById(data.pathRelative));
            }
            // keeps the bottom filepath bar in sync with the selected file
            Ext.getCmp(`${this.ident}-filepath`).setValue((data.fullRelativeUrl.indexOf('http') === -1 ? '/' : '') + data.fullRelativeUrl);

            detailPanel.hide();
            this.templates.details.overwrite(detailPanel, data);
            detailPanel.slideIn('l', { stopFx: true, duration: '0.2' });
        } else {
            if (okBtn) {
                okBtn.disable();
            }
            detailPanel.update('');
        }
    },

    /**
     * Shows enlarged preview of an image file in a new overlay window
     * @param {String} name Image file name
     * @param {String} ident The id of the active media browser instance
     * @returns void
     */
    showFullView: function(name, ident) {
        const data = this.lookup[name];
        if (!data) {
            return;
        }
        if (!this.fullViewWindow) {
            this.fullViewWindow = new Ext.Window({
                layout: 'fit',
                width: 600,
                height: 450,
                bodyStyle: 'padding: 0;',
                closeAction: 'hide',
                plain: true,
                items: [{
                    id: `${this.ident}modx-view-item-full`,
                    cls: 'modx-browser-fullview',
                    html: ''
                }],
                buttons: [{
                    text: _('close'),
                    cls: 'primary-button',
                    handler: function() { this.fullViewWindow.hide(); },
                    scope: this
                }]
            });
        }
        this.fullViewWindow.show();

        let
            imageWidth = data.image_width,
            imageHeight = data.image_height
        ;
        const
            ratio = imageWidth > 800 ? 800 / imageWidth : 1,
            // hfit: +1 for the borders
            hfit = (imageHeight * ratio)
                + this.fullViewWindow.footer.dom.clientHeight + 1
                + this.fullViewWindow.header.dom.clientHeight + 1
        ;

        if (imageWidth < 250 || imageWidth > 800) {
            imageWidth = imageWidth < 250 ? 250 : 800 ;
        }
        if (imageHeight < 200 || imageHeight > 600) {
            if (imageHeight > 600) {
                imageHeight = hfit > 600 ? 600 : hfit ;
            } else {
                imageHeight = 200;
            }
        }
        this.fullViewWindow.setSize(imageWidth, imageHeight);
        this.fullViewWindow.center();
        this.fullViewWindow.setTitle(data.name);
        Ext
            .get(`${this.ident}modx-view-item-full`)
            .update(`
                <img
                    src="${data.image}"
                    loading="lazy"
                    width="${imageWidth}"
                    height="${imageHeight}"
                    alt="${data.name}"
                    title="${data.name}"
                    class="modx-browser-fullview-img"
                    onclick="Ext.getCmp('${ident}').fullViewWindow.hide();"
                >
            `)
        ;
    },

    formatData: function(data) {
        const formatSize = function(size) {
            if (size < 1024) {
                return `${size} ${_('file_size_bytes')}`;
            }
            return `${Math.round(((size * 10) / 1024)) / 10} ${_('file_size_kilobytes')}`;
        };
        data.shortName = Ext.util.Format.ellipsis(data.name, 18);
        data.sizeString = data.size !== 0 ? formatSize(data.size) : 0;
        data.imageSizeString = data.preview !== 0 ? `${data.original_width}x${data.original_height}px` : 0;
        data.imageSizeString = data.imageSizeString === 'xpx' ? 0 : data.imageSizeString;
        this.lookup[data.name] = data;
        return data;
    },

    _initTemplates: function() {
        this.templates.thumb = new Ext.XTemplate(`
            <tpl for=".">
            <div class="modx-browser-thumb-wrap" id="{name:htmlEncode}" title="{name:htmlEncode}">
                <tpl if="preview === 1">
                <div class="modx-browser-thumb">
                    <img
                        src="data:image/gif;base64R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                        data-src="{thumb:htmlEncode}"
                        loading="lazy"
                        width="{thumb_width}"
                        height="{thumb_height}"
                        alt="{name:htmlEncode}"
                        title="{name:htmlEncode}"
                    >
                </div>
                </tpl>
                <tpl if="preview === 0">
                <div class="modx-browser-thumb">
                    <div class="modx-browser-placeholder">.{ext}</div>
                </div>
                </tpl>
                <span>{shortName:htmlEncode}</span>
            </div>
            </tpl>
        `);
        this.templates.thumb.compile();

        this.templates.list = new Ext.XTemplate(`
            <tpl for=".">
            <div class="modx-browser-list-item" id="{name:htmlEncode}">
                <span class="icon icon-file {cls}">
                    <span class="file-name">{name:htmlEncode}</span>
                    <tpl if="sizeString !== 0">
                        <span class="file-size">{sizeString}</span>
                    </tpl>
                    <tpl if="imageSizeString !== 0">
                        <span class="image-size">{imageSizeString}</span>
                    </tpl>
                </span>
            </div>
            </tpl>
        `);
        this.templates.list.compile();

        this.templates.details = new Ext.XTemplate(`
            <div class="details">
            <tpl for=".">
                <tpl if="preview === 1">
                <div class="modx-browser-detail-thumb preview" onclick="Ext.getCmp('${this.ident}').showFullView('{name:htmlEncode}','${this.ident}'); return false;">
                    <img
                        src="{image:htmlEncode}"
                        loading="lazy"
                        width="{image_width}"
                        height="{image_height}"
                        alt="{name:htmlEncode}"
                        title="{name:htmlEncode}"
                    >
                </div>
                </tpl>
                <tpl if="preview === 0">
                <div class="modx-browser-detail-thumb">
                    <div class="modx-browser-placeholder">.{ext}</div>
                </div>
                </tpl>
                <div class="modx-browser-details-info">
                    <strong>${_('file_name')}:</strong>
                    <span>{name:htmlEncode}</span>
                    <tpl if="sizeString !== 0">
                        <strong>${_('file_size')}:</strong>
                        <span>{sizeString}</span>
                    </tpl>
                    <tpl if="imageSizeString !== 0">
                        <strong>${_('image_size')}:</strong>
                        <span>{imageSizeString}</span>
                    </tpl>
                    <tpl if="lastmod !== 0">
                        <strong>${_('file_last_modified')}:</strong>
                        <span>{lastmod}</span>
                    </tpl>
                    <tpl if="visibility">
                        <strong>${_('visibility')}:</strong>
                        <span>{visibility}</span>
                    </tpl>
                </div>
            </tpl>
            </div>
        `);
        this.templates.details.compile();
    },

    _showContextMenu: function(v, i, n, e) {
        e.preventDefault();
        this.select(n.id);
        const
            data = this.lookup[n.id],
            m = this.cm
        ;
        m.removeAll();
        if (data.menu) {
            const
                selected = this.getSelectedRecords(),
                menu = selected.length > 1 ? [] : data.menu
            ;
            if (selected.length > 1) {
                data.menu.forEach(item => {
                    if (item.handler !== undefined && item.handler.match(/removeFile/i)) {
                        menu.push(item);
                    }
                });
            }
            if (!menu.length) {
                return false;
            }
            this._addContextMenuItem(menu);
            m.showAt(e.xy);
        }
        m.activeNode = n;
    }
});
Ext.reg('modx-browser-view', MODx.browser.View);

/**
 * This is the regular media browser window that opens when clicking on an image or file TV for example
 */
MODx.browser.Window = function(config = {}) {
    this.ident = Ext.id();

    // Hide the "MODX Browser" toolbar button
    MODx.browserOpen = true;

    // Tree navigation
    this.tree = MODx.load({
        xtype: 'modx-tree-directory',
        onUpload: function() {
            this.view.run();
        },
        scope: this,
        source: config.source || MODx.util.UrlParams.get().source || MODx.config.default_media_source,
        originalSource: config.source || MODx.config.default_media_source,
        hideFiles: config.hideFiles || MODx.config.modx_browser_tree_hide_files,
        hideTooltips: config.hideTooltips || MODx.config.modx_browser_tree_hide_tooltips || true, // by default do not request image preview tooltips in the media browser
        openTo: config.openTo || MODx.util.UrlParams.get().dir || '',
        ident: this.ident,
        rootIconCls: MODx.config.mgr_source_icon,
        rootId: config.rootId || '/',
        rootName: _('files'),
        rootVisible: config.rootVisible === undefined || !Ext.isEmpty(config.rootId),
        id: `${this.ident}-tree`,
        hideSourceCombo: config.hideSourceCombo || false,
        useDefaultToolbar: false,
        listeners: {
            afterUpload: {
                fn: function() {
                    this.view.run();
                },
                scope: this
            },
            afterQuickCreate: {
                fn: function() {
                    this.view.run();
                },
                scope: this
            },
            afterRename: {
                fn: function() {
                    this.view.run();
                },
                scope: this
            },
            afterRemove: {
                fn: function() {
                    this.view.run();
                },
                scope: this
            },
            changeSource: {
                fn: function(s) {
                    this.config.source = s;
                    this.view.config.source = s;
                    this.view.baseParams.source = s;
                    this.view.dir = '/';
                    this.view.run();
                    MODx.util.UrlParams.set({ ...MODx.util.UrlParams.get(), source: s, dir: '/' });
                },
                scope: this
            },
            afterrender: {
                fn: function(tree) {
                    tree.root.expand();
                },
                scope: this
            },
            beforeclick: {
                fn: function(node, e) {
                    // load the node/folder that is clicked on but prevent unnecessary requests when a file is clicked
                    if (!node.leaf) {
                        this.load(node.id);
                    } else {
                        // sync the selected item in the tree with the one in browser view
                        // the id of a browser view node in the store is the full absolute URL
                        // but there is a bug with urlAbsolute, see #11821 that's why we prepend a slash
                        this.view.select(this.view.store.indexOfId(`/${node.attributes.url}`));
                        // but instead load the container the file resides in if not already displayed
                        if (this.view.dir !== node.parentNode.id) {
                            this.load(node.parentNode.id);
                        }
                        return false;
                    }
                },
                scope: this
            },
            load: {
                fn: function() {
                    const { dir } = MODx.util.UrlParams.get();
                    if (dir) {
                        this.tree.expandTreePath(dir);
                    }
                },
                scope: this
            }
        }
    });

    // DataView
    this.view = MODx.load({
        xtype: 'modx-browser-view',
        onSelect: {
            fn: this.onSelect,
            scope: this
        },
        source: config.source || MODx.util.UrlParams.get().source || MODx.config.default_media_source,
        allowedFileTypes: config.allowedFileTypes || '',
        wctx: config.wctx || 'web',
        openTo: config.openTo || MODx.util.UrlParams.get().dir || '',
        multiSelect: config.multiSelect || false,
        ident: this.ident,
        id: `${this.ident}-view`,
        tree: this.tree
    });

    // Add event to reload on History change
    window.onpopstate = e => {
        MODx.browser.onPopState(e, this);
    };

    Ext.applyIf(config, {
        title: `${_('modx_browser')} (${MODx.ctx ? MODx.ctx : 'web'})`,
        cls: 'modx-browser modx-browser-window',
        layout: 'border',
        minWidth: 500,
        minHeight: 300,
        width: '90%',
        height: Ext.getBody().getViewSize().height * 0.9,
        modal: false,
        closeAction: 'hide',
        border: false,
        items: [{
            id: `${this.ident}-browser-tree`,
            cls: 'modx-browser-tree',
            region: 'west',
            width: 250,
            height: '100%',
            items: this.tree,
            autoScroll: true,
            split: true,
            border: false
        }, {
            id: `${this.ident}-browser-view`,
            cls: 'modx-browser-view-ct',
            region: 'center',
            autoScroll: true,
            border: false,
            items: this.view,
            tbar: this.getToolbar(),
            bbar: this.getPathbar()
        }, {
            id: `${this.ident}-img-detail-panel`,
            cls: 'modx-browser-details-ct',
            region: 'east',
            split: true,
            border: false,
            width: 250
        }],
        buttons: [{
            id: `${this.ident}-cancel-btn`,
            text: _('cancel'),
            handler: this.close,
            scope: this
        }, {
            id: `${this.ident}-ok-btn`,
            text: _('ok'),
            cls: 'primary-button',
            handler: this.onSelect,
            scope: this
        }],
        keys: {
            key: 27,
            handler: this.hide,
            scope: this
        }
    });
    MODx.browser.Window.superclass.constructor.call(this, config);
    this.config = config;
    this.addEvents({
        select: true,
        hide: true
    });

    this.on('hide', () => {
        MODx.util.UrlParams.remove('source');
        MODx.util.UrlParams.remove('dir');
    });
};
Ext.extend(MODx.browser.Window, Ext.Window, {
    returnEl: null,

    /**
     * Filter the DataView results
     */
    filter: function() {
        const filter = Ext.getCmp(`${this.ident}filter`);
        this.view.store.filter('name', filter.getValue(), true);
        this.view.select(0);
    },

    /**
     * Load the given directory in the DataView
     *
     * @param {String} dir
     */
    load: function(dir) {
        dir = dir || (Ext.isEmpty(this.config.openTo) ? '' : this.config.openTo);
        MODx.util.UrlParams.set({ ...MODx.util.UrlParams.get(), dir: decodeURIComponent(dir) });

        this.view.run({
            dir: dir,
            source: this.config.source,
            allowedFileTypes: this.config.allowedFileTypes || '',
            wctx: this.config.wctx || 'web'
        });
        this.sortStore();
    },

    /**
     * Sort the DataView results
     */
    sortStore: function() {
        const sortBy = Ext.getCmp(`${this.ident}sortSelect`).getValue();
        this.view.store.sort(sortBy, sortBy === 'name' ? 'ASC' : 'DESC');
        this.view.select(0);
    },

    /**
     * Switch viewmode from grid to list and vice versa
     */
    changeViewmode: function() {
        const view = Ext.getCmp(`${this.ident}viewSelect`).getValue();
        this.view.setTemplate(view);
        this.view.select(0);
    },

    /**
     * Remove any filter applied to the DataView
     */
    reset: function() {
        if (this.rendered) {
            Ext.getCmp(`${this.ident}filter`).reset();
            this.view.getEl().dom.scrollTop = 0;
        }
        this.view.store.clearFilter();
        this.view.select(0);
    },

    /**
     * Get the browser view toolbar configuration
     *
     * @returns {Array}
     */
    getToolbar: function() {
        return [{
            text: `${_('filter')}:`,
            xtype: 'label'
        }, {
            xtype: 'textfield',
            id: `${this.ident}filter`,
            selectOnFocus: true,
            width: 200,
            listeners: {
                render: {
                    fn: function() {
                        Ext.getCmp(`${this.ident}filter`).getEl().on('keyup', function() {
                            this.filter();
                        }, this, { buffer: 500 });
                    },
                    scope: this
                }
            }
        }, {
            text: `${_('sort_by')}:`,
            xtype: 'label'
        }, {
            id: `${this.ident}sortSelect`,
            xtype: 'combo',
            typeAhead: true,
            triggerAction: 'all',
            width: 130,
            editable: false,
            mode: 'local',
            displayField: 'desc',
            valueField: 'name',
            lazyInit: false,
            value: MODx.config.modx_browser_default_sort || 'name',
            store: new Ext.data.SimpleStore({
                fields: ['name', 'desc'],
                data: [
                    ['name', _('name')],
                    ['size', _('file_size')],
                    ['lastmod_raw', _('last_modified')]
                ]
            }),
            listeners: {
                select: {
                    fn: this.sortStore,
                    scope: this
                }
            }
        }, '-', {
            text: `${_('files_viewmode')}:`,
            xtype: 'label'
        }, '-', {
            id: `${this.ident}viewSelect`,
            xtype: 'combo',
            typeAhead: false,
            triggerAction: 'all',
            width: 100,
            editable: false,
            mode: 'local',
            displayField: 'desc',
            valueField: 'type',
            lazyInit: false,
            value: MODx.config.modx_browser_default_viewmode || 'grid',
            store: new Ext.data.SimpleStore({
                fields: ['type', 'desc'],
                data: [
                    ['grid', _('files_viewmode_grid')],
                    ['list', _('files_viewmode_list')]
                ]
            }),
            listeners: {
                select: {
                    fn: this.changeViewmode,
                    scope: this
                }
            }
        }];
    },

    /**
     * Get the bottom filepath textfield in the browser view
     *
     * @returns {Array}
     */
    getPathbar: function() {
        return {
            cls: 'modx-browser-pathbbar',
            items: [{
                xtype: 'textfield',
                id: `${this.ident}-filepath`,
                cls: 'modx-browser-filepath',
                listeners: {
                    focus: {
                        // select the filepath on focus
                        fn: function(el) {
                            // let the focus event stick first, needed for webkit primarily
                            setTimeout(() => {
                                const field = el.getEl().dom;
                                if (field.createTextRange) {
                                    const selRange = field.createTextRange();
                                    selRange.collapse(true);
                                    selRange.moveStart('character', 0);
                                    selRange.moveEnd('character', field.value.length);
                                    selRange.select();
                                } else if (field.setSelectionRange) {
                                    field.setSelectionRange(0, field.value.length);
                                } else if (field.selectionStart) {
                                    field.selectionStart = 0;
                                    field.selectionEnd = field.value.length;
                                }
                            }, 50);
                        },
                        scope: this
                    }
                }
            }]
        };
    },

    setReturn: function(el) {
        this.returnEl = el;
    },

    onSelect: function() {
        const
            selNodes = this.view.getSelectedNodes(),
            callback = this.config.onSelect || this.onSelectHandler,
            { lookup } = this.view,
            { scope } = this.config,
            source = parseInt(this.config.source || MODx.config.default_media_source, 10)
        ;
        this.hide(this.config.animEl || null, function() {
            if (callback) {
                selNodes.forEach(selNode => {
                    try {
                        const selData = lookup[selNode.id];
                        selData.source = source;
                        Ext.callback(callback, scope || this, [selData]);
                        this.fireEvent('select', selData);
                    } catch (e) {
                        console.error('Error processing MODx.Browser onSelect callback:', e);
                    }
                });
            }
        }, scope);
    },

    onSelectHandler: function(data) {
        Ext.get(this.returnEl).dom.value = unescape(data.url);
    }
});
Ext.reg('modx-browser-window', MODx.browser.Window);

/**
 * This is an attempt to extract the MODx.Browser.Window as a whole "component/page" found under Media > Media Browser
 *
 * @param {Object} config
 *
 * @extends Ext.Container
 * @xtype modx-media-view
 */
MODx.Media = function(config = {}) {
    this.ident = config.ident || Ext.id();

    // Hide the "MODX Browser" toolbar button
    MODx.browserOpen = true;

    // Tree navigation
    this.tree = MODx.load({
        xtype: 'modx-tree-directory',
        onUpload: function() {
            this.view.run();
        },
        scope: this,
        source: config.source || MODx.util.UrlParams.get().source || MODx.config.default_media_source,
        originalSource: config.source || MODx.config.default_media_source,
        hideFiles: config.hideFiles || MODx.config.modx_browser_tree_hide_files,
        hideTooltips: config.hideTooltips || MODx.config.modx_browser_tree_hide_tooltips || true, // by default do not request image preview tooltips in the media browser
        openTo: config.openTo || MODx.util.UrlParams.get().dir || '',
        ident: this.ident,
        rootIconCls: MODx.config.mgr_source_icon,
        rootId: config.rootId || '/',
        rootName: _('files'),
        rootVisible: config.rootVisible === undefined || !Ext.isEmpty(config.rootId),
        id: `${this.ident}-tree`,
        hideSourceCombo: config.hideSourceCombo || false,
        useDefaultToolbar: false,
        listeners: {
            afterUpload: {
                fn: function() {
                    this.view.run();
                },
                scope: this
            },
            afterQuickCreate: {
                fn: function() {
                    this.view.run();
                },
                scope: this
            },
            afterRename: {
                fn: function() {
                    this.view.run();
                },
                scope: this
            },
            afterRemove: {
                fn: function() {
                    this.view.run();
                },
                scope: this
            },
            changeSource: {
                fn: function(s) {
                    this.config.source = s;
                    this.view.config.source = s;
                    this.view.baseParams.source = s;
                    this.view.dir = '/';
                    this.view.run();
                    MODx.util.UrlParams.set({ ...MODx.util.UrlParams.get(), source: s, dir: '/' });
                },
                scope: this
            },
            afterrender: {
                fn: function(tree) {
                    tree.root.expand();
                },
                scope: this
            },
            beforeclick: {
                fn: function(node, e) {
                    // load the node/folder that is clicked on but prevent unnecessary requests when a file is clicked
                    if (!node.leaf) {
                        this.load(node.id);
                    } else {
                        // sync the selected item in the tree with the one in browser view
                        // the id of a browser view node in the store is the full absolute URL
                        // but there is a bug with urlAbsolute, see #11821 that's why we prepend a slash
                        this.view.select(this.view.store.indexOfId(`/${node.attributes.url}`));
                        // but instead load the container the file resides in if not already displayed
                        if (this.view.dir !== node.parentNode.id) {
                            this.load(node.parentNode.id);
                        }
                        return false;
                    }
                },
                scope: this
            },
            load: {
                fn: function() {
                    const { dir } = MODx.util.UrlParams.get();
                    if (dir) {
                        this.tree.expandTreePath(dir);
                    }
                },
                scope: this
            }
        }
    });

    // DataView
    this.view = MODx.load({
        xtype: 'modx-browser-view',
        onSelect: {
            fn: this.onSelect,
            scope: this
        },
        source: config.source || MODx.util.UrlParams.get().source || MODx.config.default_media_source,
        originalSource: config.source || MODx.config.default_media_source,
        allowedFileTypes: config.allowedFileTypes || '',
        wctx: config.wctx || 'web',
        openTo: config.openTo || MODx.util.UrlParams.get().dir || '',
        ident: this.ident,
        id: `${this.ident}-view`,
        tree: this.tree
    });

    // Add event to reload on History change
    window.onpopstate = e => {
        MODx.browser.onPopState(e, this);
    };

    Ext.applyIf(config, {
        cls: 'modx-browser modx-browser-panel',
        layout: 'border',
        width: '100%',
        height: '100%',
        style: 'background-color: transparent',
        border: false,
        items: [{
            region: 'west',
            width: 250,
            items: this.tree,
            id: `${this.ident}-browser-tree`,
            cls: 'modx-browser-tree',
            autoScroll: true,
            split: true,
            margins: '10 0 10 18'
        }, {
            region: 'center',
            layout: 'fit',
            items: this.view,
            id: `${this.ident}-browser-view`,
            cls: 'modx-browser-view-ct',
            autoScroll: true,
            border: false,
            tbar: this.getToolbar(),
            bbar: this.getPathbar(),
            margins: '10 0 10 0'
        }, {
            region: 'east',
            width: 250,
            id: `${this.ident}-img-detail-panel`,
            cls: 'modx-browser-details-ct',
            split: true,
            margins: '10 10 10 0'
        }]
    });
    MODx.Media.superclass.constructor.call(this, config);
    this.config = config;
};
Ext.extend(MODx.Media, Ext.Container, {
    returnEl: null,

    /**
     * Filter the DataView results
     */
    filter: function() {
        const filter = Ext.getCmp(`${this.ident}filter`);
        this.view.store.filter('name', filter.getValue(), true);
        this.view.select(0);
    },

    /**
     * Load the given directory in the DataView
     *
     * @param {String} dir
     */
    load: function(dir) {
        dir = dir || (Ext.isEmpty(this.config.openTo) ? '' : this.config.openTo);
        MODx.util.UrlParams.set({ ...MODx.util.UrlParams.get(), dir: decodeURIComponent(dir) });

        this.view.run({
            dir: dir,
            source: this.config.source,
            allowedFileTypes: this.config.allowedFileTypes || '',
            wctx: this.config.wctx || 'web'
        });
        this.sortStore();
    },

    /**
     * Sort the DataView results
     */
    sortStore: function() {
        const sortBy = Ext.getCmp(`${this.ident}sortSelect`).getValue();
        this.view.store.sort(sortBy, sortBy === 'name' ? 'ASC' : 'DESC');
        this.view.select(0);
    },

    /**
     * Switch viewmode from grid to list and vice versa
     */
    changeViewmode: function() {
        const view = Ext.getCmp(`${this.ident}viewSelect`).getValue();
        this.view.setTemplate(view);
        this.view.select(0);
    },

    /**
     * Remove any filter applied to the DataView
     */
    reset: function() {
        if (this.rendered) {
            Ext.getCmp(`${this.ident}filter`).reset();
            this.view.getEl().dom.scrollTop = 0;
        }
        this.view.store.clearFilter();
        this.view.select(0);
    },

    /**
     * Get the browser view toolbar configuration
     *
     * @returns {Array}
     */
    getToolbar: function() {
        return [{
            text: `${_('filter')}:`,
            xtype: 'label'
        }, {
            xtype: 'textfield',
            id: `${this.ident}filter`,
            selectOnFocus: true,
            width: 200,
            listeners: {
                render: {
                    fn: function() {
                        Ext.getCmp(`${this.ident}filter`).getEl().on('keyup', function() {
                            this.filter();
                        }, this, { buffer: 500 });
                    },
                    scope: this
                }
            }
        }, {
            text: `${_('sort_by')}:`,
            xtype: 'label'
        }, {
            id: `${this.ident}sortSelect`,
            xtype: 'combo',
            typeAhead: true,
            triggerAction: 'all',
            width: 130,
            editable: false,
            mode: 'local',
            displayField: 'desc',
            valueField: 'name',
            lazyInit: false,
            value: MODx.config.modx_browser_default_sort || 'name',
            store: new Ext.data.SimpleStore({
                fields: ['name', 'desc'],
                data: [
                    ['name', _('name')],
                    ['size', _('file_size')],
                    ['lastmod_raw', _('last_modified')]
                ]
            }),
            listeners: {
                select: {
                    fn: this.sortStore,
                    scope: this
                }
            }
        }, '-', {
            text: `${_('files_viewmode')}:`,
            xtype: 'label'
        }, '-', {
            id: `${this.ident}viewSelect`,
            xtype: 'combo',
            typeAhead: false,
            triggerAction: 'all',
            width: 100,
            editable: false,
            mode: 'local',
            displayField: 'desc',
            valueField: 'type',
            lazyInit: false,
            value: MODx.config.modx_browser_default_viewmode || 'grid',
            store: new Ext.data.SimpleStore({
                fields: ['type', 'desc'],
                data: [
                    ['grid', _('files_viewmode_grid')],
                    ['list', _('files_viewmode_list')]
                ]
            }),
            listeners: {
                select: {
                    fn: this.changeViewmode,
                    scope: this
                }
            }
        }];
    },

    /**
     * Get the bottom filepath textfield in the browser view
     *
     * @returns {Array}
     */
    getPathbar: function() {
        return {
            cls: 'modx-browser-pathbbar',
            items: [{
                xtype: 'textfield',
                id: `${this.ident}-filepath`,
                cls: 'modx-browser-filepath',
                listeners: {
                    focus: {
                        // select the filepath on focus
                        fn: function(el) {
                            // let the focus event stick first, needed for webkit primarily
                            setTimeout(() => {
                                const field = el.getEl().dom;
                                if (field.createTextRange) {
                                    const selRange = field.createTextRange();
                                    selRange.collapse(true);
                                    selRange.moveStart('character', 0);
                                    selRange.moveEnd('character', field.value.length);
                                    selRange.select();
                                } else if (field.setSelectionRange) {
                                    field.setSelectionRange(0, field.value.length);
                                } else if (field.selectionStart) {
                                    field.selectionStart = 0;
                                    field.selectionEnd = field.value.length;
                                }
                            }, 50);
                        },
                        scope: this
                    }
                }
            }]
        };
    },

    setReturn: function(el) {
        this.returnEl = el;
    },

    onSelect: function(data) {},

    onSelectHandler: function(data) {
        Ext.get(this.returnEl).dom.value = unescape(data.url);
    }
});
Ext.reg('modx-media-view', MODx.Media);

/**
 * This is the popup window (not Ext.Window!) that opens when triggered from an RTE
 */
MODx.browser.RTE = function(config = {}) {
    this.ident = config.ident || Ext.id();

    // Hide the "MODX Browser" toolbar button
    MODx.browserOpen = true;

    Ext.Ajax.defaultHeaders = {
        modAuth: config.auth
    };

    Ext.Ajax.extraParams = {
        HTTP_MODAUTH: config.auth
    };

    // Tree navigation
    this.tree = MODx.load({
        xtype: 'modx-tree-directory',
        onUpload: function() {
            this.view.run();
        },
        scope: this,
        source: config.source || MODx.config.default_media_source,
        hideFiles: config.hideFiles || MODx.config.modx_browser_tree_hide_files,
        hideTooltips: config.hideTooltips || MODx.config.modx_browser_tree_hide_tooltips || true, // by default do not request image preview tooltips in the media browser
        openTo: config.openTo || '',
        ident: this.ident,
        rootIconCls: MODx.config.mgr_source_icon,
        rootId: config.rootId || '/',
        rootName: _('files'),
        rootVisible: config.rootVisible === undefined || !Ext.isEmpty(config.rootId),
        id: `${this.ident}-tree`,
        hideSourceCombo: config.hideSourceCombo || false,
        useDefaultToolbar: false,
        listeners: {
            afterUpload: {
                fn: function() {
                    this.view.run();
                },
                scope: this
            },
            afterQuickCreate: {
                fn: function() {
                    this.view.run();
                },
                scope: this
            },
            afterRename: {
                fn: function() {
                    this.view.run();
                },
                scope: this
            },
            afterRemove: {
                fn: function() {
                    this.view.run();
                },
                scope: this
            },
            changeSource: {
                fn: function(source) {
                    this.config.source = source;
                    this.view.config.source = source;
                    this.view.baseParams.source = source;
                    this.view.dir = '/';
                    this.view.run();
                },
                scope: this
            },
            afterrender: {
                fn: function(tree) {
                    tree.root.expand();
                },
                scope: this
            },
            beforeclick: {
                fn: function(node, e) {
                    // load the node/folder that is clicked on but prevent unnecessary requests when a file is clicked
                    if (!node.leaf) {
                        this.load(node.id);
                    } else {
                        // sync the selected item in the tree with the one in browser view
                        // the id of a browser view node in the store is the full absolute URL
                        // but there is a bug with urlAbsolute, see #11821 that's why we prepend a slash
                        this.view.select(this.view.store.indexOfId(`/${node.attributes.url}`));
                        // but instead load the container the file resides in if not already displayed
                        if (this.view.dir !== node.parentNode.id) {
                            this.load(node.parentNode.id);
                        }
                        return false;
                    }
                },
                scope: this
            }
        }
    });

    // DataView
    this.view = MODx.load({
        xtype: 'modx-browser-view',
        onSelect: {
            fn: this.onSelect,
            scope: this
        },
        source: config.source || MODx.config.default_media_source,
        allowedFileTypes: config.allowedFileTypes || '',
        wctx: config.wctx || 'web',
        openTo: config.openTo || '',
        multiSelect: config.multiSelect || false,
        ident: this.ident,
        id: `${this.ident}-view`,
        tree: this.tree
    });

    Ext.applyIf(config, {
        title: _('modx_browser'),
        cls: 'modx-browser modx-browser-rte',
        layout: 'border',
        renderTo: document.body,
        id: `${this.ident}-viewport`,
        onSelect: MODx.onBrowserReturn || function(data) {},
        items: [{
            id: `${this.ident}-browser-tree`,
            cls: 'modx-browser-tree',
            region: 'west',
            width: 250,
            height: '100%',
            split: true,
            items: this.tree,
            autoScroll: true
        }, {
            id: `${this.ident}-browser-view`,
            cls: 'modx-browser-view-ct',
            region: 'center',
            autoScroll: true,
            width: 450,
            items: this.view,
            tbar: this.getToolbar(),
            bbar: this.getPathbar()
        }, {
            id: `${this.ident}-img-detail-panel`,
            cls: 'modx-browser-details-ct',
            region: 'east',
            split: true,
            width: 200,
            minWidth: 200,
            maxWidth: 300
        }, {
            id: `${this.ident}-south`,
            cls: 'modx-browser-rte-buttons',
            region: 'south',
            split: false,
            bbar: ['->', {
                xtype: 'button',
                id: `${this.ident}-cancel-btn`,
                text: _('cancel'),
                minWidth: 75,
                handler: this.onCancel,
                scope: this
            }, {
                xtype: 'button',
                id: `${this.ident}-ok-btn`,
                text: _('ok'),
                cls: 'primary-button',
                minWidth: 75,
                handler: this.onSelect,
                scope: this
            }]
        }]
    });
    MODx.browser.RTE.superclass.constructor.call(this, config);
    this.config = config;
};
Ext.extend(MODx.browser.RTE, Ext.Viewport, {
    returnEl: null,

    /**
     * Filter the DataView results
     */
    filter: function() {
        const filter = Ext.getCmp(`${this.ident}filter`);
        this.view.store.filter('name', filter.getValue(), true);
        this.view.select(0);
    },

    /**
     * Load the given directory in the DataView
     *
     * @param {String} dir
     */
    load: function(dir) {
        dir = dir || (Ext.isEmpty(this.config.openTo) ? '' : this.config.openTo);
        this.view.run({
            dir: dir,
            source: this.config.source,
            allowedFileTypes: this.config.allowedFileTypes || '',
            wctx: this.config.wctx || 'web'
        });
        this.sortStore();
    },

    /**
     * Sort the DataView results
     */
    sortStore: function() {
        const sortBy = Ext.getCmp(`${this.ident}sortSelect`).getValue();
        this.view.store.sort(sortBy, sortBy === 'name' ? 'ASC' : 'DESC');
        this.view.select(0);
    },

    /**
     * Switch viewmode from grid to list and vice versa
     */
    changeViewmode: function() {
        const view = Ext.getCmp(`${this.ident}viewSelect`).getValue();
        this.view.setTemplate(view);
        this.view.select(0);
    },

    /**
     * Remove any filter applied to the DataView
     */
    reset: function() {
        if (this.rendered) {
            Ext.getCmp(`${this.ident}filter`).reset();
            this.view.getEl().dom.scrollTop = 0;
        }
        this.view.store.clearFilter();
        this.view.select(0);
    },

    /**
     * Get the browser view toolbar configuration
     *
     * @returns {Array}
     */
    getToolbar: function() {
        return [{
            text: `${_('filter')}:`,
            xtype: 'label'
        }, {
            xtype: 'textfield',
            id: `${this.ident}filter`,
            selectOnFocus: true,
            width: 200,
            listeners: {
                render: {
                    fn: function() {
                        Ext.getCmp(`${this.ident}filter`).getEl().on('keyup', function() {
                            this.filter();
                        }, this, { buffer: 500 });
                    },
                    scope: this
                }
            }
        }, {
            text: `${_('sort_by')}:`,
            xtype: 'label'
        }, {
            id: `${this.ident}sortSelect`,
            xtype: 'combo',
            typeAhead: true,
            triggerAction: 'all',
            width: 130,
            editable: false,
            mode: 'local',
            displayField: 'desc',
            valueField: 'name',
            lazyInit: false,
            value: MODx.config.modx_browser_default_sort || 'name',
            store: new Ext.data.SimpleStore({
                fields: ['name', 'desc'],
                data: [
                    ['name', _('name')],
                    ['size', _('file_size')],
                    ['lastmod_raw', _('last_modified')]
                ]
            }),
            listeners: {
                select: {
                    fn: this.sortStore,
                    scope: this
                }
            }
        }, '-', {
            text: `${_('files_viewmode')}:`,
            xtype: 'label'
        }, '-', {
            id: `${this.ident}viewSelect`,
            xtype: 'combo',
            typeAhead: false,
            triggerAction: 'all',
            width: 100,
            editable: false,
            mode: 'local',
            displayField: 'desc',
            valueField: 'type',
            lazyInit: false,
            value: MODx.config.modx_browser_default_viewmode || 'grid',
            store: new Ext.data.SimpleStore({
                fields: ['type', 'desc'],
                data: [
                    ['grid', _('files_viewmode_grid')],
                    ['list', _('files_viewmode_list')]
                ]
            }),
            listeners: {
                select: {
                    fn: this.changeViewmode,
                    scope: this
                }
            }
        }];
    },

    /**
     * Get the bottom filepath textfield in the browser view
     *
     * @returns {Array}
     */
    getPathbar: function() {
        return {
            cls: 'modx-browser-pathbbar',
            items: [{
                xtype: 'textfield',
                id: `${this.ident}-filepath`,
                cls: 'modx-browser-filepath',
                listeners: {
                    focus: {
                        // select the filepath on focus
                        fn: function(el) {
                            // let the focus event stick first, needed for webkit primarily
                            setTimeout(() => {
                                const field = el.getEl().dom;
                                if (field.createTextRange) {
                                    const selRange = field.createTextRange();
                                    selRange.collapse(true);
                                    selRange.moveStart('character', 0);
                                    selRange.moveEnd('character', field.value.length);
                                    selRange.select();
                                } else if (field.setSelectionRange) {
                                    field.setSelectionRange(0, field.value.length);
                                } else if (field.selectionStart) {
                                    field.selectionStart = 0;
                                    field.selectionEnd = field.value.length;
                                }
                            }, 50);
                        },
                        scope: this
                    }
                }
            }]
        };
    },

    setReturn: function(el) {
        this.returnEl = el;
    },

    onSelect: function() {
        const selNodes = this.view.getSelectedNodes(),
              callback = this.config.onSelect || this.onSelectHandler,
              { lookup } = this.view,
              { scope } = this.config,
              source = parseInt(this.config.source || MODx.config.default_media_source, 10);

        if (callback) {
            selNodes.forEach(selNode => {
                try {
                    const selData = lookup[selNode.id];
                    selData.source = source;
                    Ext.callback(callback, scope || this, [selData]);
                    this.fireEvent('select', selData);
                } catch (e) {
                    console.error('Error processing MODx.Browser onSelect callback:', e);
                }
            });
        }

        if (window.top.opener) {
            window.top.close();
            window.top.opener.focus();
        }
    },

    onCancel: function() {
        const
            callback = this.config.onSelect || this.onSelectHandler,
            { scope } = this.config
        ;
        Ext.callback(callback, scope || this, [null]);
        this.fireEvent('select', null);
        if (window.top.opener) {
            window.top.close();
            window.top.opener.focus();
        }
    },

    onSelectHandler: function(data) {
        Ext.get(this.returnEl).dom.value = unescape(data.url);
    }
});
Ext.reg('modx-browser-rte', MODx.browser.RTE);

MODx.browser.onPopState = function({ state }, { tree, view }) {
    const
        params = MODx.util.UrlParams.parse(state),
        defaultSource = view.config.originalSource,
        source = params.source || defaultSource
    ;
    if (tree.sourceCombo && !tree.config.hideSourceCombo && source !== tree.sourceCombo.getValue()) {
        tree.config.source = source;
        tree.baseParams.source = source;
        tree.dir = params.dir || '/';
        tree.sourceCombo.getStore().load({
            scope: tree,
            callback: () => {
                tree.sourceCombo.setValue(source);
                tree.refresh();
                const root = tree.getRootNode();
                if (root) {
                    root.setText(tree.sourceCombo.getRawValue());
                }
                view.run({
                    dir: params.dir || '/',
                    source: source,
                    allowedFileTypes: view.config.allowedFileTypes || '',
                    wctx: view.config.wctx || 'web'
                });
                tree.expandTreePath(params.dir || '/');
            }
        });
    } else {
        view.run({
            dir: params.dir || '/',
            source: source,
            allowedFileTypes: view.config.allowedFileTypes || '',
            wctx: view.config.wctx || 'web'
        });
        tree.expandTreePath(params.dir || '/');
    }
};
