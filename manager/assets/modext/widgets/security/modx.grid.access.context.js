/**
 * Loads a grid of modAccessContexts.
 * 
 * @class MODx.grid.AccessContext
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-access-context
 */
MODx.grid.AccessContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('ugc_grid_title')
        ,url: MODx.config.connectors_url+'security/access/index.php'
        ,baseParams: {
            action: 'getList'
            ,type: config.type || 'modAccessContext'
        }
        ,fields: ['id','target','target_name','principal_class','principal','principal_name','authority','policy','policy_name','menu']
		,type: 'modAccessContext'
		,paging: true
        ,columns: [
            { header: _('id') ,dataIndex: 'id' ,width: 40 }
            ,{ header: _('context_id') ,dataIndex: 'target' ,width: 40 ,hidden: true }
            ,{ header: _('context') ,dataIndex: 'target_name' ,width: 100 }
            ,{ header: _('user_group_id') ,dataIndex: 'principal' ,width: 40 }
            ,{ header: _('user_group') ,dataIndex: 'principal_name' ,width: 120 }
            ,{ header: _('authority') ,dataIndex: 'authority' ,width: 50 }
            ,{ header: _('policy') ,dataIndex: 'policy_name' ,width: 175 }
        ]
		,tbar: this.getToolbar()
    });
    MODx.grid.AccessContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.AccessContext,MODx.grid.Grid,{
	combos: {}
	,windows: {}
	
	,createAcl: function(itm,e) {
        var r = this.menu.record || {};
        Ext.applyIf(r,{
            context: r.target
            ,user_group: r.principal
        });
		if (!this.windows.create_acl) {
			this.windows.create_acl = MODx.load({
                xtype: 'modx-window-access-context'
	            ,record: r
	            ,listeners: {
	            	'success': {fn:function(o) {    	                
    	                this.getStore().baseParams = { 
    	                    action: 'getList'
    	                    ,type: this.config.type
    	                    ,target: this.combos.ctx.getValue()
    	                    ,principal: this.combos.ug.getValue()
    	                    ,principal_class: 'modUserGroup'
    	                };
    	                this.refresh();
	            	},scope:this}
	            }
	        });
		} else {
			this.windows.create_acl.setValues(r);
		}
		        
        this.windows.create_acl.show(e.target);
	}
    
	,editAcl: function(itm,e) {
        var r = this.menu.record;
        Ext.applyIf(r,{
            context: r.target
            ,user_group: r.principal
        });
        if (!this.windows.update_acl) {
			this.windows.update_acl = MODx.load({
	            xtype: 'modx-window-access-context'
	            ,id: r.id
	            ,record: r
	            ,listeners: {
	            	'success': {fn:this.refresh,scope:this}
	            }
	        });
		} else {
			this.windows.update_acl.setValues(r);
		}
        this.windows.update_acl.show(e.target);
    }
	
    ,removeAcl: function(itm,e) {
        MODx.msg.confirm({
            title: _('ugc_remove')
            ,text: _('access_confirm_remove')
            ,url: this.config.url
            ,params: {
                action: 'removeAcl'
                ,id: this.menu.record.id
                ,type: this.config.type
            }
            ,listeners: {
            	'success': {fn:this.refresh,scope:this}
            }
        });
    }
	
	,clearFilter: function(btn,e) {
        this.getStore().baseParams = { 
            action: 'getList'
            ,type: this.config.type
            ,target: ''
            ,principal: ''
            ,principal_class: 'modUserGroup'
        };
        this.combos.ug.setValue('');
        this.combos.ctx.setValue('');
        this.getStore().load();
	}
	
	,getToolbar: function() {
		this.combos.ug = MODx.load({ 
            xtype: 'modx-combo-usergroup'
            ,listeners: {
              	'select': {fn:function(btn,e) {
                    this.getStore().baseParams = {
                        action: 'getList'
                        ,type: this.config.type
                        ,target: this.combos.rg.getValue()
                        ,principal: this.combos.ug.getValue()
                    };
                    this.getStore().load();
                },scope:this}
            }
		});
	    this.combos.ctx = MODx.load({ 
            xtype: 'modx-combo-context'
            ,listeners: {
               	'select': {fn:function(btn,e) {
                    this.getStore().baseParams = {
                        action: 'getList'
                        ,type: this.config.type
                        ,target: this.combos.ctx.getValue()
                        ,principal: this.combos.ug.getValue()
                    };
                    this.getStore().load();
                },scope:this}
            }
        });
	    
		return [
	    	_('context') +': '
			,this.combos.ctx
			,'-'
			,_('user_group') + ': '
			,this.combos.ug
			,'-'
			,{
		        text: _('clear_filter')
		        ,scope: this
		        ,handler: this.clearFilter
		    }
			,'->'
			,{
		        text: _('add')
		        ,scope: this
		        ,handler: this.createAcl
			}
	    ];
	}
});
Ext.reg('modx-grid-access-context',MODx.grid.AccessContext);

/** 
 * Generates the modAccessContext window.
 *  
 * @class MODx.window.AccessContext
 * @extends MODx.Window
 * @param {Object} An object of configuration options.
 * @xtype modx-window-access-context
 */
MODx.window.AccessContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('ugc_mutate')
        ,height: 250
        ,width: 350
        ,type: 'modAccessContext'
        ,id: 0
    });
    MODx.window.AccessContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AccessContext,MODx.Window,{
    combos: {}
	
    ,_loadForm: function() {
        if (this.checkIfLoaded(this.config.record)) { return false; }
        if (this.config.id) {
            MODx.Ajax.request({
                url: MODx.config.connectors_url+'security/access/index.php'
                ,params: {
                    action: 'getAcl'
                    ,id: this.config.id
                    ,type: this.config.type
                }
                ,listeners: {
                	'success':{fn:this.prepareForm,scope:this}
                }
            });
        } else {
            this.prepareForm(null,null);
        }
    }
	
    ,prepareForm: function(r,o) {
        var data = {};
        if (r) {
            r = Ext.decode(r.responseText);
            if (r.success) {
                data = r.object;
                this.config.baseParams = {
                    action: 'updateAcl',
                    type: this.config.type
                };
            }
        }
        this.config.values = data;		
				
        this.fp = this.createForm({
            url: this.config.url || MODx.config.connectors_url+'security/access/index.php'
            ,baseParams: this.config.baseParams || { action: 'addAcl', type: this.config.type }
			,items: [ 
            	{
                    fieldLabel: _('context')
                    ,name: 'target'
                    ,hiddenName: 'target'
                    ,xtype: 'modx-combo-context'
                    ,value: data.context
                },{
                    fieldLabel: _('user_group')
                    ,name: 'principal'
                    ,hiddenName: 'principal'
                    ,xtype: 'modx-combo-usergroup'
                    ,value: data.principal || ''
                    ,baseParams: {
                        action: 'getList'
                        ,combo: '1'
                    }
                },{
	                fieldLabel: _('authority')
	                ,name: 'authority'
                    ,xtype: 'textfield'
	                ,width: 40
	                ,value: data.authority
	            },{
                    fieldLabel: _('policy')
                    ,name: 'policy'
                    ,hiddenName: 'policy'
                    ,xtype: 'modx-combo-policy'
                    ,value: data.policy || ''
                    ,baseParams: {
                        action: 'getList'
                        ,combo: '1'
                    }
                },{
	                name: 'principal_class'
                    ,xtype: 'hidden'
	                ,value: 'modUserGroup'
	            },{
	                name: 'id'
                    ,xtype: 'hidden'
	                ,value: data.id
	            }
			]
        });
        
        this.renderForm();
    }
});
Ext.reg('modx-window-access-context',MODx.window.AccessContext);