/**
 * @class MODx.panel.GroupsRoles
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-groups-roles
 */
MODx.panel.ActionDom = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-actiondom'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{ 
             html: '<h2>'+_('form_customization')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-actiondom-header'
        },MODx.getPageStructure([{
            title: _('form_rules')
            ,bodyStyle: 'padding: 15px;'
            ,autoHeight: true
            ,items: [{
                html: '<p>'+_('form_customization_msg')+'</p>'
                ,border: false
            },{
                title: ''
                ,preventRender: true
                ,xtype: 'modx-grid-actiondom'
            }]
        }],{
            id: 'modx-form-customization-tabs'
        })]
    });
    MODx.panel.ActionDom.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ActionDom,MODx.FormPanel);
Ext.reg('modx-panel-actiondom',MODx.panel.ActionDom);