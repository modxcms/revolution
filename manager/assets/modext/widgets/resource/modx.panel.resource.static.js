/**
 * @class MODx.panel.Static
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype modx-panel-static
 */
MODx.panel.Static = function(config) {
    config = config || {record:{}};
    config.record = config.record || {};
    Ext.applyIf(config,{
        id: 'modx-panel-resource'
        ,class_key: 'modStaticResource'
        ,items: this.getFields(config)
    });
    MODx.panel.Static.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Static,MODx.panel.Resource,{
    defaultClassKey: 'modStaticResource'
    ,classLexiconKey: 'static_resource'
    ,rteElements: false

    ,getPageHeader: function(config) {
        config = config || {record:{}};
        return {
            html: _('static_resource_new')
            ,id: 'modx-resource-header'
            ,xtype: 'modx-header'
        };
    }
    ,getMainFields: function(config) {
        var its = MODx.panel.Static.superclass.getMainFields.call(this,config);
        its.push({
            xtype: 'modx-combo-browser'
            ,browserEl: 'modx-browser'
            ,prependPath: false
            ,prependUrl: false
            // ,hideFiles: true
            ,fieldLabel: _('static_resource')
            ,description: '<b>[[*content]]</b>'
            ,name: 'content'
            ,id: 'modx-resource-content-static' // changed id to not have to usual box-shadow around the content field
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
                    if (str.substring(0,1) == '/') { str = str.substring(1); }
                    Ext.getCmp('modx-resource-content-static').setValue(str);
                    this.markDirty();
                },scope:this}
            }
        });
        return its;
    }

    ,getContentField: function(config) {
        return null;
    }
});
Ext.reg('modx-panel-static',MODx.panel.Static);
