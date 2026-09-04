MODx.page.SystemDefinitions = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{xtype: 'modx-panel-system-definitions', releaseHash: config.releaseHash}]
    });
    MODx.page.SystemDefinitions.superclass.constructor.call(this, config);
};
Ext.extend(MODx.page.SystemDefinitions, MODx.Component);
Ext.reg('modx-page-system-definitions', MODx.page.SystemDefinitions);

MODx.grid.DefinitionRegistry = function(config) {
    config = config || {};
    var encode = function(value) {
        return Ext.util.Format.htmlEncode(value === null || value === undefined ? '' : String(value));
    };
    var yesNo = function(value) {
        return value === null || value === undefined ? '' : (value ? _('yes') : _('no'));
    };
    Ext.applyIf(config, {
        id: 'modx-grid-definition-registry',
        cls: 'main-wrapper',
        border: false,
        autoHeight: true,
        loadMask: true,
        url: MODx.config.connector_url,
        baseParams: {action: 'System/Definition/GetList', kind: 'elements'},
        fields: ['kind', 'type', 'name', 'key', 'package', 'source', 'manifest', 'source_file',
            'release_hash', 'collision', 'collision_state', 'database_disabled', 'event', 'priority', 'contexts', 'target'],
        paging: true,
        pageSize: parseInt(MODx.config.default_per_page, 10) || 20,
        remoteSort: true,
        showActionsColumn: false,
        columns: [{header: _('definition_kind'), dataIndex: 'kind', width: 90, sortable: true, renderer: encode},
            {header: _('definition_name'), dataIndex: 'name', width: 160, sortable: true, renderer: encode},
            {header: _('definition_key'), dataIndex: 'key', width: 280, sortable: true, renderer: encode},
            {header: _('definition_package'), dataIndex: 'package', width: 150, sortable: true, renderer: encode},
            {header: _('definition_source'), dataIndex: 'source', width: 80, renderer: encode},
            {header: _('definition_manifest'), dataIndex: 'manifest', width: 240, renderer: encode},
            {header: _('definition_source_file'), dataIndex: 'source_file', width: 240, renderer: encode},
            {header: _('definition_event'), dataIndex: 'event', width: 160, renderer: encode},
            {header: _('definition_priority'), dataIndex: 'priority', width: 80},
            {header: _('definition_contexts'), dataIndex: 'contexts', width: 140,
                renderer: function(value) {
                    return Ext.util.Format.htmlEncode(Ext.isArray(value) ? value.join(', ') : (value || ''));
                }},
            {header: _('definition_target'), dataIndex: 'target', width: 220, renderer: encode},
            {header: _('definition_collision'), dataIndex: 'collision', width: 90,
                renderer: yesNo},
            {header: _('definition_database_disabled'), dataIndex: 'database_disabled', width: 130,
                renderer: yesNo},
            {header: _('definition_decision'), dataIndex: 'collision_state', width: 170,
                sortable: true, renderer: encode}],
        viewConfig: {forceFit: false, emptyText: _('definition_registry_empty')},
        tbar: [{xtype: 'label', text: _('definition_kind') + ':'}, {
            xtype: 'combo', width: 130, editable: false, triggerAction: 'all', mode: 'local',
            value: 'elements', store: [['elements', _('definition_elements')], ['events', _('definition_events')],
                ['listeners', _('definition_listeners')]],
            listeners: {select: function(combo) {
                this.getStore().baseParams.kind = combo.getValue();
                if (this.getBottomToolbar()) {
                    this.getBottomToolbar().changePage(1);
                    return;
                }
                this.getStore().load({params: {start: 0, limit: this.config.pageSize}});
            }, scope: this}
        }, '->', {text: _('ext_refresh'), handler: function() { this.store.reload(); }, scope: this}]
    });
    MODx.grid.DefinitionRegistry.superclass.constructor.call(this, config);
};
Ext.extend(MODx.grid.DefinitionRegistry, MODx.grid.Grid);
Ext.reg('modx-grid-definition-registry', MODx.grid.DefinitionRegistry);

MODx.panel.SystemDefinitions = function(config) {
    config = config || {};
    Ext.applyIf(config, {id: 'modx-panel-system-definitions', layout: 'anchor', cls: 'container',
        items: [{html: _('definition_registry_intro') + '<br/><small>' + _('definition_release_hash') + ': '
            + Ext.util.Format.htmlEncode(config.releaseHash || '') + '</small>', xtype: 'modx-description'},
            {xtype: 'modx-grid-definition-registry', anchor: '100%'}]});
    MODx.panel.SystemDefinitions.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.SystemDefinitions, MODx.Panel);
Ext.reg('modx-panel-system-definitions', MODx.panel.SystemDefinitions);
