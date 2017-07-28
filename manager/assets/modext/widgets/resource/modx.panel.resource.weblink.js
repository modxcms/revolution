/**
 * @class MODx.panel.WebLink
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-weblink
 */
MODx.panel.WebLink = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-resource'
        ,class_key: 'modWebLink'
        ,items: this.getFields(config)
    });
    MODx.panel.WebLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.WebLink,MODx.panel.Resource,{
    defaultClassKey: 'modWebLink'
    ,classLexiconKey: 'weblink'
    ,rteElements: false

    ,getPageHeader: function(config) {
        return {
            html: _('weblink_new')
            ,id: 'modx-resource-header'
            ,xtype: 'modx-header'
        };
    }
    ,getMainFields: function(config) {
        var its = MODx.panel.WebLink.superclass.getMainFields.call(this,config);
        its.push({
            xtype: 'textfield'
            ,fieldLabel: _('weblink')
            ,description: '<b>[[*content]]</b><br />'+_('weblink_help')
            ,name: 'content'
            ,id: 'modx-weblink-content'
            ,anchor: '100%'
            ,value: (config.record.content || config.record.ta) || 'http://'
        });
        return its;
    }

    ,getContentField: function(config) {
        return null;
    }
    ,getSettingLeftFields: function(config) {
        var its = MODx.panel.WebLink.superclass.getSettingLeftFields.call(this,config);
        its.push({
            xtype: 'textfield'
            ,fieldLabel: _('weblink_response_code')
            ,description: _('weblink_response_code_help')
            ,name: 'responseCode'
            ,id: 'modx-weblink-responseCode'
            ,anchor: '100%'
            ,value: (config.record.responseCode) || 'HTTP/1.1 301 Moved Permanently'
        });
        return its;
    }
});
Ext.reg('modx-panel-weblink',MODx.panel.WebLink);
