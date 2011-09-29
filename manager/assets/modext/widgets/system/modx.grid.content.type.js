/**
 * @class MODx.panel.ContentType
 * @extends MODx.FormPanel
 * @param {Object} config An object of options.
 * @xtype modx-panel-content-type
 */
MODx.panel.ContentType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-content-type'
		,cls: 'container'
        ,url: MODx.config.connectors_url+'system/contenttype.php'
        ,baseParams: {
            action: 'updateFromGrid'
        }
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('content_types')+'</h2>'
            ,cls: 'modx-page-header'
            ,itemId: 'header'
            ,border: false
        },{
            layout: 'form'
            ,itemId: 'form'
            ,items: [{
                html: '<p>'+_('content_type_desc')+'</p>'
				,bodyCssClass: 'panel-desc'
                ,itemId: 'description'
                ,border: false
            },{
                xtype: 'modx-grid-content-type'
                ,itemId: 'grid'
				,cls:'main-wrapper'
                ,preventRender: true
            }]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.ContentType.superclass.constructor.call(this,config);  
};
Ext.extend(MODx.panel.ContentType,MODx.FormPanel,{
    initialized: false
    ,setup: function() {}
    ,beforeSubmit: function(o) {
        var g = this.getComponent('form').getComponent('grid');
        Ext.apply(o.form.baseParams,{
            data: g.encodeModified()
        });
    }
    ,success: function(o) {
        this.getComponent('form').getComponent('grid').getStore().commitChanges();
    }
});
Ext.reg('modx-panel-content-type',MODx.panel.ContentType);

/**
 * Loads a grid of content types
 * 
 * @class MODx.grid.ContentType
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-contenttype
 */
MODx.grid.ContentType = function(config) {
    config = config || {};
    var binaryColumn = new Ext.ux.grid.CheckColumn({
        header: _('binary')
        ,dataIndex: 'binary'
        ,width: 40
        ,sortable: true
    });

    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'system/contenttype.php'
        ,fields: ['id','name','mime_type','file_extensions','headers','binary','description']
        ,paging: true
        ,remoteSort: true
        ,plugins: binaryColumn
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 50
            ,sortable: true
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,sortable: true
            ,editor: { xtype: 'textfield' }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,editor: { xtype: 'textfield' }
            ,width: 200
        },{
            header: _('mime_type')
            ,dataIndex: 'mime_type'
            ,sortable: true
            ,editor: { xtype: 'textfield' }
            ,width: 80
        },{
            header: _('file_extensions')
            ,dataIndex: 'file_extensions'
            ,sortable: true
            ,editor: { xtype: 'textfield' }
        },binaryColumn]
        ,tbar: [{
            text: _('content_type_new')
            ,handler: { xtype: 'modx-window-content-type-create' ,blankValues: true }
        }]
    });
    MODx.grid.ContentType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ContentType,MODx.grid.Grid,{
    getMenu: function() {
        var m = [];
        m.push({
            text: _('content_type_remove')
            ,handler: this.confirm.createDelegate(this,["remove",_('content_type_remove_confirm')])
        })
        this.menu.record.menu = m;
    }
});
Ext.reg('modx-grid-content-type',MODx.grid.ContentType);


/** 
 * Generates the ContentType window.
 *  
 * @class MODx.window.ContentType
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-contenttype-create
 */
MODx.window.CreateContentType = function(config) {
    config = config || {};
    this.ident = config.ident || 'cct'+Ext.id();
    Ext.applyIf(config,{
        title: _('content_type_new')
        ,width: 350
        ,url: MODx.config.connectors_url+'system/contenttype.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,xtype: 'textfield'
            ,width: 200
            ,allowBlank: false
        },{
            fieldLabel: _('mime_type')
            ,name: 'mime_type'
            ,id: 'modx-'+this.ident+'-mime-type'
            ,xtype: 'textfield'
            ,description: _('mime_type_desc')
            ,width: 200
            ,allowBlank: false
        },{
            fieldLabel: _('file_extensions')
            ,name: 'file_extensions'
            ,id: 'modx-'+this.ident+'-file-extensions'
            ,xtype: 'textfield'
            ,description: _('file_extensions_desc')
            ,width: 200
            ,allowBlank: false
        },{
            xtype: 'combo-boolean'
            ,fieldLabel: _('binary')
            ,name: 'binary'
            ,hiddenName: 'binary'
            ,id: 'modx-'+this.ident+'-binary'
            ,description: _('binary_desc')
            ,width: 60
            ,value: 0
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,xtype: 'textarea'
            ,width: 200
            ,grow: true
        }]
    });
    MODx.window.CreateContentType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateContentType,MODx.Window);
Ext.reg('modx-window-content-type-create',MODx.window.CreateContentType);