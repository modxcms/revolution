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
        ,autoHeight: true
        ,items: [{
            html: '<h2>'+_('search')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'portal'
            ,items: [{
                columnWidth: .97
                ,items: [{
                    title: _('search_criteria')
					,cls: 'x-panel-header'
                    ,layout: 'form'
                    ,border: false
                    ,defaults: {
                        collapsible: false
                        ,autoHeight: true
                        ,bodyStyle: 'padding: 15px;'
                    }
                    ,items: this.getFields()
                },{
                    xtype: 'modx-grid-search'
                    ,preventRender: true
                    ,bodyStyle: 'padding: 0;'
                    ,width: '100.7%'
                }]
            }]
        }]
    });
    MODx.panel.Search.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Search,MODx.FormPanel,{
    filters: {}
    
    ,getFields: function() {
        var lsr = {
            'change': {fn:this.filter,scope: this}
            ,'render': {fn:this._addEnterKeyHandler}
        };
        return [{
            xtype: 'textfield'
            ,name: 'id'
            ,fieldLabel: _('id')
            ,width: 100
            ,listeners: lsr
        },{
            xtype: 'textfield'
            ,name: 'pagetitle'
            ,fieldLabel: _('pagetitle')
            ,width: 300
            ,listeners: lsr
        },{
            xtype: 'textfield'
            ,name: 'longtitle'
            ,fieldLabel: _('long_title')
            ,width: 300
            ,listeners: lsr
        },{
            xtype: 'textarea'
            ,name: 'content'
            ,fieldLabel: _('content')
            ,width: 300
            ,grow: true
            ,listeners: lsr
        },{
            xtype: 'checkbox'
            ,name: 'published'
            ,fieldLabel: _('published')
            ,inputValue: 1
            ,checked: false
            ,handler: this.filter
            ,scope: this
        },{
            xtype: 'checkbox'
            ,name: 'unpublished'
            ,fieldLabel: _('unpublished')
            ,inputValue: 1
            ,checked: false
            ,handler: this.filter
            ,scope: this
        },{
            xtype: 'checkbox'
            ,name: 'deleted'
            ,fieldLabel: _('deleted')
            ,inputValue: 1
            ,checked: false
            ,handler: this.filter
            ,scope: this
        },{
            xtype: 'checkbox'
            ,name: 'undeleted'
            ,fieldLabel: _('undeleted')
            ,inputValue: 1
            ,checked: false
            ,handler: this.filter
            ,scope: this
        }];
    }
    
    ,filter: function(tf,newValue,oldValue) {
        var p = this.getForm().getValues();
        p.action = 'search';
        
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
        ,url: MODx.config.connectors_url+'resource/index.php'
        ,baseParams: {
            action: 'search'
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
        },{
            header: _('description')
            ,dataIndex: 'description'
        },{
            header: _('published')
            ,dataIndex: 'published'
            ,width: 30
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
            ,editable: false
        },{
            header: _('deleted')
            ,dataIndex: 'deleted'
            ,width: 30
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
            ,editable: false
        }]
    });
    MODx.grid.Search.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Search,MODx.grid.Grid);
Ext.reg('modx-grid-search',MODx.grid.Search);