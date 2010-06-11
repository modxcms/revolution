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
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('actions')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,itemId: 'header'
        },{
            xtype: 'portal'
            ,itemId: 'portal'
            ,items: [{
                columnWidth: .47
                ,itemId: 'col-action'
                ,items: [{
                    title: _('actions')
                    ,itemId: 'form-action'
                    ,cls: 'x-panel-header'
                    ,style: 'padding: 5px;'
                    ,bodyStyle: 'text-transform: none; font-weight: normal;'
                    ,layout: 'form'
                    ,items: [{
                        html: '<p>'+_('action_desc')+'</p>'
                        ,border: false
                    },{
                        xtype: 'modx-tree-action'
                        ,itemId: 'tree-action'
                    }]
                }]
            },{
                columnWidth: .47
                ,itemId: 'col-menu'
                ,items: [{
                    title: _('topmenu')
                    ,itemId: 'form-menu'
                    ,cls: 'x-panel-header'
                    ,style: 'padding: 5px;'
                    ,bodyStyle: 'text-transform: none; font-weight: normal;'
                    ,layout: 'form'
                    ,items: [{
                        html: '<p>'+_('topmenu_desc')+'</p>'
                        ,border: false
                    },{
                        xtype: 'modx-tree-menu'
                        ,itemId: 'tree-menu'
                    }]
                }]
            }]
        }]
    });
    MODx.panel.Actions.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Actions,MODx.FormPanel);
Ext.reg('modx-panel-actions',MODx.panel.Actions);