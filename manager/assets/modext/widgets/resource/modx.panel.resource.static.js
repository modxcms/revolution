/**
 * @class MODx.panel.Static
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype modx-panel-static
 */
MODx.panel.Static = function(config) {
    config = config || {record:{}};
    config.record = config.record || {};
    config.default_title = config.default_title || _('static_resource_new');
    Ext.applyIf(config,{
        id: 'modx-panel-resource'
        ,class_key: 'MODX\\Revolution\\modStaticResource'
        ,items: this.getFields(config)
    });
    MODx.panel.Static.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Static,MODx.panel.Resource,{
    defaultClassKey: 'MODX\\Revolution\\modStaticResource'
    ,classLexiconKey: 'static_resource'
    ,rteElements: false
    ,contentField: 'modx-resource-content'

    ,getContentField: function(config) {
        return {
            xtype: 'modx-combo-browser'
            ,browserEl: 'modx-browser'
            ,prependPath: false
            ,prependUrl: false
            ,fieldLabel: _('static_resource')
            ,description: '<b>[[*content]]</b>'
            ,name: 'content'
            ,id: 'modx-resource-content'
            ,maxLength: 255
            ,anchor: '100%'
            ,value: (config.record.content || config.record.ta) || ''
            ,openTo: config.record.openTo
            ,listeners: {
                'select':{fn:function(data) {
                    var str = data.fullRelativeUrl;
                    if (MODx.config.base_url != '/') {
                        var regex = new RegExp('^' + MODx.config.base_url + '(.*)');
                        str = str.replace(regex, '/$1');
                    }
                    if (str.substring(0,1) === '/') { str = str.substring(1); }
                    Ext.getCmp('modx-resource-content').setValue(str);
                    this.markDirty();
                },scope:this}
            }
        };
    }
});
Ext.reg('modx-panel-static',MODx.panel.Static);
