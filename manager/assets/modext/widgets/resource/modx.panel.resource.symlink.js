/**
 * @class MODx.panel.SymLink
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-symlink
 */
MODx.panel.SymLink = function(config) {
    config = config || {};
    config.default_title = config.default_title || _('symlink_new');
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
        ,class_key: 'MODX\\Revolution\\modSymLink'
        ,items: this.getFields(config)
    });
    MODx.panel.SymLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.SymLink,MODx.panel.Resource,{
    defaultClassKey: 'MODX\\Revolution\\modSymLink'
    ,classLexiconKey: 'symlink'
    ,rteElements: false
    ,contentField: 'modx-symlink-content'

    ,getContentField: function(config) {
        return {
            xtype: 'textfield'
            ,fieldLabel: _('symlink')
            ,description: '<b>[[*content]]</b><br />'+_('symlink_help')
            ,name: 'content'
            ,id: 'modx-symlink-content'
            ,maxLength: 255
            ,anchor: '100%'
            ,value: (config.record.content || config.record.ta) || ''
        };
    }
});
Ext.reg('modx-panel-symlink',MODx.panel.SymLink);
