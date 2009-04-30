Ext.onReady(function() {
    MODx.load({ xtype: 'modx-page-import-html' });
});

/**
 * @class MODx.page.ImportHTML
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-page-import-html
 */
MODx.page.ImportHTML = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        form: 'modx-import-site'
        ,fields: {
            import_element: {
                xtype: 'textfield'
                ,id: 'modx-import-element'
                ,width: 200
                ,applyTo: 'modx-import-element'
                ,value: 'body'
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
            process: 'import', text: _('import_site'), method: 'remote'
            ,onComplete: function(o, itm, res) {
                Ext.get('modx-import-results').update(res.message);
            }
        },{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['welcome']}
        }]
        ,components: [{
            xtype: 'modx-tree-resource-simple'
            ,title: _('resources')
            ,url: MODx.config.connectors_url+'resource/index.php'
            ,el: 'modx-ih-resource-tree'
            ,id: 'modx-ih-resource-tree'
            ,tb_id: 'modx-ih-resource-tree-tb'
            ,enableDrop: false
            ,rootVisible: false
        }]
    });
    MODx.page.ImportHTML.superclass.constructor.call(this,config);
    this.setup();
};
Ext.extend(MODx.page.ImportHTML,MODx.Component,{
    setup: function() {
        Ext.Ajax.timeout = 0;
        var t = Ext.getCmp('modx-ih-resource-tree');
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
Ext.reg('modx-page-import-html',MODx.page.ImportHTML);