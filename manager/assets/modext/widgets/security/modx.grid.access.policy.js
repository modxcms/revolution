/**
 * Loads the panel for managing access policies.
 * 
 * @class MODx.panel.AccessPolicies
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-access-policies
 */
MODx.panel.AccessPolicies = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-access-policies'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('policies')+'</h2>'
            ,border: false
            ,id: 'modx-policies-header'
            ,cls: 'modx-page-header'
        },{
            layout: 'form'
            ,bodyStyle: 'padding: 15px'
            ,items: [{
                html: '<p>'+_('policy_management_msg')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-access-policy'
                ,preventRender: true
            }]
        }]
    });
    MODx.panel.AccessPolicies.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.AccessPolicies,MODx.FormPanel);
Ext.reg('modx-panel-access-policies',MODx.panel.AccessPolicies);

/**
 * Loads a grid of modAccessPolicies.
 * 
 * @class MODx.grid.AccessPolicy
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-access-policy
 */
MODx.grid.AccessPolicy = function(config) {
    config = config || {};    
    Ext.applyIf(config,{
        id: 'modx-grid-access-policy'
        ,url: MODx.config.connectors_url+'security/access/policy.php'
        ,fields: ['id','name','description','class','data','parent','menu']
		,paging: true
        ,autosave: true
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
            ,sortable: true
        },{
            header: _('policy_name')
            ,dataIndex: 'name'
            ,width: 200
            ,editor: { xtype: 'textfield' ,allowBlank: false }
            ,sortable: true
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 375
            ,editor: { xtype: 'textfield' }
        }]
        ,tbar: [{
            text: _('add')
            ,scope: this
            ,handler: { xtype: 'modx-window-access-policy-create' ,blankValues: true }
        }]
    });
    MODx.grid.AccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.AccessPolicy,MODx.grid.Grid,{		
    editPolicy: function(itm,e) {
        location.href = '?a='+MODx.action['security/access/policy/update']+'&id='+this.menu.record.id;
    }
});
Ext.reg('modx-grid-access-policy',MODx.grid.AccessPolicy);

/**
 * Generates a window for creating Access Policies.
 *  
 * @class MODx.window.CreateAccessPolicy
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-access-policy-create
 */
MODx.window.CreateAccessPolicy = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        width: 400
        ,title: _('policy_create')
        ,url: MODx.config.connectors_url+'security/access/policy.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-cap-name'
            ,xtype: 'textfield'
            ,anchor: '90%'
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,id: 'modx-cap-description'
            ,xtype: 'textarea'
            ,anchor: '90%'
            ,height: 50
        },{
            name: 'class'
            ,id: 'modx-cap-class'
            ,xtype: 'hidden'
        },{
            name: 'id'
            ,id: 'modx-cap-id'
            ,xtype: 'hidden'
        }]
    });
    MODx.window.CreateAccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateAccessPolicy,MODx.Window);
Ext.reg('modx-window-access-policy-create',MODx.window.CreateAccessPolicy);
