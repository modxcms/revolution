/**
 * Loads a grid of TVs assigned to the Template.
 *
 * @class MODx.grid.TemplateTV
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-template-tv
 */
// eslint-disable-next-line func-names
MODx.grid.TemplateTV = function(config = {}) {
    const accessCheckboxCol = new Ext.ux.grid.CheckColumn({
        header: _('access'),
        dataIndex: 'access',
        width: 70,
        sortable: true
    });
    Ext.applyIf(config, {
        title: _('template_assignedtv_tab'),
        id: 'modx-grid-template-tv',
        url: MODx.config.connector_url,
        fields: [
            'id',
            'name',
            'caption',
            'tv_rank',
            'access',
            'perm',
            'category_name',
            'category'
        ],
        baseParams: {
            action: 'Element/Template/TemplateVar/GetList',
            template: config.template,
            sort: 'tv_rank',
            category: MODx.request.category || null
        },
        saveParams: {
            template: config.template
        },
        width: 800,
        paging: true,
        plugins: accessCheckboxCol,
        remoteSort: true,
        sortBy: 'category_name, tv_rank',
        grouping: true,
        groupBy: 'category_name',
        singleText: _('tv'),
        pluralText: _('tvs'),
        enableDragDrop: true,
        ddGroup: 'template-tvs-ddsort',
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true,
            listeners: {
                beforerowselect: function(sm, idx, keep, record) {
                    // eslint-disable-next-line no-param-reassign
                    sm.grid.ddText = `<div>${record.data.name}</div>`;
                }
            }
        }),
        columns: [{
            header: _('name'),
            dataIndex: 'name',
            width: 150,
            sortable: true,
            renderer: {
                fn: function(value, metadata, record) {
                    return this.renderLink(value, {
                        href: `?a=element/tv/update&id=${record.data.id}`,
                        target: '_blank'
                    });
                },
                scope: this
            }
        }, {
            header: _('category'),
            dataIndex: 'category_name',
            width: 150,
            sortable: true
        }, {
            header: _('caption'),
            dataIndex: 'caption',
            width: 350,
            sortable: false
        }, accessCheckboxCol, {
            header: _('rank'),
            dataIndex: 'tv_rank',
            width: 100,
            editor: { xtype: 'textfield', allowBlank: false },
            sortable: true
        }],
        tbar: [
            '->',
            {
                xtype: 'modx-combo-category',
                itemId: 'filter-category',
                emptyText: _('filter_by_category'),
                value: MODx.request.category !== 'undefined' ? MODx.request.category : null,
                submitValue: false,
                hiddenName: '',
                width: 200,
                listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.applyGridFilter(cmp, 'category');
                        },
                        scope: this
                    }
                }
            },
            this.getQueryFilterField(),
            this.getClearFiltersButton('filter-category, filter-query')
        ]
    });
    MODx.grid.TemplateTV.superclass.constructor.call(this, config);
    this.on('render', this.prepareDDSort, this);
};
Ext.extend(MODx.grid.TemplateTV, MODx.grid.Grid, {
    getMenu: function() {
        const
            record = this.getSelectionModel().getSelected(),
            permissions = record.data.perm,
            menu = []
        ;
        if (permissions.indexOf('pedit') !== -1) {
            menu.push({
                text: _('edit'),
                handler: this.updateTV
            });
        }
        return menu;
    },

    updateTV: function(itm, e) {
        MODx.loadPage('element/tv/update', `id=${this.menu.record.id}`);
    },

    sortTVs: function(sourceNode, targetNode) {
        const
            store = this.getStore(),
            sourceIdx = store.indexOf(sourceNode),
            targetIdx = store.indexOf(targetNode)
        ;
        // Insert the selection to the target (and remove original selection)
        store.removeAt(sourceIdx);
        store.insert(targetIdx, sourceNode);

        // Extract the store items with the same category_name as the sourceNode to start the index at 0 for each category
        // eslint-disable-next-line func-names, prefer-arrow-callback
        const filteredStore = store.queryBy(function(record, id) {
            if (record.get('category_name') === sourceNode.get('category_name')) {
                return true;
            }
            return false;
        }, this);

        // Loop trough the filtered store and re-apply the re-calculated ranks to the store records
        // eslint-disable-next-line func-names, prefer-arrow-callback
        Ext.each(filteredStore.items, function(item, index, allItems) {
            if (sourceNode.get('category_name') === item.get('category_name')) {
                const record = store.getById(item.id);
                record.set('tv_rank', index);
            }
        }, this);
    },

    prepareDDSort: function(grid) {
        this.dropTarget = new Ext.dd.DropTarget(grid.getView().mainBody, {
            ddGroup: 'template-tvs-ddsort',
            copy: false,
            notifyOver: function(dragSource, e, data) {
                if (dragSource.getDragData(e)) {
                    const
                        targetNode = dragSource.getDragData(e).selections[0],
                        sourceNode = data.selections[0]
                    ;
                    if ((sourceNode.data.category_name !== targetNode.data.category_name)
                        || !sourceNode.data.access
                        || !targetNode.data.access
                        || (sourceNode.data.id === targetNode.data.id)
                    ) {
                        return this.dropNotAllowed;
                    }
                    return this.dropAllowed;
                }
                return this.dropNotAllowed;
            },
            notifyDrop: function(dragSource, e, data) {
                if (dragSource.getDragData(e)) {
                    const
                        targetNode = dragSource.getDragData(e).selections[0],
                        sourceNode = data.selections[0]
                    ;
                    if ((targetNode.id !== sourceNode.id)
                        && (targetNode.get('category_name') === sourceNode.get('category_name'))
                        && sourceNode.get('access')
                    ) {
                        grid.sortTVs(sourceNode, targetNode);
                    }
                }
            }
        });
    }
});
Ext.reg('modx-grid-template-tv', MODx.grid.TemplateTV);
