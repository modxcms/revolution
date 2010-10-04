/**
 * Loads a grid of modContexts.
 * 
 * @class MODx.grid.Context
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-contexts
 */
MODx.grid.Context = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('contexts')
        ,url: MODx.config.connectors_url+'context/index.php'
        ,fields: ['key','description','menu']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,primaryKey: 'key'
        ,columns: [{
            header: _('context_key')
            ,dataIndex: 'key'
            ,width: 150
            ,sortable: true
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 575
            ,sortable: false
            ,editor: { xtype: 'textfield' }
        }]
        ,tbar: [{
                text: _('create_new')
                ,handler: { xtype: 'modx-window-context-create' ,blankValues: true }
        }]
    });
    MODx.grid.Context.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Context,MODx.grid.Grid,{
    update: function(itm,e) {
        location.href = 'index.php?a='+MODx.action['context/update']+'&key='+this.menu.record.key;
    }
});
Ext.reg('modx-grid-contexts',MODx.grid.Context);

/**
 * Generates the create context window.
 *  
 * @class MODx.window.CreateContext
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-context-create
 */
MODx.window.CreateContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('context_create')
        ,url: MODx.config.connectors_url+'context/index.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('context_key')
            ,name: 'key'
            ,anchor: '90%'
            ,maxLength: 100
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,anchor: '90%'
            ,grow: true
        }]
    });
    MODx.window.CreateContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateContext,MODx.Window);
Ext.reg('modx-window-context-create',MODx.window.CreateContext);

/**
 * Loads the Contexts panel
 * 
 * @class MODx.panel.Contexts
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration options
 * @xtype modx-panel-contexts
 */
MODx.panel.Contexts = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-contexts'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('contexts')+'</h2>'
            ,border: false
            ,id: 'modx-contexts-header'
            ,cls: 'modx-page-header'
        },{
            layout: 'form'
            ,bodyStyle: 'padding: 15px'
            ,items: [{
                html: '<p>'+_('context_management_message')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-contexts'
                ,preventRender: true
            }]
        }]
    });
    MODx.panel.Contexts.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Contexts,MODx.FormPanel);
Ext.reg('modx-panel-contexts',MODx.panel.Contexts);