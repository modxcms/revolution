/**
 * @class MODx.panel.FCProfile
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-fc-profile
 */
MODx.panel.FCProfile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/forms/profile/get'
        }
        ,id: 'modx-panel-fc-profile'
		,cls: 'container'
        ,class_key: 'modFormCustomizationProfile'
        ,bodyStyle: ''
        ,items: [{
            html: _('profile_new')
            ,id: 'modx-fcp-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('profile')
            ,defaults: { border: false ,msgTarget: 'side' }
            ,layout: 'form'
            ,id: 'modx-chunk-form'
            ,labelWidth: 150
            ,items: [{
                html: '<p>'+_('profile_msg')+'</p>'
                ,id: 'modx-fcp-msg'
                ,xtype: 'modx-description'
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
					,anchor: '100%'
					,maxLength: 255
					,enableKeyEvents: true
					,allowBlank: false
					,value: config.record.name
					,listeners: {
						'keyup': {scope:this,fn:function(f,e) {
							Ext.getCmp('modx-fcp-header').getEl().update(_('profile')+': '+f.getValue());
						}}
					}
				},{
					xtype: 'textarea'
					,fieldLabel: _('description')
					,name: 'description'
					,id: 'modx-fcp-description'
					,anchor: '100%'
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
					,anchor: '100%'
					,allowBlank: true
				}]
            },{
                xtype: 'modx-grid-fc-set'
				,cls:'main-wrapper'
                ,baseParams: {
                    action: 'security/forms/set/getList'
                    ,profile: config.record.id
                }
                ,preventRender: true
            }]
        },{
            title: _('usergroups')
            ,layout: 'anchor'
            ,items: [{
                html: '<p>'+_('profile_usergroups_msg')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-fc-profile-usergroups'
				,cls:'main-wrapper'
                ,data: config.record.usergroups || []
                ,preventRender: true
            }]
        }],{
            id: 'modx-fc-profile-tabs'
        })]
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
            Ext.defer(function() {
                Ext.getCmp('modx-fcp-header').update(_('profile')+': '+this.config.record.name);
            }, 250, this);
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
            ,cls: 'primary-button'
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
        // ,height: 150
        // ,width: 375
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
