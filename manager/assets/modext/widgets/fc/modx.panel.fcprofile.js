/**
 * @class MODx.panel.FCProfile
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-fc-profile
 */
MODx.panel.FCProfile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'security/forms/profile.php'
        ,baseParams: {}
        ,id: 'modx-panel-fc-profile'
		,cls: 'container'
        ,class_key: 'modFormCustomizationProfile'
        ,bodyStyle: ''
        ,items: [{
            html: '<h2>'+_('profile_new')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-fcp-header'
        },MODx.getPageStructure([{
            title: _('profile')
            ,defaults: { border: false ,msgTarget: 'side' }
            ,layout: 'form'
            ,id: 'modx-chunk-form'
            ,labelWidth: 150
            ,items: [{
                html: '<p>'+_('profile_msg')+'</p>'
				,bodyCssClass: 'panel-desc'
                ,id: 'modx-fcp-msg'
                ,border: false
            },{
				xtype: 'panel'
				,border: false
				,cls:'main-wrapper'
				,layout: 'form'
				,items: [{
					xtype: 'hidden'
					,name: 'id'
					,id: 'modx-fcp-id'
					,value: config.record.id || MODx.request.id
				},{
					xtype: 'textfield'
					,fieldLabel: _('name')
					,name: 'name'
					,id: 'modx-fcp-name'
					,anchor: '90%'
					,maxLength: 255
					,enableKeyEvents: true
					,allowBlank: false
					,value: config.record.name
					,listeners: {
						'keyup': {scope:this,fn:function(f,e) {
							Ext.getCmp('modx-fcp-header').getEl().update('<h2>'+_('profile')+': '+f.getValue()+'</h2>');
						}}
					}
				},{
					xtype: 'textarea'
					,fieldLabel: _('description')
					,name: 'description'
					,id: 'modx-fcp-description'
					,anchor: '90%'
					,maxLength: 255
					,grow: false
					,value: config.record.description
				},{
					xtype: 'xcheckbox'
					,fieldLabel: _('active')
					,name: 'active'
					,id: 'modx-fcp-active'
					,inputValue: true
					,value: config.record.active ? true : false
					,anchor: '90%'
					,allowBlank: true
				}]
            },{ html: '<hr />' },{
                xtype: 'modx-grid-fc-set'
				,cls:'main-wrapper'
                ,baseParams: {
                    action: 'getList'
                    ,profile: config.record.id
                }
                ,preventRender: true
            }]
        },{
            title: _('usergroups')
            ,items: [{
                html: '<p>'+_('profile_usergroups_msg')+'</p>'
				,bodyCssClass: 'panel-desc'
                ,border: false
            },{
                xtype: 'modx-grid-fc-profile-usergroups'
				,cls:'main-wrapper'
                ,data: config.record.usergroups || []
                ,preventRender: true
            }]
        }],{
            id: 'modx-fc-profile-tabs'
        })]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.FCProfile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.FCProfile,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (!this.initialized) { this.getForm().setValues(this.config.record); }
        if (!Ext.isEmpty(this.config.record.name)) {
            Ext.getCmp('modx-fcp-header').getEl().update('<h2>'+_('profile')+': '+this.config.record.name+'</h2>');
        }
        this.fireEvent('ready',this.config.record);
        this.clearDirty();
        this.initialized = true;
        MODx.fireEvent('ready');
        return true;
    }
    ,beforeSubmit: function(o) {
        Ext.apply(o.form.baseParams,{
            usergroups: Ext.getCmp('modx-grid-fc-profile-usergroups').encode()
        });
        return this.fireEvent('save',{
            values: this.getForm().getValues()
        });
    }
    ,success: function(r) {
        Ext.getCmp('modx-grid-fc-profile-usergroups').getStore().commitChanges();
        this.getForm().setValues(r.result.object);
    }
});
Ext.reg('modx-panel-fc-profile',MODx.panel.FCProfile);

MODx.grid.FCProfileUserGroups = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-grid-fc-profile-usergroups'
        ,fields: ['id','name']
        ,autoHeight: true
        ,stateful: false
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
        }]
        ,tbar: [{
            text: _('usergroup_create')
            ,handler: this.addUserGroup
            ,scope: this
        }]
    });
    MODx.grid.FCProfileUserGroups.superclass.constructor.call(this,config);
    this.fcugRecord = Ext.data.Record.create(config.fields);
};
Ext.extend(MODx.grid.FCProfileUserGroups,MODx.grid.LocalGrid,{
    addUserGroup: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-fc-profile-add-usergroup'
            ,listeners: {
                'success': {fn:function(r) {
                    var s = this.getStore();
                    var rec = new this.fcugRecord(r);
                    s.add(rec);
                },scope:this}
            }
        });
    }

    ,getMenu: function(g,ri) {
        return [{
            text: _('usergroup_remove')
            ,handler: this.removeUserGroup
            ,scope: this
        }];
    }

    ,removeUserGroup: function(btn,e) {
        var rec = this.getSelectionModel().getSelected();
        Ext.Msg.confirm(_('usergroup_remove'),_('usergroup_remove_confirm'),function(e) {
            if (e == 'yes') {
                this.getStore().remove(rec);
            }
        },this);
    }
});
Ext.reg('modx-grid-fc-profile-usergroups',MODx.grid.FCProfileUserGroups);



MODx.window.AddGroupToProfile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('usergroup_create')
        ,height: 150
        ,width: 375
        ,fields: [{
            fieldLabel: _('user_group')
            ,name: 'usergroup'
            ,hiddenName: 'usergroup'
            ,id: 'modx-fcaug-usergroup'
            ,xtype: 'modx-combo-usergroup'
            ,editable: false
            ,allowBlank: false
            ,anchor: '100%'
        }]
    });
    MODx.window.AddGroupToProfile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddGroupToProfile,MODx.Window,{
    submit: function() {
        var rec = {};
        rec.id = Ext.getCmp('modx-fcaug-usergroup').getValue();
        rec.name = Ext.getCmp('modx-fcaug-usergroup').getRawValue();

        var g = Ext.getCmp('modx-grid-fc-profile-usergroups');
        var s = g.getStore();
        var v = s.findExact('id',rec.id);
        if (v != '-1') {
            MODx.msg.alert(_('error'),_('profile_usergroup_err_ae'));
            return false;
        }

        this.fireEvent('success',rec);
        this.hide();
        return false;
    }
});
Ext.reg('modx-window-fc-profile-add-usergroup',MODx.window.AddGroupToProfile);
