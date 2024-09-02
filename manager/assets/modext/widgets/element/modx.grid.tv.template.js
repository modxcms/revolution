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
        ,width: 60
        ,sortable: true
    });
    Ext.applyIf(config,{
        id: 'modx-grid-tv-template'
        ,url: MODx.config.connector_url
        ,fields: [
            'id',
            'templatename',
            'category',
            'category_name',
            'description',
            'access',
            'menu'
        ]
        ,showActionsColumn: false
        ,baseParams: {
            action: 'Element/TemplateVar/Template/GetList'
            ,tv: config.tv
            ,category: MODx.request.category || null
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
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=element/template/update&id=' + record.data.id
                    ,target: '_blank'
                });
            }, scope: this }
        },{
            header: _('category')
            ,dataIndex: 'category_name'
            ,width: 300
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 300
        },tt]
        ,tbar: [
            '->',
            {
                xtype: 'modx-combo-category'
                ,itemId: 'filter-category'
                ,emptyText: _('filter_by_category')
                ,value: MODx.request.category !== 'undefined' ? MODx.request.category : null
                ,submitValue: false
                ,hiddenName: ''
                ,width: 200
                ,listeners: {
                    select: {
                        fn: function (cmp, record, selectedIndex) {
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
    MODx.grid.TemplateVarTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.TemplateVarTemplate,MODx.grid.Grid);
Ext.reg('modx-grid-tv-template',MODx.grid.TemplateVarTemplate);
