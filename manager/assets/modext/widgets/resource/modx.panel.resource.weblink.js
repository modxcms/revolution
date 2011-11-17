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
            html: '<h2>'+_('weblink_new')+'</h2>'
            ,id: 'modx-resource-header'
            ,cls: 'modx-page-header'
            ,border: false
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
});
Ext.reg('modx-panel-weblink',MODx.panel.WebLink);