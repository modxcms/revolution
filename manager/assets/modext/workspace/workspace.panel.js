MODx.panel.Workspace = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-workspace'
        ,cls: 'container'
        ,bodyStyle: ''
        ,items: [{
            html: '<h2>'+_('package_management')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-workspace-header'
        },MODx.getPageStructure([{
            title: _('packages')
            ,autoHeight: true
			,layout: 'form'
            ,items: [{
                html: '<p>'+_('packages_desc')+'</p>'
                ,bodyCssClass: 'panel-desc'
                ,border: false
            },{
                xtype: 'modx-grid-package'
                ,id: 'modx-grid-package'
                ,cls: 'main-wrapper'
                ,preventRender: true
            }]
        },{
            title: _('providers')
            ,autoHeight: true
			,layout: 'form'
            ,items: [{
                html: '<p>'+_('providers_desc')+'</p>'
                ,bodyCssClass: 'panel-desc'
                ,border: false
            },{
                xtype: 'modx-grid-provider'
                ,id: 'modx-grid-provider'
                ,cls: 'main-wrapper'
                ,title: ''
                ,preventRender: true
            }]
        }])]
    });
    MODx.panel.Workspace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Workspace,MODx.FormPanel);
Ext.reg('modx-panel-workspace',MODx.panel.Workspace);
