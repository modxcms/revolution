/**
 * 
 * @class MODx.panel.AccessPolicy
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype modx-panel-access-policy
 */
MODx.panel.AccessPolicy = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'security/access/policy.php'
        ,baseParams: {
            action: 'update'
            ,id: MODx.request.id
        }
        ,id: 'modx-panel-access-policy'
        ,class_key: 'modAccessPolicy'
        ,plugin: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('chunk_new')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-policy-header'
        },{
            xtype: 'portal'
            ,items: [{
                columnWidth: 1
                ,items: [{
                    title: _('policy')
                    ,layout: 'form'
                    ,bodyStyle: 'padding: 1.5em;'
                    ,items: [{
                        html: '<p>'+_('policy_desc')+'</p>'
                        ,border: false
                    },{
                        xtype: 'hidden'
                        ,name: 'id'
                        ,value: config.plugin
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('name')
                        ,name: 'name'
                        ,width: 300
                        ,maxLength: 255
                        ,enableKeyEvents: true
                        ,allowBlank: false
                        ,listeners: {
                            'keyup': {scope:this,fn:function(f,e) {
                                Ext.getCmp('modx-policy-header').getEl().update('<h2>'+_('policy')+': '+f.getValue()+'</h2>');
                            }}
                        }
                    },{
                        xtype: 'textarea'
                        ,fieldLabel: _('description')
                        ,name: 'description'
                        ,width: 300
                        ,grow: true
                    },{
                        xtype: 'button'
                        ,text: _('save')
                        ,handler: this.submit
                        ,scope: this
                    }]
                },{
                    title: _('policy_data')
                    ,layout: 'form'
                    ,bodyStyle: ''
                    ,items: [{
                        xtype: 'modx-grid-policy-property'
                        ,policy: MODx.request.id
                        ,source: null
                        ,autoHeight: true
                    }]
                }]
            }]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
        }
    });
    MODx.panel.AccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.AccessPolicy,MODx.FormPanel,{
    setup: function() {
        if (this.config.policy === '' || this.config.policy === 0) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.policy
            }
            ,listeners: {
            	'success':{fn:function(r) {
                    this.getForm().setValues(r.object);
                    var data = Ext.util.JSON.decode(r.object.policy_data);
                    var g = Ext.getCmp('modx-grid-policy-property');
                    g.setSource(data);
                    g.config.policy = r.object.id;
                    g.getView().refresh();
                    
                    Ext.getCmp('modx-policy-header').getEl().update('<h2>'+_('policy')+': '+r.object.name+'</h2>');
                    this.fireEvent('ready');
            	},scope:this}
            }
        });
    }
});
Ext.reg('modx-panel-access-policy',MODx.panel.AccessPolicy);




/**
 * Loads a property grid of modAccessPolicies properties.
 * 
 * @class MODx.grid.PolicyProperty
 * @extends Ext.grid.PropertyGrid
 * @param {Object} config An object of options.
 * @xtype modx-grid-policy-property
 */
MODx.grid.PolicyProperty = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-grid-policy-property'
        ,source: null
        ,height: 300
        ,maxHeight: 300
        ,autoHeight: true
        ,minColumnWidth: 250
        ,autoExpandColumn: 'name'
        ,autoWidth: true
        ,collapsible: true
        ,stripeRows: true
        ,tbar: [{
            text: _('policy_property_new')
            ,scope: this
            ,handler: this.createAttribute
        }]
    });
    MODx.grid.PolicyProperty.superclass.constructor.call(this,config);
    this.config = config;
    
    this.on('afteredit',this.updateAttribute,this);
    this.menu = new Ext.menu.Menu({ defaultAlign: 'tl-b?' });
    this.on('rowcontextmenu',this.showMenu,this);
};
Ext.extend(MODx.grid.PolicyProperty,Ext.grid.PropertyGrid,{
    createAttribute: function() {
        Ext.Msg.prompt(_('policy_property_create'),_('policy_property_specify_name'),function(btn,v) {
            if (btn == 'ok') {
                MODx.Ajax.request({
                    url: MODx.config.connectors_url+'security/access/policy.php'
                    ,params: {
                        action: 'createPolicyData'
                        ,id: this.config.policy
                        ,key: v
                    }
                    ,listeners: {
                    	'success': {fn:function(r) {
                    		var s = this.getSource();
                            if (!s) return false;
                    		s[v] = true;
                    		this.setSource(s);
                    	},scope:this}
                    }
                });
            }
        },this);
    }
    
    ,removeAttribute: function() {
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'security/access/policy.php'
            ,params: {
                action: 'removePolicyData'
                ,id: this.config.policy
                ,key: this.menu.record
            }
            ,listeners: {
            	'success': {fn:function(r) {
                    var s = this.getSource();
                    s[this.menu.record] = null;
                    this.setSource(s);            		
            	},scope:this}
            }
        });
    }
    
    ,updateAttribute: function(e) {
        MODx.Ajax.request({
           url: MODx.config.connectors_url+'security/access/policy.php'
           ,params: {
               action: 'updatePolicyData'
               ,id: this.config.policy
               ,key: e.record.data.name
               ,value: e.value
           }
           ,listeners: {
               'success': {fn:function(r) {
                   e.record.commit();
               },scope:this}
           }
        });
    }
    
    ,showMenu: function(g,ri,e) {
        e.stopEvent();
        e.preventDefault();
        var sm = this.getSelectionModel();
                
        this.menu.removeAll();
        this.menu.record = sm.selection.record.id;
        this.menu.add({
            text: _('policy_property_remove')
            ,scope: this
            ,handler: this.removeAttribute
        });
        
        this.menu.show(e.target);
    }
});
Ext.reg('modx-grid-policy-property',MODx.grid.PolicyProperty);