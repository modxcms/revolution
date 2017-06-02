MODx.panel.ImportHTML = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/import/html'
        }
        ,id: 'modx-panel-import-html'
		,cls: 'container'
        ,buttonAlign: 'center'
        ,items: [{
            html: _('import_site_html')
            ,id: 'modx-import-html-header'
            ,xtype: 'modx-header'
        },{
            layout: 'form'
            ,border: true
            ,labelWidth: 250
            ,width: '100%'
            ,autoHeight: true
            ,buttonAlign: 'center'
            ,items: [{
                html: '<p>'+_('import_site_message')+'</p>'
                ,xtype: 'modx-description'
            },{
				xtype: 'panel'
				,border: false
				,cls:'main-wrapper'
				,layout: 'form'
				,items: [{
					xtype: 'textfield'
					,fieldLabel: _('import_element')
					,name: 'import_element'
					,id: 'modx-import-element'
					,labelSeparator: ''
					,anchor: '100%'
					,value: '{"content":"$body"}'
                    ,description: _('import_element_help')
				},{
					xtype: 'hidden'
					,name: 'import_context'
					,id: 'modx-import-context'
					,value: 'web'
					,anchor: '100%'
				},{
					xtype: 'textfield'
					,fieldLabel: _('import_parent_document')
					,name: 'import_parent'
					,id: 'modx-import-parent'
					,labelSeparator: ''
					,anchor: '100%'
					,value: 0
				},MODx.PanelSpacer,{
					xtype: 'modx-tree-resource-simple'
					,title: _('import_use_doc_tree')
					,url: MODx.config.connector_url
                    ,action: 'resource/getnodes'
					,id: 'modx-ih-resource-tree'
					,enableDrop: false
					,rootVisible: false
					,hideLabel: true
					,listeners: {
						'click': {fn:this.setParent,scope:this}
					}
				}]
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
