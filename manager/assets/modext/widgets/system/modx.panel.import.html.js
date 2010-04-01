MODx.panel.ImportHTML = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'system/import/html.php'
        ,baseParams: {
            action: 'html'
        }
        ,id: 'modx-panel-import-html'
        ,baseParams: {
            action: 'clear'
        }
        ,buttonAlign: 'center'
        ,items: [{
            html: '<h2>'+_('import_site_html')+'</h2>'
            ,id: 'modx-import-html-header'
            ,cls: 'modx-page-header'
            ,border: false
        },{
            layout: 'form'
            ,bodyStyle: 'padding: 15px;'
            ,labelWidth: 200
            ,width: '100%'
            ,autoHeight: true
            ,buttonAlign: 'center'
            ,items: [{
                html: '<p>'+_('import_site_message')+'</p>'
                ,border: false
            },{
                xtype: 'textfield'
                ,fieldLabel: _('import_element')
                ,name: 'import_element'
                ,id: 'modx-import-element'
                ,width: 250
                ,value: 'body'
            },{
                xtype: 'hidden'
                ,name: 'import_context'
                ,id: 'modx-import-context'
                ,value: 'web'
            },{
                xtype: 'textfield'
                ,fieldLabel: _('import_parent_document')
                ,name: 'import_parent'
                ,id: 'modx-import-parent'
                ,width: 250
                ,value: 0
            },{
                xtype: 'modx-tree-resource-simple'
                ,title: _('import_use_doc_tree')
                ,url: MODx.config.connectors_url+'resource/index.php'
                ,id: 'modx-ih-resource-tree'
                ,enableDrop: false
                ,rootVisible: false
                ,hideLabel: true
                ,listeners: {
                    'click': {fn:this.setParent,scope:this}
                }
            }]
        }]
    });
    MODx.panel.ImportHTML.superclass.constructor.call(this,config);
    Ext.Ajax.timeout = 0;
};
Ext.extend(MODx.panel.ImportHTML,MODx.FormPanel,{    
    setParent: function(node,e) {
        var iPar = 0;
        var iCxt = 'web';
        
        var spl = node.attributes.id.split('_');
        iCxt = spl[0];
        iPar = spl[1];
        Ext.getCmp('modx-import-parent').setValue(iPar);
        Ext.getCmp('modx-import-context').setValue(iCxt);
        return false;
    }
});
Ext.reg('modx-panel-import-html',MODx.panel.ImportHTML);