/**
 * Loads the search filter panel
 *
 * @class MODx.panel.Search
 * @extends MODx.FormPanel
 * @param {Object} config An object of options.
 * @xtype modx-panel-search
 */
MODx.panel.Search = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-search'
        ,cls: 'container form-with-labels'
        ,labelAlign: 'top'
        ,autoHeight: true
        ,items: [{
            html: _('search')
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('search_criteria')
            ,layout: 'form'
            ,defaults: {
                collapsible: false
                ,autoHeight: true
            }
            ,items: [{
                layout: 'form'
                ,cls: 'main-wrapper'
                ,border: false
                ,items: this.getFields(config)
            },{
                html: '<hr />'
                ,border: false
            },{
                xtype: 'modx-grid-search'
                ,cls: 'main-wrapper'
                ,preventRender: true
            }]
        }])]
    });
    MODx.panel.Search.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Search,MODx.FormPanel,{
    filters: {}

    ,getFields: function(config) {
        return [{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,anchor: '100%'
                ,border: false
            }
            ,items: [{
                columnWidth: .6
                ,items: [{
                    xtype: 'textfield'
                    ,name: 'pagetitle'
                    ,id: 'modx-search-pagetitle'
                    ,fieldLabel: _('pagetitle')
                    ,anchor: '100%'
                    ,listeners: {
                        'change': {fn:this.filter,scope: this}
                        ,'render': {fn:this._addEnterKeyHandler}
                    }
                    ,value: config.record.q || ''
                },{
                    xtype: 'textfield'
                    ,name: 'longtitle'
                    ,id: 'modx-search-longtitle'
                    ,fieldLabel: _('long_title')
                    ,anchor: '100%'
                    ,listeners: {
                        'change': {fn:this.filter,scope: this}
                        ,'render': {fn:this._addEnterKeyHandler}
                    }
                    ,value: config.record.q || ''
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('resource_summary')
                    ,name: 'introtext'
                    ,id: 'modx-search-introtext'
                    ,anchor: '100%'
                    ,listeners: {
                        'change': {fn:this.filter,scope: this}
                        ,'render': {fn:this._addEnterKeyHandler}
                    }
                    ,value: config.record.q || ''
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('resource_description')
                    ,name: 'description'
                    ,id: 'modx-search-description'
                    ,anchor: '100%'
                    ,listeners: {
                        'change': {fn:this.filter,scope: this}
                        ,'render': {fn:this._addEnterKeyHandler}
                    }
                    ,value: config.record.q || ''
                }]
            },{
                columnWidth: .4
                ,items: [{
                    xtype: 'textfield'
                    ,name: 'alias'
                    ,id: 'modx-search-alias'
                    ,fieldLabel: _('alias')
                    ,anchor: '100%'
                    ,listeners: {
                        'change': {fn:this.filter,scope: this}
                        ,'render': {fn:this._addEnterKeyHandler}
                    }
                    ,value: config.record.q || ''
                },{
                    xtype: 'textfield'
                    ,name: 'menutitle'
                    ,id: 'modx-search-menutitle'
                    ,fieldLabel: _('resource_menutitle')
                    ,anchor: '100%'
                    ,listeners: {
                        'change': {fn:this.filter,scope: this}
                        ,'render': {fn:this._addEnterKeyHandler}
                    }
                    ,value: config.record.q || ''
                },{
                    layout: 'column'
                    ,border: false
                    ,defaults: {
                        layout: 'form'
                        ,labelAlign: 'top'
                        ,anchor: '100%'
                        ,border: false
                    }
                    ,items: [{
                        columnWidth: .5
                        ,items: [{
                            xtype: 'textfield'
                            ,name: 'id'
                            ,id: 'modx-search-id'
                            ,fieldLabel: _('id')
                            ,width: 100
                            ,listeners: {
                                'change': {fn:this.filter,scope: this}
                                ,'render': {fn:this._addEnterKeyHandler}
                            }
                        },{
                            xtype: 'xcheckbox'
                            ,name: 'published'
                            ,id: 'modx-search-published'
                            ,boxLabel: _('published')
                            ,hideLabel: true
                            ,inputValue: 1
                            ,checked: false
                            ,handler: this.filter
                            ,scope: this
                        },{
                            xtype: 'xcheckbox'
                            ,name: 'unpublished'
                            ,id: 'modx-search-unpublished'
                            ,boxLabel: _('unpublished')
                            ,hideLabel: true
                            ,inputValue: 1
                            ,checked: false
                            ,handler: this.filter
                            ,scope: this
                        }]
                    },{
                        columnWidth: .5
                        ,items: [{
                            xtype: 'textfield'
                            ,name: 'parent'
                            ,id: 'modx-search-parent'
                            ,fieldLabel: _('parent')
                            ,width: 100
                            ,listeners: {
                                'change': {fn:this.filter,scope: this}
                                ,'render': {fn:this._addEnterKeyHandler}
                            }
                        },{
                            xtype: 'xcheckbox'
                            ,name: 'deleted'
                            ,id: 'modx-search-deleted'
                            ,boxLabel: _('deleted')
                            ,hideLabel: true
                            ,inputValue: 1
                            ,checked: false
                            ,handler: this.filter
                            ,scope: this
                        },{
                            xtype: 'xcheckbox'
                            ,name: 'undeleted'
                            ,id: 'modx-search-undeleted'
                            ,boxLabel: _('undeleted')
                            ,hideLabel: true
                            ,inputValue: 1
                            ,checked: false
                            ,handler: this.filter
                            ,scope: this
                        }]
                    }]
                }]
            }]
        },{
            xtype: 'textarea'
            ,name: 'content'
            ,fieldLabel: _('content')
            ,anchor: '100%'
            ,grow: true
            ,listeners: {
                'change': {fn:this.filter,scope: this}
                ,'render': {fn:this._addEnterKeyHandler}
            }
            ,value: config.record.q || ''
        }];
    }

    ,filter: function(tf,newValue,oldValue) {
        var p = this.getForm().getValues();
        p.action = 'Resource/Search';

        var g = Ext.getCmp('modx-grid-search');
        if (g) {
            g.getStore().baseParams = p;
            g.getBottomToolbar().changePage(1);
            g.refresh();
        }
    }

    ,_addEnterKeyHandler: function() {
        this.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
            this.fireEvent('change');
        },this);
    }
});
Ext.reg('modx-panel-search',MODx.panel.Search);

/**
 * Loads the search result grid
 *
 * @class MODx.grid.Search
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype grid-search
 */
MODx.grid.Search = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('search_results')
        ,id: 'modx-grid-search'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Resource/Search'
        }
        ,fields: ['id','pagetitle','description','published','deleted','menu']
        ,paging: true
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 20
            ,sortable: true
        },{
            header: _('pagetitle')
            ,dataIndex: 'pagetitle'
            ,sortable: true
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=resource/update&id=' + record.data.id
                    ,target: '_blank'
                });
            }, scope: this }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,sortable: true
        },{
            header: _('published')
            ,dataIndex: 'published'
            ,width: 30
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
            ,editable: false
            ,sortable: true
        },{
            header: _('deleted')
            ,dataIndex: 'deleted'
            ,width: 30
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
            ,editable: false
            ,sortable: true
        }]
    });
    MODx.grid.Search.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Search,MODx.grid.Grid);
Ext.reg('modx-grid-search',MODx.grid.Search);
