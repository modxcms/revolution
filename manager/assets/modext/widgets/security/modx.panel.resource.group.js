/**
 * @class MODx.panel.ResourceGroups
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-resource-groups
 */
MODx.panel.ResourceGroups = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-resource-groups'
        ,cls: 'container'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: _('resource_groups')
            ,id: 'modx-resource-groups-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('resource_groups')
            ,layout: 'form'
            ,defaults: { border: false ,autoHeight: true }
            ,items: [{
                html: '<p>'+_('rrg_drag')+'</p>'
                ,xtype: 'modx-description'
            },{
                layout: 'column'
                ,cls:'main-wrapper'
                ,defaults: { border: false }
                ,items: [{
                    columnWidth: .5
                    ,items: [{
                        xtype: 'modx-tree-resource-group'
                        ,id: 'modx-gr-tree-resourcegroup'
                        ,ddGroup: 'rg2resource'
                        ,height: 400
                    }]
                },{
                    columnWidth: .5
                    ,defaults: { autoHeight: true }
                    ,items: [{
                        xtype: 'modx-tree-resource-simple'
                        ,id: 'modx-gr-tree-resource'
                        ,url: MODx.config.connector_url
                        ,baseParams: {
                            action: 'Resource/GetNodes'
                            ,noMenu: true
                        }
                        ,ddGroup: 'rg2resource'
                        ,title: _('resources')
                        ,enableDrop: false
                        ,allowDrop: false
                        ,enableDD: false
                        ,rootVisible: false
                        ,listeners: {
                            'click': {fn:function() {return false;}}
                        }
                    }]
                }]
            }]
        }])]
    });
    MODx.panel.ResourceGroups.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ResourceGroups,MODx.FormPanel);
Ext.reg('modx-panel-resource-groups',MODx.panel.ResourceGroups);
