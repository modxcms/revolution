/**
 * Loads a grid of Manager Logs.
 * 
 * @class MODx.grid.ManagerLog
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-manager-log
 */
MODx.grid.ManagerLog = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('manager_log')
        ,id: 'modx-grid-manager-log'
        ,url: MODx.config.connectors_url+'system/log.php'
        ,fields: ['id','user','username','occurred','action','classKey','item','menu']
        ,autosave: true
        ,paging: true
        ,columns: [{
            header: _('occurred')
            ,dataIndex: 'occurred'
            ,width: 125
        },{
            header: _('user')
            ,dataIndex: 'username'
            ,width: 200
            ,editable: false
        },{
            header: _('action')
            ,dataIndex: 'action'
            ,width: 125
        }]
    });
    MODx.grid.ManagerLog.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ManagerLog,MODx.grid.Grid);
Ext.reg('modx-grid-manager-log',MODx.grid.ManagerLog);

/**
 * Loads the Manager Log filter panel.
 * 
 * @class MODx.panel.ManagerLog
 * @extends MODx.FormPanel
 * @constructor
 * @param {Object} config An object of options.
 * @xtype panel-manager-log
 */
MODx.panel.ManagerLog = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-manager-log'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('mgrlog_view')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'manager-log-header'
        },{            
            xtype: 'portal'
            ,items: [{
                columnWidth: 1
                ,items: [{
                    title: _('mgrlog_query')
                    ,layout: 'form'
                    ,bodyStyle: 'padding: 1.5em;'
                    ,items: this.getItems()
                    ,buttonAlign: 'center'
                    ,buttons: [{
                        text: _('filter_clear')
                        ,scope: this
                        ,handler: function() {
                            this.getForm().reset();
                            this.filter();
                        }
                    }]
                },{
                    xtype: 'modx-grid-manager-log'
                    ,bodyStyle: ''
                    ,preventRender: true
                }]
            }]
        }]
    });
    MODx.panel.ManagerLog.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ManagerLog,MODx.FormPanel,{
    /**
     * Gets the items for this panel
     * @return array An array of items 
     */
    getItems: function() {
        var lsr = {
            'change': {fn:this.filter,scope: this}
            ,'render': {fn:this._addEnterKeyHandler}
        };
        return [{
            html: '<p>'+_('mgrlog_query_msg')+'</p>'
            ,border: false
        },{
            xtype: 'modx-combo-user'
            ,fieldLabel: _('user')
            ,name: 'user'
            ,listeners: {
                'select': {fn: this.filter, scope: this}
            }
        },{
            xtype: 'textfield'
            ,fieldLabel: _('action')
            ,name: 'action_type'
            ,listeners: lsr
        },{
            xtype: 'datefield'
            ,fieldLabel: _('date_start')
            ,name: 'date_start'
            ,allowBlank: true
            ,listeners: lsr
        },{
            xtype: 'datefield'
            ,fieldLabel: _('date_end')
            ,name: 'date_end'
            ,allowBlank: true
            ,listeners: lsr
        }];
    }
    /**
     * Filters the grid via the panel fields
     * @param {Ext.form.Field} tf
     * @param {String} newValue
     * @param {String} oldValue
     */
    ,filter: function(tf,newValue,oldValue) {
        var p = this.getForm().getValues();
        var g = Ext.getCmp('modx-grid-manager-log');
        p.action = 'getList';
        g.getStore().baseParams = p;
        g.refresh();
        g.getBottomToolbar().changePage(1);
    }
    /**
     * Adds an enter key handler to a field
     */
    ,_addEnterKeyHandler: function() {
        this.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
            this.fireEvent('change'); 
        },this);
    }
});
Ext.reg('modx-panel-manager-log',MODx.panel.ManagerLog);