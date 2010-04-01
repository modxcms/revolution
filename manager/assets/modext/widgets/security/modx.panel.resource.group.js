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
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{ 
             html: '<h2>'+_('resource_groups')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-resource-groups-header'
        },{
            layout: 'form'
            ,bodyStyle: 'padding: 15px;'
            ,defaults: { border: false ,autoHeight: true }
            ,items: [{
                html: '<p>'+_('rrg_drag')+'</p>'
            },{
                layout: 'column'
                ,defaults: { border: false }
                ,items: [{
                    columnWidth: .4
                    ,style: 'padding: 4px;'
                    ,items: [{
                        xtype: 'modx-tree-resource-group'
                        ,id: 'modx-gr-tree-resourcegroup'
                        ,ddGroup: 'rg2resource'
                        ,height: 400
                    }]
                },{
                    columnWidth: .4
                    ,style: 'padding: 4px;'
                    ,defaults: { autoHeight: true }
                    ,items: [{
                        xtype: 'modx-tree-resource-simple'
                        ,id: 'modx-gr-tree-resource'
                        ,url: MODx.config.connectors_url+'resource/index.php'
                        ,baseParams: {
                            action: 'getNodes'
                            ,noMenu: true
                        }
                        ,ddGroup: 'rg2resource'
                        ,title: _('resources')
                        ,enableDrop: false
                        ,rootVisible: false
                    }]
                }]
            }]
        }]
    });
    MODx.panel.ResourceGroups.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ResourceGroups,MODx.FormPanel);
Ext.reg('modx-panel-resource-groups',MODx.panel.ResourceGroups);

