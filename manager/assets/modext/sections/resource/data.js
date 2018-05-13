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
            text: _('edit')
            ,id: 'modx-abtn-edit'
            ,cls: 'primary-button'
            ,hidden: config.canEdit == 1 ? false : true
            ,handler: this.editResource
            ,scope: this
        });
    }
    btns.push({
        text: _('view')
        ,id: 'modx-abtn-preview'
        ,handler: this.preview
        ,scope: this
    });
    btns.push({
        text: _('cancel')
        ,id: 'modx-abtn-cancel'
        ,handler: this.cancel
        ,scope: this
    });
    Ext.applyIf(config,{
        form: 'modx-resource-data'
        ,buttons: btns
        ,components: [{
            xtype: 'modx-panel-resource-data'
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
        MODx.loadPage('resource/update', 'id='+this.config.record.id);
    }
    ,cancel: function() {
        MODx.loadPage('?');
    }
});
Ext.reg('modx-page-resource-data',MODx.page.ResourceData);
