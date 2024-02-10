/**
 * Loads a grid of TVs assigned to the Template.
 *
 * @class MODx.grid.TemplateTV
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-template-tv
 */
MODx.grid.TemplateTV = function(config) {
    config = config || {};
    var tt = new Ext.ux.grid.CheckColumn({
        header: _('access')
        ,dataIndex: 'access'
        ,width: 70
        ,sortable: true
    });
    Ext.applyIf(config,{
        title: _('template_assignedtv_tab')
        ,id: 'modx-grid-template-tv'
        ,url: MODx.config.connector_url
        ,fields: ['id','name','caption','tv_rank','access','perm','category_name','category']
        ,baseParams: {
            action: 'Element/Template/TemplateVar/GetList'
            ,template: config.template
            ,sort: 'tv_rank'
            ,category: MODx.request.category || ''
            ,query: MODx.request.query ? decodeURIComponent(MODx.request.query) : ''
        }
        ,saveParams: {
            template: config.template
        }
        ,width: 800
        ,paging: true
        ,plugins: tt
        ,remoteSort: true
        ,sortBy: 'category_name, tv_rank'
        ,grouping: true
        ,groupBy: 'category_name'
        ,singleText: _('tv')
        ,pluralText: _('tvs')
        ,enableDragDrop: true
        ,ddGroup : 'template-tvs-ddsort'
        ,sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
            ,listeners: {
                beforerowselect: function(sm, idx, keep, record) {
                    sm.grid.ddText = '<div>'+ record.data.name +'</div>';
                }
            }
        })
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,sortable: true
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=element/tv/update&id=' + record.data.id
                    ,target: '_blank'
                });
            }, scope: this }
        },{
            header: _('category')
            ,dataIndex: 'category_name'
            ,width: 150
            ,sortable: true
        },{
            header: _('caption')
            ,dataIndex: 'caption'
            ,width: 350
            ,sortable: false
        },tt,{
            header: _('rank')
            ,dataIndex: 'tv_rank'
            ,width: 100
            ,editor: { xtype: 'textfield' ,allowBlank: false }
            ,sortable: true
        }]
        ,tbar: ['->',{
            xtype: 'modx-combo-category'
            ,name: 'filter_category'
            ,hiddenName: 'filter_category'
            ,id: 'modx-temptv-filter-category'
            ,emptyText: _('filter_by_category')
            ,value: MODx.request.category || ''
            ,allowBlank: true
            ,width: 150
            ,listeners: {
                'select': {
                    fn: function (cb, rec, ri) {
                        if (!MODx.request.query) {
                            this.filterByCategory(cb, rec, ri);
                        }
                    }
                    ,scope: this
                }
            }
        },{
            xtype: 'textfield'
            ,name: 'query'
            ,id: 'modx-temptv-query'
            ,cls: 'x-form-filter'
            ,emptyText: _('search')
            ,value: MODx.request.query ? decodeURIComponent(MODx.request.query) : ''
            ,listeners: {
                'change': {
                    fn: function (cb, rec, ri) {
                        this.tvSearch(cb, rec, ri);
                    }
                    ,scope: this
                }
                ,'afterrender': {
                    fn: function (cb) {
                        if (MODx.request.query) {
                            this.tvSearch(cb, cb.value);
                            MODx.request.query = '';
                        }
                    }
                    ,scope: this
                }
                ,'render': {
                    fn: function (cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER
                            ,fn: this.blur
                            ,scope: cmp
                        });
                    }
                    ,scope: this
                }
            }
        },{
            xtype: 'button'
            ,id: 'modx-filter-clear'
            ,cls: 'x-form-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this},
                'mouseout': { fn: function(evt){
                    this.removeClass('x-btn-focus');
                }
                }
            }
        }]
    });
    MODx.grid.TemplateTV.superclass.constructor.call(this,config);
    this.on('render', this.prepareDDSort, this);
};
Ext.extend(MODx.grid.TemplateTV,MODx.grid.Grid,{
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.perm;
        var m = [];

        if (p.indexOf('pedit') != -1) {
            m.push({
                text: _('edit')
                ,handler: this.updateTV
            });
        }
        return m;
    }

    ,updateTV: function(itm,e) {
        MODx.loadPage('element/tv/update', 'id='+this.menu.record.id);
    }

    ,sortTVs: function(sourceNode, targetNode) {
        var store = this.getStore();
        var sourceIdx = store.indexOf(sourceNode);
        var targetIdx = store.indexOf(targetNode);

        // Insert the selection to the target (and remove original selection)
        store.removeAt(sourceIdx);
        store.insert(targetIdx, sourceNode);

        // Extract the store items with the same category_name as the sourceNode to start the index at 0 for each category
        var filteredStore = store.queryBy(function(rec, id) {
            if (rec.get('category_name') === sourceNode.get('category_name')) {
                return true;
            }
            return false;
        }, this);

        // Loop trough the filtered store and re-apply the re-calculated ranks to the store records
        Ext.each(filteredStore.items, function(item, index, allItems) {
            if (sourceNode.get('category_name') === item.get('category_name')) {
                var record = store.getById(item.id);
                record.set('tv_rank', index);
            }
        }, this);
    }

    ,filterByCategory: function (cb, newValue, ov) {
        var nv = Ext.isEmpty(newValue) || Ext.isObject(newValue) ? cb.getValue() : newValue;
        var s = this.getStore();
        s.baseParams.category = nv;
        s.load();
        this.replaceState();
        this.getBottomToolbar().changePage(1);
    }

    ,tvSearch: function(tf, newValue, ov) {
        var nv = newValue || tf;
        var s = this.getStore();
        s.baseParams.query = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        s.load();
        this.replaceState();
        this.getBottomToolbar().changePage(1);
    }

    ,clearFilter: function() {
        var s = this.getStore();
        s.baseParams.category = s.baseParams.query = '';
        Ext.getCmp('modx-temptv-filter-category').setValue('');
        Ext.getCmp('modx-temptv-query').setValue('');
        s.load();
        this.replaceState();
        this.getBottomToolbar().changePage(1);
    }

    ,prepareDDSort: function(grid) {
        this.dropTarget = new Ext.dd.DropTarget(grid.getView().mainBody, {
            ddGroup: 'template-tvs-ddsort'
            ,copy: false
            ,notifyOver: function(dragSource, e, data) {
                if (dragSource.getDragData(e)) {
                    var targetNode = dragSource.getDragData(e).selections[0];
                    var sourceNode = data.selections[0];

                    if ((sourceNode.data['category_name'] != targetNode.data['category_name']) ||
                        !sourceNode.data['access'] ||
                        !targetNode.data['access'] ||
                        (sourceNode.data['id'] == targetNode.data['id'])
                        ) {
                        return this.dropNotAllowed;
                    }

                    return this.dropAllowed;
                }

                return this.dropNotAllowed;
            }
            ,notifyDrop : function(dragSource, e, data) {
                if (dragSource.getDragData(e)) {
                    var targetNode = dragSource.getDragData(e).selections[0];
                    var sourceNode = data.selections[0];
                    if ((targetNode.id != sourceNode.id) &&
                        (targetNode.get('category_name') === sourceNode.get('category_name')) &&
                        sourceNode.get('access')
                    ) {
                        grid.sortTVs(sourceNode, targetNode);
                    }
                }
            }
        });
    }
});
Ext.reg('modx-grid-template-tv',MODx.grid.TemplateTV);
