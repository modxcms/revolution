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
        id: 'modx-grid-access-context'
        ,url: MODx.config.connectors_url+'security/access/index.php'
        ,baseParams: {
            action: 'getList'
            ,type: config.type || 'modAccessContext'
            ,target: config.context_key
        }
        ,fields: ['id','target','target_name','principal_class','principal','principal_name','authority','policy','policy_name','cls']
		,type: 'modAccessContext'
		,paging: true
        ,columns: [
            { header: _('context') ,dataIndex: 'target_name' ,width: 100 }
            ,{ header: _('user_group') ,dataIndex: 'principal_name' ,width: 120 }
            ,{ header: _('authority') ,dataIndex: 'authority' ,width: 50 }
            ,{ header: _('policy') ,dataIndex: 'policy_name' ,width: 175 }
        ]
        ,tbar: [{
            text: _('acl_add')
            ,scope: this
            ,handler: this.createAcl
        }]
    });
    MODx.grid.AccessContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.AccessContext,MODx.grid.Grid,{
    combos: {}
    ,windows: {}

    ,getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.cls;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {

        } else {
            if (p.indexOf('pedit') != -1) {
                m.push({
                    text: _('edit')
                    ,handler: this.editAcl
                });
            }
            if (p.indexOf('premove') != -1) {
                if (m.length > 0) { m.push('-'); }
                m.push({
                    text: _('remove')
                    ,handler: this.removeAcl
                });
            }
        }

        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }

    ,createAcl: function(itm,e) {
        var r = {
            target: this.config.context_key
            ,principal_class: 'modUserGroup'
        };
        if (!this.windows.create_acl) {
            this.windows.create_acl = MODx.load({
                xtype: 'modx-window-access-context-create'
                ,record: r
                ,listeners: {
                    'success': {fn:function(o) {
                    this.refresh();
                    },scope:this}
                }
            });
        }
        this.windows.create_acl.fp.getForm().reset();
        this.windows.create_acl.setValues(r);
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
                xtype: 'modx-window-access-context-update'
                ,acl: r.id
                ,record: r
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        }
        this.windows.update_acl.setValues(r);
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
                ,type: this.config.type || 'modAccessContext'
            }
            ,listeners: {
            	'success': {fn:this.refresh,scope:this}
            }
        });
    }
	
});
Ext.reg('modx-grid-access-context',MODx.grid.AccessContext);

MODx.window.UpdateAccessContext = function(config) {
    config = config || {};
    var r = config.record;
    this.ident = config.ident || 'uactx'+Ext.id();
    Ext.applyIf(config,{
        title: _('ugc_mutate')
        ,url: MODx.config.connectors_url+'security/access/index.php'
        ,baseParams: { action: 'updateAcl', type: config.type || 'modAccessContext' }
        ,height: 250
        ,width: 350
        ,type: 'modAccessContext'
        ,acl: 0
        ,fields: [{
            xtype: 'hidden'
            ,name: 'target'
            ,id: 'modx-'+this.ident+'-target'
            ,value: r.context
        },{
            xtype: 'modx-combo-usergroup'
            ,fieldLabel: _('user_group')
            ,name: 'principal'
            ,hiddenName: 'principal'
            ,id: 'modx-'+this.ident+'-principal'
            ,value: r.principal || ''
            ,baseParams: {
                action: 'getList'
                ,combo: '1'
            }
            ,anchor: '90%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('authority')
            ,name: 'authority'
            ,id: 'modx-'+this.ident+'-authority'
            ,width: 40
            ,value: r.authority
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,id: 'modx-'+this.ident+'-policy'
            ,value: r.policy || ''
            ,baseParams: {
                action: 'getList'
                ,combo: '1'
            }
            ,anchor: '90%'
        },{
            xtype: 'hidden'
            ,name: 'principal_class'
            ,id: 'modx-'+this.ident+'-principal-class'
            ,value: 'modUserGroup'
        },{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
            ,value: r.id
        }]
    });
    MODx.window.UpdateAccessContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateAccessContext,MODx.Window);
Ext.reg('modx-window-access-context-update',MODx.window.UpdateAccessContext);


MODx.window.CreateAccessContext = function(config) {
    config = config || {};
    var r = config.record;
    this.ident = config.ident || 'cactx'+Ext.id();
    Ext.applyIf(config,{
        title: _('ugc_mutate')
        ,url: MODx.config.connectors_url+'security/access/index.php'
        ,baseParams: { action: 'addAcl', type: config.type || 'modAccessContext' }
        ,height: 250
        ,width: 350
        ,type: 'modAccessContext'
        ,acl: 0
        ,fields: [{
            xtype: 'hidden'
            ,name: 'target'
            ,id: 'modx-'+this.ident+'-target'
        },{
            xtype: 'hidden'
            ,name: 'principal_class'
            ,id: 'modx-'+this.ident+'-principal-class'
            ,value: 'modUserGroup'
        },{
            xtype: 'modx-combo-usergroup'
            ,fieldLabel: _('user_group')
            ,name: 'principal'
            ,hiddenName: 'principal'
            ,id: 'modx-'+this.ident+'-usergroup'
            ,baseParams: {
                action: 'getList'
                ,combo: '1'
            }
            ,anchor: '90%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('authority')
            ,name: 'authority'
            ,id: 'modx-'+this.ident+'-authority'
            ,width: 40
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,id: 'modx-'+this.ident+'-policy'
            ,baseParams: {
                action: 'getList'
                ,combo: '1'
            }
            ,anchor: '90%'
        }]
    });
    MODx.window.CreateAccessContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateAccessContext,MODx.Window);
Ext.reg('modx-window-access-context-create',MODx.window.CreateAccessContext);