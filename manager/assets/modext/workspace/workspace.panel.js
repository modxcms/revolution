/**
 * The package list container
 * 
 * @class MODx.panel.Workspace
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype modx-panel-workspace
 */
MODx.panel.Workspace = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-workspace'
        ,cls: 'container'
        ,bodyStyle: ''
		,unstyled:true
        ,items: [{
            html: '<h2>'+_('package_management')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-workspace-header'
        },MODx.getPageStructure([{
            title: _('packages')		
			,items:[{
				xtype: 'modx-breadcrumbs-panel'
				,id: 'packages-breadcrumbs'
				,desc: _('packages_desc')
				,root : { 
					text : 'Packages List'
					,className: 'first'
					,root: true
					,pnl: 'modx-panel-packages' 
				}
			},{
				layout:'card'
				,id:'card-container'
				,activeItem:0
				,border: false	
				,autoHeight: true			
				,defaults:{
					cls: 'main-wrapper'
					,preventRender: true
					,autoHeight: true
				}
				,items: [{
					xtype: 'modx-panel-packages'
					,id: 'modx-panel-packages'									
				},{
					xtype: 'modx-panel-packages-browser'
					,id: 'modx-panel-packages-browser'
				}]  
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
Ext.extend(MODx.panel.Workspace,MODx.Panel);
Ext.reg('modx-panel-workspace',MODx.panel.Workspace);