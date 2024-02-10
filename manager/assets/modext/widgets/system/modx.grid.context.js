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
            html: _('contexts')
            ,id: 'modx-contexts-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('contexts')
            ,layout: 'form'
            ,items: [{
                html: '<p>'+_('context_management_message')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-contexts'
                ,urlFilters: ['search']
                ,cls:'main-wrapper'
                ,preventRender: true
            }]
        }])]
    });
    MODx.panel.Contexts.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Contexts,MODx.FormPanel);
Ext.reg('modx-panel-contexts',MODx.panel.Contexts);

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
        ,id: 'modx-grid-context'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Context/GetList'
        }
        ,fields: ['key','name','description','perm', 'rank']
        ,paging: true
        ,autosave: true
        ,save_action: 'Context/UpdateFromGrid'
        ,remoteSort: true
        ,primaryKey: 'key'
        ,columns: [{
            header: _('key')
            ,dataIndex: 'key'
            ,width: 100
            ,sortable: true
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,sortable: true
            ,editor: { xtype: 'textfield' }
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=context/update&key=' + record.data.key
                });
            }, scope: this }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 575
            ,sortable: false
            ,editor: { xtype: 'textarea' }
        },{
            header: _('rank')
            ,dataIndex: 'rank'
            ,width: 100
            ,sortable: true
            ,editor: { xtype: 'numberfield' }
        }]
        ,tbar: [{
            text: _('create')
            ,cls:'primary-button'
            ,handler: this.create
            ,scope: this
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-ctx-search'
            ,cls: 'x-form-filter'
            ,emptyText: _('search')
            ,value: MODx.request.search
            ,listeners: {
                'change': {
                    fn: function (cb, rec, ri) {
                        this.ctxSearch(cb, rec, ri);
                    }
                    ,scope: this
                },
                'afterrender': {
                    fn: function (cb){
                        if (MODx.request.search) {
                            this.ctxSearch(cb, cb.value);
                            MODx.request.search = '';
                        }
                    }
                    ,scope: this
                }
                ,'render': {
                    fn: function(cmp) {
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
    MODx.grid.Context.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Context,MODx.grid.Grid,{
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.perm;
        var m = [];
        if (p.indexOf('pnew') != -1) {
            m.push({
                text: _('duplicate')
                ,handler: this.duplicateContext
                ,scope: this
            });
        }

        if (p.indexOf('pedit') != -1) {
            m.push({
                text: _('edit')
                ,handler: this.updateContext
            });
        }

        if (p.indexOf('premove') != -1) {
            m.push('-');
            m.push({
                text: _('delete')
                ,handler: this.remove
                ,scope: this
            });
        }
        return m;
    }

    ,create: function(btn, e) {
        if (this.createWindow) {
            this.createWindow.destroy();
        }
        this.createWindow = MODx.load({
            xtype: 'modx-window-context-create',
            closeAction:'close',
            listeners: {
                'success': {
                    fn: function() {
                        this.afterAction();
                    },
                    scope: this
                }
            }
        });
        this.createWindow.show(e.target);
    }

    ,updateContext: function(itm,e) {
        MODx.loadPage('context/update', 'key='+this.menu.record.key);
    }

    ,duplicateContext: function() {
        var r = {
            key: this.menu.record.key
            ,newkey: ''
        };
        var w = MODx.load({
            xtype: 'modx-window-context-duplicate'
            ,record: r
            ,listeners: {
                'success': {fn:function() {
                    this.refresh();
                    var tree = Ext.getCmp('modx-resource-tree');
                    if (tree) {
                        tree.refresh();
                    }
                },scope:this}
            }
        });
        w.show();
    }

    ,remove: function(btn, e) {
        MODx.msg.confirm({
            title: _('warning'),
            text: _('context_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'Context/Remove',
                key: this.menu.record.key
            },
            listeners: {
                'success': {
                    fn: function() {
                        this.afterAction();
                    },
                    scope: this
                }
            }
        });
    }

    ,ctxSearch: function(tf,newValue,oldValue) {
        var s = this.getStore();
        s.baseParams.search = newValue;
        this.replaceState();
        this.getBottomToolbar().changePage(1);
    }

    ,clearFilter: function() {
        var s = this.getStore();
        var ctxSearch = Ext.getCmp('modx-ctx-search');
        s.baseParams = {
            action: 'Context/GetList'
        };
        MODx.request.search = '';
        ctxSearch.setValue('');
        this.replaceState();
        this.getBottomToolbar().changePage(1);
    }

    ,afterAction: function() {
        var cmp = Ext.getCmp('modx-resource-tree');
        if (cmp) {
            cmp.refresh();
        }
        this.getSelectionModel().clearSelections(true);
        this.refresh();
    }

    ,getActions: function(record, rowIndex, colIndex, store) {
        var permissions = record.data.perm;
        var actions = [];

        if (~permissions.indexOf('pedit')) {
            actions.push({
                action: 'updateContext',
                icon: 'pencil-square-o',
                text: _('edit')
            });
        }

        if (~permissions.indexOf('premove')) {
            actions.push({
                action: 'remove',
                icon: 'trash-o',
                text: _('delete')
            });
        }

        return actions;
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
        title: _('create')
        ,url: MODx.config.connector_url
        ,action: 'Context/Create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('context_key')
            ,name: 'key'
            ,anchor: '100%'
            ,maxLength: 100
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,anchor: '100%'
            ,maxLength: 100
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,anchor: '100%'
            ,grow: true
        },{
            xtype: 'numberfield'
            ,fieldLabel: _('rank')
            ,name: 'rank'
            ,allowBlank: true
            ,anchor: '100%'
        }]
        ,keys: []
    });
    MODx.window.CreateContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateContext,MODx.Window);
Ext.reg('modx-window-context-create',MODx.window.CreateContext);
