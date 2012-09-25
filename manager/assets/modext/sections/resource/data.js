/**
 * Loads the resource data page
 * 
 * @class MODx.page.ResourceData
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-resource-data
 */
MODx.page.ResourceData = function(config) {
    config = config || {};
    var btns = [];
    if (config.canEdit == 1) {
        btns.push({
            process: 'edit'
            ,id: 'modx-abtn-edit'
            ,text: _('edit')
            ,hidden: config.canEdit == 1 ? false : true
            ,handler: this.editResource
            ,scope: this
        });
        btns.push('-');
    }
    btns.push({
        process: 'preview'
        ,text: _('view')
        ,handler: this.preview
        ,scope: this
        ,id: 'modx-abtn-preview'
    });
    btns.push('-');
    btns.push({
        process: 'cancel'
        ,text: _('cancel')
        ,handler: this.cancel
        ,scope: this
        ,id: 'modx-abtn-cancel'
    });
    Ext.applyIf(config,{
        form: 'modx-resource-data'
            ,actions: {
            'new': MODx.action['resource/create']
            ,edit: MODx.action['resource/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: btns
        ,components: [{
            xtype: 'modx-panel-resource-data'
            ,renderTo: 'modx-panel-resource-data-div'
            ,resource: config.record.id
            ,context: config.record.context_key
            ,class_key: config.record.class_key
            ,pagetitle: config.record.pagetitle
            ,border: false
            ,url: config.url
        }]
    });
    MODx.page.ResourceData.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ResourceData,MODx.Component,{
    preview: function() {
        window.open(this.config.record.preview_url);
        return false;
    }
    ,editResource: function() {
        location.href = '?a='+MODx.action['resource/update']+'&id='+this.config.record.id;
    }
    ,cancel: function() {
        location.href = '?a='+MODx.action['welcome'];
    }
});
Ext.reg('modx-page-resource-data',MODx.page.ResourceData);