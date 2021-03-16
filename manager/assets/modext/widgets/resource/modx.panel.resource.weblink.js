/**
 * @class MODx.panel.WebLink
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-weblink
 */
MODx.panel.WebLink = function(config) {
    config = config || {};
    config.default_title = config.default_title || _('weblink_new');
    Ext.applyIf(config,{
        id: 'modx-panel-resource'
        ,class_key: 'MODX\\Revolution\\modWebLink'
        ,items: this.getFields(config)
    });
    MODx.panel.WebLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.WebLink,MODx.panel.Resource,{
    defaultClassKey: 'MODX\\Revolution\\modWebLink'
    ,classLexiconKey: 'weblink'
    ,rteElements: false
    ,contentField: 'modx-weblink-content'

    ,getContentField: function(config) {
        return {
            xtype: 'textfield'
            ,fieldLabel: _('weblink')
            ,description: '<b>[[*content]]</b><br />'+_('weblink_help')
            ,name: 'content'
            ,id: 'modx-weblink-content'
            ,anchor: '100%'
            ,value: (config.record.content || config.record.ta) || ''
        };
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
