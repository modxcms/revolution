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
        ,sortable: false
    });
    Ext.applyIf(config,{
        title: _('template_assignedtv_tab')
        ,id: 'modx-grid-template-tv'
        ,url: MODx.config.connector_url
        ,fields: ['id','name','description','tv_rank','access','category_name','category']
        ,baseParams: {
            action: 'element/template/tv/getlist'
            ,template: config.template
            ,sort: 'tv_rank'
        }
        ,saveParams: {
            template: config.template
        }
        ,width: 800
        ,paging: true
        ,plugins: tt
        ,remoteSort: true
        ,enableDragDrop: true
        ,ddGroup : 'template-tvs-dd'
        ,sm: new Ext.grid.RowSelectionModel({singleSelect:false})
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,editor: { xtype: 'textfield' ,allowBlank: false }
            ,sortable: true
        },{
            header: _('category')
            ,dataIndex: 'category_name'
            ,width: 150
            ,sortable: true
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 350
            ,editor: { xtype: 'textfield' }
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
            ,value: ''
            ,allowBlank: true
            ,width: 150
            ,listeners: {
                'select': {fn: this.filterByCategory, scope:this}
            }
        },'-',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-temptv-search'
            ,emptyText: _('search_ellipsis')
            ,listeners: {
                'change': {fn: this.search, scope: this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: this.blur
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,id: 'modx-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });
    MODx.grid.TemplateTV.superclass.constructor.call(this,config);

    this.on('render',function() {
        var grid = this;
        var store = this.getStore();
        var ddrow = new Ext.dd.DropTarget(grid.getView().mainBody, {
            ddGroup : 'template-tvs-dd'
            ,notifyDrop : function(dd, e, data) {
                var sm = grid.getSelectionModel();
                var row = sm.getSelections();
                var cindex = dd.getDragData(e).rowIndex;
                if (sm.hasSelection()) {
                    for (i = 0; i < row.length; i++) {
                        store.remove(store.getById(row[i].id));
                        store.insert(cindex,row[i]);
                    }
                    sm.selectRecords(row);
                }
                
                Ext.each(store.data.items, function(item, index, allItems) {
                    // take pagination into account by recalculating the index based on the currently viewed page
                    index = grid.config.bbar.cursor !== 0 ? ((grid.config.bbar.cursor - 1) * grid.config.bbar.pageSize) + index : index;

                    if (row[0].data.category_name === item.data.category_name) {
                        item.set('tv_rank', index);
                    }
                }, this);
            }
        });
    },this);
};
Ext.extend(MODx.grid.TemplateTV,MODx.grid.Grid,{

    filterByCategory: function(cb,rec,ri) {
        this.getStore().baseParams['category'] = cb.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.search = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        Ext.getCmp('modx-temptv-filter-category').setValue('');
        this.getBottomToolbar().changePage(1);
        this.refresh();
        return true;
    }
    ,clearFilter: function() {
    	this.getStore().baseParams = {
            action: 'element/template/tv/getList'
            ,template: this.config.template
    	};
        Ext.getCmp('modx-temptv-filter-category').reset();
        Ext.getCmp('modx-temptv-search').setValue('');
    	this.getBottomToolbar().changePage(1);
        this.refresh();
    }

});
Ext.reg('modx-grid-template-tv',MODx.grid.TemplateTV);
