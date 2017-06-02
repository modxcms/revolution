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
            html: _('topmenu')
            ,xtype: 'modx-header'
        },{
            itemId: 'form-menu'
            ,bwrapCssClass: 'shadowbox'
            ,items: [{
                html: '<p>'+_('topmenu_desc')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-tree-menu'
                ,itemId: 'tree-menu'
                ,id: 'modx-tree-menu'
                ,cls: 'main-wrapper'
            }]
        }]
    });
    MODx.panel.Actions.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Actions,MODx.FormPanel);
Ext.reg('modx-panel-actions',MODx.panel.Actions);
