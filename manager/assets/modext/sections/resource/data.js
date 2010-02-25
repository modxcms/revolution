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
            ,text: _('edit')
            ,params: { a: MODx.action['resource/update'], id: config.id }
            ,hidden: config.canEdit == 1 ? false : true
        });
        btns.push('-');
    }
    btns.push({
        process: 'preview'
        ,text: _('preview')
        ,handler: this.preview.createDelegate(this,[config.id])
        ,scope: this
    });
    btns.push('-');
    btns.push({
        process: 'cancel'
        ,text: _('cancel')
        ,params: { a: MODx.action['welcome'] }
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
            ,resource: config.id
            ,context: config.ctx
            ,class_key: config.class_key
            ,pagetitle: config.pagetitle
        }]
	});
	MODx.page.ResourceData.superclass.constructor.call(this,config);	
};
Ext.extend(MODx.page.ResourceData,MODx.Component,{
    preview: function(id) {
        window.open(MODx.config.base_url+'index.php?id='+id);
        return false;
    }
});
Ext.reg('modx-page-resource-data',MODx.page.ResourceData);