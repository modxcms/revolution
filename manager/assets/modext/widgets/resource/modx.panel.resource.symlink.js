/**
 * @class MODx.panel.SymLink
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-symlink
 */
MODx.panel.SymLink = function(config) {
    config = config || {};
    var it = [];
    it.push({
        title: _('createedit_symlink')
        ,id: 'modx-resource-settings'
        ,layout: 'form'
        ,labelWidth: 200
        ,bodyStyle: 'padding: 15px;'
        ,autoHeight: true
        ,defaults: { border: false ,msgTarget: 'side' ,width: 400 }
        ,items: this.getFields(config)
    });
    
    Ext.applyIf(config,{
        id: 'modx-panel-resource'
        ,class_key: 'modSymLink'
        ,items: this.getFields(config)
    });
    MODx.panel.SymLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.SymLink,MODx.panel.Resource,{
    defaultClassKey: 'modSymLink'
    ,classLexiconKey: 'symlink'
    ,rteElements: false

    ,getPageHeader: function(config) {
        return {
            html: '<h2>'+_('symlink_new')+'</h2>'
            ,id: 'modx-resource-header'
            ,cls: 'modx-page-header'
            ,border: false
        };
    }
    ,getMainFields: function(config) {
        var its = MODx.panel.SymLink.superclass.getMainFields.call(this,config);
        its.push({
            xtype: 'textfield'
            ,fieldLabel: _('symlink')
            ,description: '<b>[[*content]]</b><br />'+_('symlink_help')
            ,name: 'content'
            ,id: 'modx-symlink-content'
            ,maxLength: 255
            ,anchor: '100%'
            ,value: (config.record.content || config.record.ta) || ''
        });
        return its;
    }

    ,getContentField: function(config) {
        return null;
    }
});
Ext.reg('modx-panel-symlink',MODx.panel.SymLink);