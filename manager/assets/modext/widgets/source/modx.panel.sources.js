/**
 * Loads the Sources panel
 *
 * @class MODx.panel.Sources
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration options
 * @xtype modx-panel-sources
 */
MODx.panel.Sources = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-sources'
        ,cls: 'container'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: _('sources')
            ,id: 'modx-sources-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('sources')
            ,layout: 'form'
            ,items: [{
                html: '<p>'+_('sources.intro_msg')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-sources'
                ,urlFilters: ['query']
                ,cls: 'main-wrapper'
                ,preventRender: true
            }]
        },{
            layout: 'form'
            ,title: _('source_types')
            ,items: [{
                html: '<p>'+_('source_types.intro_msg')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-source-types'
                ,cls: 'main-wrapper'
                ,preventRender: true
            }]
        }],{
            stateful: true
            ,stateId: 'modx-sources-tabpanel'
            ,stateEvents: ['tabchange']
            ,getState:function() {
                return {activeTab:this.items.indexOf(this.getActiveTab())};
            }
        })]
    });
    MODx.panel.Sources.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Sources,MODx.FormPanel);
Ext.reg('modx-panel-sources',MODx.panel.Sources);

/**
 * Loads a grid of Sources.
 *
 * @class MODx.grid.Sources
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-sources
 */
MODx.grid.Sources = function(config) {
    config = config || {};

    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'Source/GetList'
        }
        ,fields: ['id','name','description','class_key','cls']
        ,paging: true
        ,autosave: true
        ,save_action: 'Source/UpdateFromGrid'
        ,remoteSort: true
        ,sm: this.sm
        ,columns: [this.sm,{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 50
            ,sortable: true
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,sortable: true
            ,editor: { xtype: 'textfield' ,allowBlank: false }
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=source/update&id=' + record.data.id
                });
            }, scope: this }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 300
            ,sortable: false
            ,editor: { xtype: 'textarea' }
            ,renderer: Ext.util.Format.htmlEncode
        }]
        ,tbar: [{
            text: _('create')
            ,handler: { xtype: 'modx-window-source-create' ,blankValues: true }
            ,cls:'primary-button'
        },{
            text: _('bulk_actions')
            ,menu: [{
                text: _('selected_remove')
                ,handler: this.removeSelected
                ,scope: this
            }]
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-source-search'
            ,cls: 'x-form-filter'
            ,emptyText: _('search')
            ,value: MODx.request.query
            ,listeners: {
                'change': {
                    fn: function (cb, rec, ri) {
                        this.sourceSearch(cb, rec, ri);
                    }
                    ,scope: this
                },
                'afterrender': {
                    fn: function (cb){
                        if (MODx.request.query) {
                            this.sourceSearch(cb, cb.value);
                            MODx.request.query = '';
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
            ,text: _('filter_clear')
            ,id: 'modx-filter-clear'
            ,cls: 'x-form-filter-clear'
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this},
                'mouseout': { fn: function(evt){
                    this.removeClass('x-btn-focus');
                }
                }
            }
        }]
    });
    MODx.grid.Sources.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Sources,MODx.grid.Grid,{
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.cls;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
            m.push({
                text: _('selected_remove')
                ,handler: this.removeSelected
                ,scope: this
            });
        } else {
            if (p.indexOf('pupdate') != -1) {
                m.push({
                    text: _('edit')
                    ,handler: this.updateSource
                });
            }
            if (p.indexOf('pduplicate') != -1) {
                m.push({
                    text: _('duplicate')
                    ,handler: this.duplicateSource
                });
            }
            if (p.indexOf('premove') != -1 && r.data.id != 1 && r.data.name != 'Filesystem') {
                if (m.length > 0) m.push('-');
                m.push({
                    text: _('delete')
                    ,handler: this.removeSource
                });
            }
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }

    ,createSource: function() {
        MODx.loadPage('system/source/create');
    }

    ,updateSource: function() {
        MODx.loadPage('source/update', 'id='+this.menu.record.id);
    }

    ,duplicateSource: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'Source/Duplicate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,removeSource: function() {
        MODx.msg.confirm({
            title: _('delete')
            ,text: _('source_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'Source/Remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,removeSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('selected_remove')
            ,text: _('source_remove_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'Source/RemoveMultiple'
                ,sources: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }

    ,sourceSearch: function(tf,newValue,oldValue) {
        var s = this.getStore();
        s.baseParams.query = newValue;
        this.replaceState();
        this.getBottomToolbar().changePage(1);
    }

    ,clearFilter: function() {
        var s = this.getStore();
        var sourceSearch = Ext.getCmp('modx-source-search');
        s.baseParams = {
            action: 'Source/GetList'
        };
        MODx.request.query = '';
        sourceSearch.setValue('');
        this.replaceState();
        this.getBottomToolbar().changePage(1);
    }
});
Ext.reg('modx-grid-sources',MODx.grid.Sources);

/**
 * Generates the create Source window.
 *
 * @class MODx.window.CreateSource
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-source-create
 */
MODx.window.CreateSource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('create')
        ,url: MODx.config.connector_url
        ,autoHeight: true
        ,action: 'Source/Create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,anchor: '100%'
            ,grow: true
        },{
            name: 'class_key'
            ,hiddenName: 'class_key'
            ,xtype: 'modx-combo-source-type'
            ,fieldLabel: _('source_type')
            ,anchor: '100%'
            ,allowBlank: false
            ,value: MODx.config.default_media_source_type
        }]
        ,keys: []
    });
    MODx.window.CreateSource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateSource,MODx.Window);
Ext.reg('modx-window-source-create',MODx.window.CreateSource);

MODx.grid.SourceTypes = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'Source/Type/GetList'
        }
        ,fields: ['class','name','description']
        ,showActionsColumn: false
        ,paging: true
        ,remoteSort: true
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,sortable: true
            ,renderer: Ext.util.Format.htmlEncode
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 300
            ,sortable: false
            ,renderer: Ext.util.Format.htmlEncode
        }]
    });
    MODx.grid.SourceTypes.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.SourceTypes,MODx.grid.Grid);
Ext.reg('modx-grid-source-types',MODx.grid.SourceTypes);
