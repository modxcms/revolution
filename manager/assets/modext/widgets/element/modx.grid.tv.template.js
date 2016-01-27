/**
 * Loads a grid of TVs assigned to the Template.
 *
 * @class MODx.grid.TemplateVarTemplate
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-tv-template
 */
MODx.grid.TemplateVarTemplate = function(config) {
    config = config || {};
    var tt = new Ext.ux.grid.CheckColumn({
        header: _('access')
        ,dataIndex: 'access'
        ,width: 50
        ,sortable: true
    });
    Ext.applyIf(config,{
        id: 'modx-grid-tv-template'
        ,url: MODx.config.connector_url
        ,fields: ['id','templatename','category','category_name','description','access','menu']
        ,baseParams: {
            action: 'element/tv/template/getList'
            ,tv: config.tv
        }
        ,saveParams: {
            tv: config.tv
        }
        ,width: 800
        ,paging: true
        ,plugins: tt
        ,remoteSort: true
        ,columns: [{
            header: _('name')
            ,dataIndex: 'templatename'
            ,width: 150
            ,sortable: true
        },{
            header: _('category')
            ,dataIndex: 'category_name'
            ,width: 300
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 300
        },tt]
        ,tbar: ['->',{
            xtype: 'modx-combo-category'
            ,name: 'filter_category'
            ,hiddenName: 'filter_category'
            ,id: 'modx-tvtemp-filter-category'
            ,emptyText: _('filter_by_category')
            ,value: ''
            ,allowBlank: true
            ,width: 150
            ,listeners: {
                'select': {fn: this.filterByCategory, scope:this}
            }
        },'-',{
            xtype: 'textfield'
            ,name: 'query'
            ,id: 'modx-tvtemp-search'
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
    MODx.grid.TemplateVarTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.TemplateVarTemplate,MODx.grid.Grid,{
    filterByCategory: function(cb,rec,ri) {
        this.getStore().baseParams['category'] = cb.getValue();
        this.getBottomToolbar().changePage(1);
        //this.refresh();
    }
    ,search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.query = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        Ext.getCmp('modx-tvtemp-filter-category').setValue('');
        this.getBottomToolbar().changePage(1);
        //this.refresh();
        return true;
    }
    ,clearFilter: function() {
    	this.getStore().baseParams = {
            action: 'element/tv/template/getList'
    	};
        Ext.getCmp('modx-tvtemp-filter-category').reset();
        Ext.getCmp('modx-tvtemp-search').setValue('');
    	this.getBottomToolbar().changePage(1);
        //this.refresh();
    }
});
Ext.reg('modx-grid-tv-template',MODx.grid.TemplateVarTemplate);
