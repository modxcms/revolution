
MODx.grid.UserGroupContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-grid-user-group-contexts'
        ,url: MODx.config.connectors_url+'security/access/index.php'
        ,baseParams: {
            action: 'getList'
        }
        ,fields: ['id','target','target_name','principal_class','principal','principal_name','authority','policy','policy_name']
        ,columns: [{
            header: _('context')
            ,dataIndex: 'target_name'
            ,width: 120
        },{
            header: _('minimum_role')
            ,dataIndex: 'authority'
            ,width: 100
            ,editor: { xtype: 'modx-combo-authority' ,allowBlank: false, renderer: true }
        },{
            header: _('policy')
            ,dataIndex: 'policy_name'
            ,width: 200
            ,editor: { xtype: 'modx-combo-policy' ,allowBlank: false ,renderer: true }
        }]
        ,tbar: [{
            text: _('context_add')
            ,scope: this
            ,handler: this.createAcl
        }]
    });
    MODx.grid.UserGroupContext.superclass.constructor.call(this,config);
    this.ctxRecord = new Ext.data.Record.create([
        {name: 'id'}
        ,{name: 'target'}
        ,{name:'target_name'}
        ,{name:'principal_class'}
        ,{name:'principal'}
        ,{name:'principal_name'}
        ,{name:'authority'}
        ,{name:'policy'}
        ,{name:'policy_name'}
    ]);
    this.on('afteredit',function() { Ext.getCmp('modx-panel-user-group').fireEvent('fieldChange'); });
};
Ext.extend(MODx.grid.UserGroupContext,MODx.grid.LocalGrid,{
    combos: {}
    ,windows: {}
    
    ,createAcl: function(itm,e) {
        var r = {
            principal: this.config.usergroup
        };
        if (!this.windows.addContext) {
            this.windows.addContext = MODx.load({
                xtype: 'modx-window-user-group-context-create'
                ,record: r
                ,listeners: {
                    'success': {fn:function(r) {
                        var s = this.getStore();
                        var rec = new this.ctxRecord(r);
                        s.add(rec);
                        
                        Ext.getCmp('modx-panel-user-group').fireEvent('fieldChange');
                    },scope:this}
                }
            });
        }
        this.windows.addContext.setValues(r);
        this.windows.addContext.show(e.target);
    }
        
    ,_showMenu: function(g,ri,e) {
        e.stopEvent();
        e.preventDefault();
        var m = this.menu;
        m.recordIndex = ri;
        m.record = this.getStore().getAt(ri).data;
        if (!this.getSelectionModel().isSelected(ri)) {
            this.getSelectionModel().selectRow(ri);
        }
        m.removeAll();
        m.add({
            text: _('context_remove')
            ,handler: this.remove.createDelegate(this,[{text: _('user_group_context_remove_confirm')}])
            ,scope: this
        });
        m.show(e.target);
    }
});
Ext.reg('modx-grid-user-group-context',MODx.grid.UserGroupContext);


MODx.window.CreateUGAccessContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('ugc_mutate')
        ,url: MODx.config.connectors_url+'security/access/index.php'
        ,height: 250
        ,width: 350
        ,fields: [{
            xtype: 'modx-combo-context'
            ,fieldLabel: _('context')
            ,name: 'target'
            ,hiddenName: 'target'
            ,editable: false
        },{
            xtype: 'modx-combo-authority'
            ,fieldLabel: _('minimum_role')
            ,name: 'authority'
            ,value: 0
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,baseParams: {
                action: 'getList'
                ,combo: '1'
            }
        },{
            xtype: 'hidden'
            ,name: 'principal'
            ,hiddenName: 'principal'
        },{
            xtype: 'hidden'
            ,name: 'principal_class'
            ,value: 'modUserGroup'
        }]
    });
    MODx.window.CreateUGAccessContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUGAccessContext,MODx.Window,{
    submit: function() {
        var f = this.fp.getForm();
        var r = f.getValues();
        r.policy_name = f.findField('policy').getRawValue();
        r.target_name = f.findField('target').getRawValue();
        
        /*
        var g = Ext.getCmp('modx-grid-user-group-contexts');
        var s = g.getStore();
        var v = s.query('id',r.id).items;
        if (v.length > 0) {
            MODx.msg.alert(_('error'),_('user_group_context_err_ae'));
           return false;
        }*/
        
        this.fireEvent('success',r);
        this.hide();
        return false;
    }
});
Ext.reg('modx-window-user-group-context-create',MODx.window.CreateUGAccessContext);