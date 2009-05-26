
MODx.grid.UserGroupResourceGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-grid-user-group-resource-groups'
        ,url: MODx.config.connectors_url+'security/access/index.php'
        ,baseParams: {
            action: 'getList'
        }
        ,fields: ['id','target','target_name','principal_class','principal','principal_name','authority','policy','policy_name','context_key']
        ,columns: [{
            header: _('resource_group')
            ,dataIndex: 'target_name'
            ,width: 100
        },{
            header: _('authority')
            ,dataIndex: 'authority'
            ,width: 50
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('policy')
            ,dataIndex: 'policy_name'
            ,width: 175
            ,editor: { xtype: 'modx-combo-policy' ,allowBlank: false ,renderer: true ,baseParams: {action: 'getList',combo: '1'} }
        },{
            header: _('context')
            ,dataIndex: 'context_key'
            ,width: 150
            ,editor: { xtype: 'modx-combo-context' ,allowBlank: false ,renderer: true }
        }]
        ,tbar: [{
            text: _('resource_group_add')
            ,scope: this
            ,handler: this.addResourceGroup
        }]
    });
    MODx.grid.UserGroupResourceGroup.superclass.constructor.call(this,config);
    this.rgRecord = new Ext.data.Record.create([
        {name: 'id'}
        ,{name: 'target'}
        ,{name:'target_name'}
        ,{name:'principal_class'}
        ,{name:'principal'}
        ,{name:'principal_name'}
        ,{name:'authority'}
        ,{name:'policy'}
        ,{name:'policy_name'}
        ,{name:'context_key'}
    ]);
    this.on('afteredit',function() { Ext.getCmp('modx-panel-user-group').fireEvent('fieldChange'); });
};
Ext.extend(MODx.grid.UserGroupResourceGroup,MODx.grid.LocalGrid,{
    combos: {}
    ,windows: {}
    
    ,addResourceGroup: function(itm,e) {
        var r = {
            principal: this.config.usergroup
        };
        if (!this.windows.addResourceGroup) {
            this.windows.addResourceGroup = MODx.load({
                xtype: 'modx-window-user-group-resourcegroup-create'
                ,record: r
                ,listeners: {
                    'success': {fn:function(r) {
                        var s = this.getStore();
                        var rec = new this.rgRecord(r);
                        s.add(rec);
                        
                        Ext.getCmp('modx-panel-user-group').fireEvent('fieldChange');
                    },scope:this}
                }
            });
        }
        this.windows.addResourceGroup.setValues(r);
        this.windows.addResourceGroup.show(e.target);
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
            text: _('resource_group_remove')
            ,handler: this.remove.createDelegate(this,[{text: _('user_group_resourcegroup_remove_confirm')}])
            ,scope: this
        });
        m.show(e.target);
    }
});
Ext.reg('modx-grid-user-group-resource-group',MODx.grid.UserGroupResourceGroup);


MODx.window.CreateUGRG = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('resource_group_add')
        ,url: MODx.config.connectors_url+'security/access/index.php'
        ,height: 250
        ,width: 350
        ,fields: [{
            xtype: 'modx-combo-resourcegroup'
            ,fieldLabel: _('resource_group')
            ,name: 'target'
            ,hiddenName: 'target'
            ,editable: false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('authority')
            ,name: 'authority'
            ,width: 40
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
        },{
            xtype: 'modx-combo-context'
            ,fieldLabel: _('context')
            ,name: 'context_key'
            ,hiddenName: 'context_key'
            ,editable: false
        }]
    });
    MODx.window.CreateUGRG.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUGRG,MODx.Window,{
    submit: function() {
        var f = this.fp.getForm();
        var r = f.getValues();
        r.policy_name = f.findField('policy').getRawValue();
        r.target_name = f.findField('target').getRawValue();
        
        var g = Ext.getCmp('modx-grid-user-group-resource-groups');
        var s = g.getStore();
        var v = s.query('id',r.id).items;
        if (v.length > 0) {
            MODx.msg.alert(_('error'),_('user_group_resourcegroup_err_ae'));
            return false;
        }
        
        this.fireEvent('success',r);
        this.hide();
        return false;
    }
});
Ext.reg('modx-window-user-group-resourcegroup-create',MODx.window.CreateUGRG);