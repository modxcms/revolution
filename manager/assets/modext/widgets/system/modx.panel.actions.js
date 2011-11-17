/**
 * Loads the panel for managing actions and menus.
 * 
 * @class MODx.panel.Actions
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-actions
 */
MODx.panel.Actions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        bodyStyle: ''
        ,id: 'modx-panel-actions'
		,cls: 'container'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('actions')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,itemId: 'header'
        },{
            xtype: 'portal'
            ,itemId: 'portal'
            ,unstyled: true
			,defaults: { layout: 'form', xtype: 'panel', cls: 'with-title' }
            ,items: [{
                columnWidth: .5
				,title: _('actions')
				,itemId: 'form-action'
				,items: [{
					html: '<p>'+_('action_desc')+'</p>'
					,bodyCssClass: 'panel-desc'
					,border: false
				},{
					xtype: 'modx-tree-action'
					,itemId: 'tree-action'
					,id: 'modx-tree-action'
					,cls: 'main-wrapper'
				}]
            },{
                columnWidth: .5
				,title: _('topmenu')
				,itemId: 'form-menu'
				,items: [{
					html: '<p>'+_('topmenu_desc')+'</p>'
					,bodyCssClass: 'panel-desc'
					,border: false
				},{
					xtype: 'modx-tree-menu'
					,itemId: 'tree-menu'
					,id: 'modx-tree-menu'
					,cls: 'main-wrapper'
				}]
            }]
        }]
    });
    MODx.panel.Actions.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Actions,MODx.FormPanel);
Ext.reg('modx-panel-actions',MODx.panel.Actions);
