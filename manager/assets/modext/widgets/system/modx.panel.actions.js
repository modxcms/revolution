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
        id: 'modx-panel-actions'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('actions')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-actions-header'
        },{
            xtype: 'portal'
            ,id: 'modx-action-portal'
            ,items: [{
                columnWidth: .47
                ,id: 'modx-action-col'
                ,items: [{
                    title: _('actions')
					,cls: 'x-panel-header'
			        ,style: 'padding: .5em;'
			        ,bodyStyle: 'text-transform: none; font-weight: Normal;'
                    ,layout: 'form'
                    ,items: [{
                        html: '<p>'+_('action_desc')+'</p>'
                        ,border: false
                    },{
                        xtype: 'modx-tree-action'
                    }]
                }]
            },{
                columnWidth: .47
                ,id: 'modx-menu-col'
                ,items: [{
                    title: _('topmenu')
					,cls: 'x-panel-header'
			        ,style: 'padding: .5em;'
			        ,bodyStyle: 'text-transform: none; font-weight: Normal;'
                    ,layout: 'form'
                    ,items: [{
                        html: '<p>'+_('topmenu_desc')+'</p>'
                        ,border: false
                    },{
                        xtype: 'modx-tree-menu'
                    }]
                }]
            }]
        }]
    });
    MODx.panel.Actions.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Actions,MODx.FormPanel);
Ext.reg('modx-panel-actions',MODx.panel.Actions);