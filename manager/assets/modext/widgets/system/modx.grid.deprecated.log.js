/**
 * Loads a grid for managing Settings.
 *
 * @class MODx.grid.DeprecatedLogGrid
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-settings
 */
MODx.grid.DeprecatedLogGrid = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        columns: [{
            header: _('deprecated_caller')
            ,dataIndex: 'caller'
            ,sortable: true
            ,editable: false
            ,width: 150
        },{
            header: _('deprecated_caller_file')
            ,dataIndex: 'caller_file'
            ,sortable: true
            ,editable: false
            ,width: 150
            ,renderer: function(value, metaData, record) {
                return Ext.util.Format.htmlEncode(value) + ' : ' + Ext.util.Format.htmlEncode(record.data.caller_line);
            }
        },{
            header: _('deprecated_caller_line')
            ,dataIndex: 'caller_line'
            ,sortable: true
            ,editable: false
            ,width: 50
            ,hidden: true
        },{
            header: _('deprecated_call_count')
            ,dataIndex: 'call_count'
            ,sortable: false
            ,editable: false
            ,width: 50
        },{
            header: _('deprecated_definition')
            ,dataIndex: 'method_definition'
            ,sortable: false
            ,editable: false
            ,width: 50
            ,hidden: true
        }]
        ,fields: ['id', 'method', 'call_count', 'caller', 'caller_file', 'caller_line', 'method_id', 'method_definition', 'method_since', 'method_recommendation']
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'System/DeprecatedLog/GetList'
        }
        ,showActionsColumn: false
        ,grouping: true
        ,groupBy: 'method_definition'
        ,singleText: _('setting')
        ,pluralText: _('settings')
        ,sortBy: 'call_count'
        ,sortDir: 'desc'
        ,pageSize: parseInt(MODx.config.default_per_page) || 20
        ,paging: true

        ,view: new Ext.grid.GroupingView({
            emptyText: config.emptyText || _('ext_emptymsg')
            ,forceFit: true
            ,autoFill: true
            ,showPreview: true
            ,enableRowBody: true
            ,scrollOffset: 0
            ,groupTextTpl: '{group:htmlEncode}<br>' +
                '<tpl if="values.rs[0].data.method_since"><span style="font-weight: normal;">{[_("deprecated_since")]}</span> v{[Ext.util.Format.htmlEncode(values.rs[0].data.method_since)]} | </tpl>' +
                '<span style="font-weight: normal;">{[Ext.util.Format.htmlEncode(values.rs[0].data.method_recommendation)]}</span>'
        }),

        tbar: [{
            text: _('clear')
            ,id: 'modx-abtn-clear'
            ,cls: 'primary-button'
            ,handler: this.clearDeprecatedLog
            ,scope: this
            ,hidden: !MODx.perm.error_log_erase
        }]
    });

    MODx.grid.DeprecatedLogGrid.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.DeprecatedLogGrid, MODx.grid.Grid, {
    clearDeprecatedLog: function() {
        var panel = Ext.getCmp('modx-panel-deprecated-log');
        panel.el.mask(_('working'));
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'System/DeprecatedLog/Clear'
            }
            ,listeners: {
                'success': {fn:function(r) {
                        panel.el.unmask();
                        // refresh grid
                        this.refresh();
                    },scope: this}
            }
        });
    }
});
Ext.reg('modx-grid-deprecated-log', MODx.grid.DeprecatedLogGrid);
