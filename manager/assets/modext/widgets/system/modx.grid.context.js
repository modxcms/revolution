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
        ,fields: ['key','description','perm']
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
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-ctx-search'
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
    MODx.grid.Context.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Context,MODx.grid.Grid,{
    updateContext: function(itm,e) {
        location.href = 'index.php?a='+MODx.action['context/update']+'&key='+this.menu.record.key;
    }
    ,getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.perm;
        var m = [];
        if (p.indexOf('pedit') != -1) {
            m.push({
                text: _('context_update')
                ,handler: this.updateContext
            });
        }
        if (p.indexOf('premove') != -1) {
            m.push('-');
            m.push({
                text: _('context_remove')
                ,handler: this.remove.createDelegate(this,["context_remove_confirm"])
            });
        }
        return m;
    }

    ,search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.search = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        this.getBottomToolbar().changePage(1);
        this.refresh();
        return true;
    }
    ,clearFilter: function() {
    	this.getStore().baseParams = {
            action: 'getList'
    	};
        Ext.getCmp('modx-ctx-search').reset();
    	this.getBottomToolbar().changePage(1);
        this.refresh();
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
            ,anchor: '100%'
            ,maxLength: 100
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,anchor: '100%'
            ,grow: true
        }]
        ,keys: []
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
		,cls: 'container'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('contexts')+'</h2>'
            ,border: false
            ,id: 'modx-contexts-header'
            ,cls: 'modx-page-header'
        },{
            layout: 'form'
            ,items: [{
                html: '<p>'+_('context_management_message')+'</p>'
				,bodyCssClass: 'panel-desc'
                ,border: false
            },{
                xtype: 'modx-grid-contexts'
				,cls:'main-wrapper'
                ,preventRender: true
            }]
        }]
    });
    MODx.panel.Contexts.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Contexts,MODx.FormPanel);
Ext.reg('modx-panel-contexts',MODx.panel.Contexts);
