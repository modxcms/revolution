/**
 * Loads the ErrorLog panel
 *
 * @class MODx.panel.ErrorLog
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration options
 * @xtype modx-panel-error-log
 */
MODx.panel.ErrorLog = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,id: 'modx-panel-error-log'
        ,cls: 'container'
        ,baseParams: {
            action: 'System/ErrorLog/Clear'
        }
        ,items: [{
            html: _('error_log')
            ,id: 'modx-error-log-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('error_log')
            ,layout: 'form'
            ,hideLabels: true
            ,autoHeight: true
            ,border: true
            ,items: [{
                html: '<p>'+_('error_log_desc')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'panel'
                ,border: false
                ,cls:'main-wrapper'
                ,layout: 'form'
                ,labelAlign: 'top'
                ,items: [{
                    xtype: 'textarea'
                    ,name: 'log'
                    ,hideLabel: true
                    ,id: 'modx-error-log-content'
                    ,grow: true
                    ,anchor: '100%'
                    ,hidden: config.record.tooLarge ? true : false
                    ,style: 'white-space:pre;overflow: auto;'
                },{
                    html: '<p>'+_('error_log_too_large',{
                        name: config.record.name
                    })+'</p>'
                    ,border: false
                    ,hidden: config.record.tooLarge ? false : true
                },{
                    xtype: 'button'
                    ,text: _('error_log_download',{size: config.record.size})
                    ,cls: 'primary-button'
                    ,style: 'margin-top: 15px;'
                    ,hidden: config.record.tooLarge ? false : true
                    ,handler: this.download
                    ,scope: this
                }]
            }]
        },{
            title: _('deprecated_log')
            ,id: 'modx-panel-deprecated-log'
            ,layout: 'form'
            ,hideLabels: true
            ,autoHeight: true
            ,border: true
            ,items: [{
                html: '<p>'+_('deprecated_log_desc')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-deprecated-log'
                ,id: 'modx-grid-deprecated-log'
                ,border: false
                ,cls:'main-wrapper'
            }]
        }])]
    });
    MODx.panel.ErrorLog.superclass.constructor.call(this,config);
    this.setup();
};
Ext.extend(MODx.panel.ErrorLog,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (this.initialized) { this.clearDirty(); return true; }
        this.getForm().setValues(this.config.record);
        this.clearDirty();
        MODx.fireEvent('ready');
        this.initialized = true;
        return true;
    }
    ,download: function() {
        location.href = this.config.url+'?action=System/ErrorLog/Download&HTTP_MODAUTH='+MODx.siteId;
    }
    /**
     * Set the textarea height to make use of the maximum "space" the client viewport allows
     */
    ,setTextareaHeight: function() {
        var elem = Ext.getCmp('modx-error-log-content');
        // Client viewport visible height
        var clientHeight = document.documentElement.clientHeight || window.innerHeight || document.body.clientHeight
            // Our textarea "top" position
            ,elemTop = elem.el.getTop()
            // The followings are to prevent scrolling if possible (slice is to remove "px" from the values, since we want integers)
            ,wrapperPadding = this.el.select('.main-wrapper').first().getStyle('padding-bottom').slice(0, -2)
            ,containerMargin = this.el.getStyle('margin-bottom').slice(0, -2);

        // Now set our max available height for our textarea
        elem.el.setHeight(clientHeight - elemTop - wrapperPadding - containerMargin);
    }
});
Ext.reg('modx-panel-error-log',MODx.panel.ErrorLog);
