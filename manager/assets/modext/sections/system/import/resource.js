Ext.onReady(function() {
    MODx.load({ xtype: 'modx-page-import-resource' });
});

/**
 * @class MODx.page.ImportResource
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-page-import-resource
 */
MODx.page.ImportResource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        form: 'modx-import-site'
        ,fields: {
            import_element: {
                xtype: 'textfield'
                ,width: 200
                ,applyTo: 'modx-import-element'
                ,value: 'body'
            }
            ,import_base_path: {
                xtype: 'textfield'
                ,width: 200
                ,applyTo: 'modx-import-base-path'
                ,value: ''
            }
            ,import_resource_class: {
                xtype: 'textfield'
                ,width: 200
                ,applyTo: 'modx-import-resource-class'
                ,value: 'modStaticResource'
            }
            ,import_allowed_extensions: {
                xtype: 'textfield'
                ,width: 200
                ,applyTo: 'modx-import-allowed-extensions'
                ,value: ''
            }
            ,import_context: {
                xtype: 'hidden'
                ,id: 'modx-import-context'
                ,applyTo: 'modx-import-context'
                ,value: 'web'
            }
            ,import_parent: {
                xtype: 'hidden'
                ,id: 'modx-import-parent'
                ,applyTo: 'modx-import-parent'
                ,value: '0'
            }
        }
        ,buttons: [{
            process: 'import', text: _('import_resources'), method: 'remote'
            ,onComplete: function(o,i,r) {
                Ext.get('modx-import-results').update(r.message);
            }
        },{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['welcome']}
        }]
        ,components: [{
            xtype: 'modx-tree-resource-simple'
            ,title: _('resources')
            ,id: 'modx-import-tree'
            ,el: 'modx-import-resource-tree'
            ,url: MODx.config.connectors_url+'resource/index.php'
            ,enableDrop: false
            ,rootVisible: false
        }]
    });
    MODx.page.ImportResource.superclass.constructor.call(this,config);
    this.setup();
};
Ext.extend(MODx.page.ImportResource,MODx.Component,{
    setup: function() {
        Ext.Ajax.timeout = 0;
        var t = Ext.getCmp('modx-import-tree');
        t.getSelectionModel().on('selectionchange',this.handleClick,t);
    }
    
    ,handleClick: function() {
        var iPar = 0;
        var iCxt = 'web';
        var s = this.getSelectionModel().getSelectedNode();
        if (s) {
            var spl = s.attributes.id.split('_');
            if (spl) {
                iCxt = spl[0];
                iPar = spl[1];
            }
        }
        Ext.getCmp('modx-import-parent').setValue(iPar);
        Ext.getCmp('modx-import-context').setValue(iCxt);
    }
});
Ext.reg('modx-page-import-resource',MODx.page.ImportResource);