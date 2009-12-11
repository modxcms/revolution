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
        ,bodyStyle: 'padding: 0'
        ,autoHeight: true
        ,items: [{
            html: '<h2>'+_('search')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'portal'
            ,items: [{
                columnWidth: .977
                ,items: [{
                    title: _('search_criteria')
					,cls: 'x-panel-header'
					,style: 'padding: .5em;'
					,bodyStyle: 'text-transform: none; font-weight: Normal;'
                    ,layout: 'form'
                    ,border: false
                    ,defaults: { 
                        collapsible: false
                        ,autoHeight: true
                        ,bodyStyle: 'padding: 1.5em;'
                    }
                    ,items: this.getFields()
                },{
                    xtype: 'modx-grid-search'
                    ,preventRender: true
                    ,bodyStyle: 'padding: 0'
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
        var csr = {'check': {fn:this.filter, scope:this}};
        return [{
            xtype: 'textfield'
            ,name: 'id'
            ,fieldLabel: _('id')
            ,listeners: lsr
        },{
            xtype: 'textfield'
            ,name: 'pagetitle'
            ,fieldLabel: _('pagetitle')
            ,listeners: lsr
        },{
            xtype: 'textfield'
            ,name: 'longtitle'
            ,fieldLabel: _('long_title')
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
            ,listeners: csr
        },{
            xtype: 'checkbox'
            ,name: 'unpublished'
            ,fieldLabel: _('unpublished')
            ,listeners: csr
        },{
            xtype: 'checkbox'
            ,name: 'deleted'
            ,fieldLabel: _('deleted')
            ,listeners: csr
        },{
            xtype: 'checkbox'
            ,name: 'undeleted'
            ,fieldLabel: _('undeleted')
            ,listeners: csr
        }];
    }
    
    ,filter: function(tf,newValue,oldValue) {
        var p = this.getForm().getValues();
        p.start = 0;
        p.limit = 20;
        Ext.getCmp('modx-grid-search').getStore().load({
            params: p
            ,scope: this
        });
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