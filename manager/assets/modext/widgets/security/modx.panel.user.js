/**
 * @class MODx.panel.User
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-user
 */
MODx.panel.User = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'security/user.php'
        ,baseParams: {}
        ,id: 'modx-panel-user'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,bodyStyle: ''
        ,items: [{
             html: '<h2>'+_('user_new')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-user-header'
        },{
            xtype: 'modx-tabs'
            ,id: 'modx-user-tabs'
            ,deferredRender: false
            ,border: true
            ,defaults: {
                autoHeight: true
                ,layout: 'form'
                ,labelWidth: 150
                ,bodyStyle: 'padding: 15px;'
            }
            ,items: this.getFields(config)
        }]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.User.superclass.constructor.call(this,config);
    Ext.getCmp('modx-user-panel-newpassword').getEl().dom.style.display = 'none';
    Ext.getCmp('modx-user-password-genmethod-s').on('check',this.showNewPassword,this);
};
Ext.extend(MODx.panel.User,MODx.FormPanel,{
    setup: function() {
        if (this.config.user === '' || this.config.user === 0) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.user
                ,getGroups: true
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    
                    var d = Ext.decode(r.object.groups);
                    var g = Ext.getCmp('modx-grid-user-groups');
                    if (g) {
                        var s = g.getStore();
                        if (s) { s.loadData(d); }
                    }
                    Ext.get('modx-user-header').update('<h2>'+_('user')+': '+r.object.username+'</h2>');
                    this.fireEvent('ready',r.object);
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        var d = {};
        var g = Ext.getCmp('modx-grid-user-settings');
        if (g) { d.settings = g.encodeModified(); }
        
        var h = Ext.getCmp('modx-grid-user-groups');
        if (h) { d.groups = h.encode(); }

        var t = Ext.getCmp('modx-remote-tree');
        if (t) { d.remote_data = t.encode(); }

        var et = Ext.getCmp('modx-extended-tree');
        if (et) { d.extended = et.encode(); }
        
        Ext.apply(o.form.baseParams,d);
    }
    
    ,success: function(o) {
        var userId = this.config.user;
        if (Ext.getCmp('modx-user-passwordnotifymethod-s').getValue() === true && o.result.message != '') {
            Ext.Msg.hide();
            Ext.Msg.show({
                title: _('password_notification')
                ,msg: o.result.message
                ,buttons: Ext.Msg.OK
                ,fn: function(btn) {
                    if (userId == 0) {
                        location.href = '?a='+MODx.action['security/user']+'&id='+o.result.object.id;
                    }
                    return false;
                }
            });
        } else if (userId == 0) {
            location.href = '?a='+MODx.action['security/user']+'&id='+o.result.object.id;
        }
    }
    
    ,showNewPassword: function(cb,v) {
        var el = Ext.getCmp('modx-user-panel-newpassword').getEl();
        if (v) {
            el.slideIn('t',{useDisplay:true});
        } else {
            el.slideOut('t',{useDisplay:true});
        }
    }
    
    ,getFields: function(config) {
        var f = [{
            title: _('general_information')
            ,defaults: { msgTarget: 'side' ,autoHeight: true }
            ,items: [{
                id: 'modx-user-id'
                ,name: 'id'
                ,xtype: 'hidden'
                ,value: config.user
            },{
                id: 'modx-user-newpassword'
                ,name: 'newpassword'
                ,xtype: 'hidden'
                ,value: false
            },{
                id: 'modx-user-fs-newpassword'
                ,title: _('password_new')
                ,xtype: 'fieldset'
                ,checkboxToggle: true
                ,collapsed: (config.user ? true : false)
                ,forceLayout: true
                ,listeners: {
                    'expand': {fn:function(p) {
                        Ext.getCmp('modx-user-newpassword').setValue(true);
                    },scope:this}
                    ,'collapse': {fn:function(p) {
                        Ext.getCmp('modx-user-newpassword').setValue(false);
                    },scope:this}
                }
                ,items: [{
                    xtype: 'radiogroup'
                    ,fieldLabel: _('password_method')
                    ,columns: 1
                    ,items: [{
                        id: 'modx-user-passwordnotifymethod-e'
                        ,name: 'passwordnotifymethod'
                        ,boxLabel: _('password_method_email')
                        ,xtype: 'radio'
                        ,value: 'e'
                        ,inputValue: 'e'
                    },{
                        id: 'modx-user-passwordnotifymethod-s'
                        ,name: 'passwordnotifymethod'
                        ,boxLabel: _('password_method_screen')
                        ,xtype: 'radio'
                        ,value: 's'
                        ,inputValue: 's'
                        ,checked: true
                    }]
                },{
                    xtype: 'radiogroup'
                    ,fieldLabel: _('password_gen_method')
                    ,columns: 1
                    ,items: [{
                        id: 'modx-user-password-genmethod-g'
                        ,name: 'passwordgenmethod'
                        ,boxLabel: _('password_gen_gen')
                        ,xtype: 'radio'
                        ,inputValue: 'g'
                        ,value: 'g'
                        ,checked: true
                    },{
                        id: 'modx-user-password-genmethod-s'
                        ,name: 'passwordgenmethod'
                        ,boxLabel: _('password_gen_specify')
                        ,xtype: 'radio'
                        ,inputValue: 'spec'
                        ,value: 'spec'
                    }]
                },{
                    id: 'modx-user-panel-newpassword'
                    ,xtype: 'panel'
                    ,layout: 'form'
                    ,border: false
                    ,autoHeight: true
                    ,items: [{
                        id: 'modx-user-specifiedpassword'
                        ,name: 'specifiedpassword'
                        ,fieldLabel: _('change_password_new')
                        ,xtype: 'textfield'
                        ,inputType: 'password'
                        ,width: 175
                    },{
                        id: 'modx-user-confirmpassword'
                        ,name: 'confirmpassword'
                        ,fieldLabel: _('change_password_confirm')
                        ,xtype: 'textfield'
                        ,inputType: 'password'
                        ,width: 175
                    }]
                }]
            },{
                id: 'modx-user-fs-general'
                ,title: _('general_information')
                ,xtype: 'fieldset'
                ,items: [{
                    id: 'modx-user-username'
                    ,name: 'username'
                    ,fieldLabel: _('username')
                    ,xtype: 'textfield'
                    ,width: 300
                },{
                    id: 'modx-user-active'
                    ,name: 'active'
                    ,fieldLabel: _('active')
                    ,xtype: 'checkbox'
                    ,inputValue: 1
                },{
                    id: 'modx-user-fullname'
                    ,name: 'fullname'
                    ,fieldLabel: _('user_full_name')
                    ,xtype: 'textfield'
                    ,width: 300
                    ,maxLength: 255
                },{
                    id: 'modx-user-email'
                    ,name: 'email'
                    ,fieldLabel: _('user_email')
                    ,xtype: 'textfield'
                    ,width: 300
                    ,maxLength: 255
                    ,allowBlank: false
                },{
                    id: 'modx-user-phone'
                    ,name: 'phone'
                    ,fieldLabel: _('user_phone')
                    ,xtype: 'textfield'
                    ,width: 200
                    ,maxLength: 255
                },{
                    id: 'modx-user-mobilephone'
                    ,name: 'mobilephone'
                    ,fieldLabel: _('user_mobile')
                    ,xtype: 'textfield'
                    ,width: 200
                    ,maxLength: 255
                },{
                    id: 'modx-user-address'
                    ,name: 'address'
                    ,fieldLabel: _('address')
                    ,xtype: 'textarea'
                    ,width: 300
                    ,grow: true
                },{
                    id: 'modx-user-city'
                    ,name: 'city'
                    ,fieldLabel: _('city')
                    ,xtype: 'textfield'
                    ,width: 300
                    ,maxLength: 255
                },{
                    id: 'modx-user-fax'
                    ,name: 'fax'
                    ,fieldLabel: _('user_fax')
                    ,xtype: 'textfield'
                    ,width: 200
                    ,maxLength: 255
                },{
                    id: 'modx-user-state'
                    ,name: 'state'
                    ,fieldLabel: _('user_state')
                    ,xtype: 'textfield'
                    ,width: 100
                    ,maxLength: 100
                },{
                    id: 'modx-user-zip'
                    ,name: 'zip'
                    ,fieldLabel: _('user_zip')
                    ,xtype: 'textfield'
                    ,width: 100
                    ,maxLength: 25
                },{
                    id: 'modx-user-country'
                    ,fieldLabel: _('user_country')
                    ,xtype: 'modx-combo-country'
                    ,value: ''
                },{
                    id: 'modx-user-website'
                    ,name: 'website'
                    ,fieldLabel: _('user_website')
                    ,xtype: 'textfield'
                    ,width: 300
                    ,maxLength: 255
                },{
                    id: 'modx-user-dob'
                    ,name: 'dob'
                    ,fieldLabel: _('user_dob')
                    ,xtype: 'datefield'
                    ,width: 150
                    ,allowBlank: true
                    ,format: MODx.config.manager_date_format
                },{
                    id: 'modx-user-gender'
                    ,name: 'gender'
                    ,hiddenName: 'gender'
                    ,fieldLabel: _('user_gender')
                    ,xtype: 'modx-combo-gender'
                },{
                    id: 'modx-user-comment'
                    ,name: 'comment'
                    ,fieldLabel: _('comment')
                    ,xtype: 'textarea'
                    ,width: 300
                    ,grow: true
                },{
                    html: MODx.onUserFormRender
                    ,border: false
                }]
            },{
                id: 'modx-user-fs-blocked'
                ,title: _('login_options')
                ,xtype: 'fieldset'
                ,items: [{
                    id: 'modx-user-logincount'
                    ,name: 'logincount'
                    ,fieldLabel: _('user_logincount')
                    ,xtype: 'statictextfield'
                },{
                    id: 'modx-user-lastlogin'
                    ,name: 'lastlogin'
                    ,fieldLabel: _('user_prevlogin')
                    ,xtype: 'statictextfield'
                },{
                    id: 'modx-user-failedlogincount'
                    ,name: 'failedlogincount'
                    ,fieldLabel: _('user_failedlogincount')
                    ,xtype: 'textfield'
                },{
                    id: 'modx-user-blocked'
                    ,name: 'blocked'
                    ,fieldLabel: _('user_block')
                    ,xtype: 'checkbox'
                    ,inputValue: 1
                },{
                    id: 'modx-user-blockeduntil'
                    ,name: 'blockeduntil'
                    ,fieldLabel: _('user_blockeduntil')
                    ,xtype: 'datefield'
                    ,width: 150
                    ,allowBlank: true
                    ,format: MODx.config.manager_date_format
                },{
                    id: 'modx-user-blockedafter'
                    ,name: 'blockedafter'
                    ,fieldLabel: _('user_blockedafter')
                    ,xtype: 'datefield'
                    ,width: 150
                    ,allowBlank: true
                    ,format: MODx.config.manager_date_format
                }]
            }]
        }];
        if (config.user != 0) {
            f.push({
                title: _('settings')
                ,autoHeight: true
                ,defaults: { autoHeight: true }
                ,hideMode: 'offsets'
                ,items: [{
                    html: '<h3>'+_('user_settings')+'</h3>'
                    ,border: false
                },{
                    html: '<p>'+_('user_settings_desc')+'</p>'
                    ,border: false
                },{
                    xtype: 'modx-grid-user-settings'
                    ,preventRender: true
                    ,user: config.user
                    ,width: '97%'
                }]
            })
        }
        f.push({
            title: _('access_permissions')
            ,layout: 'form'
            ,bodyStyle: 'padding: 15px;'
            ,defaults: { border: false ,autoHeight: true }
            ,hideMode: 'offsets'
            ,items: [{
                html: _('access_permissions_user_message')
            },MODx.PanelSpacer,{
                xtype: 'modx-grid-user-groups'
                ,title: ''
                ,preventRender: true
                ,user: config.user
                ,width: '97%'
                ,listeners: {
                    'afterRemoveRow':{fn:this.markDirty,scope:this}
                    ,'afterUpdateRole':{fn:this.markDirty,scope:this}
                    ,'afterAddGroup':{fn:this.markDirty,scope:this}
                }
            }]
        });
        if (config.remoteFields && config.remoteFields.length) {
            f.push({
                title: _('remote_data')
                ,layout: 'form'
                ,bodyStyle: 'padding: 15px;'
                ,defaults: { border: false ,autoHeight: true }
                ,hideMode: 'offsets'
                ,items: [{
                    html: '<p>'+_('user_remote_data_msg')+'</p>'
                },{
                    layout: 'column'
                    ,items: [{
                        columnWidth: 0.4
                        ,title: _('attributes')
                        ,layout: 'fit'
                        ,border: false
                        ,items: {
                            xtype: 'modx-orm-tree'
                            ,id: 'modx-remote-tree'
                            ,data: config.remoteFields
                            ,formPanel: 'modx-panel-user'
                            ,prefix: 'remote'
                        }
                    },{
                        xtype: 'modx-orm-form'
                        ,columnWidth: 0.6
                        ,title: _('editing_form')
                        ,id: 'modx-remote-form'
                        ,prefix: 'remote'
                        ,treePanel: 'modx-remote-tree'
                        ,formPanel: 'modx-panel-user'
                    }]
                }]
            });
        }
        config.extendedFields = config.extendedFields || [];
        f.push({
            title: _('extended_fields')
            ,layout: 'form'
            ,bodyStyle: 'padding: 15px;'
            ,defaults: { border: false ,autoHeight: true }
            ,hideMode: 'offsets'
            ,items: [{
                html: '<p>'+_('extended_fields_msg')+'</p>'
            },{
                layout: 'column'
                ,items: [{
                    columnWidth: 0.4
                    ,title: _('attributes')
                    ,layout: 'fit'
                    ,border: false
                    ,items: {
                        xtype: 'modx-orm-tree'
                        ,id: 'modx-extended-tree'
                        ,data: config.extendedFields
                        ,formPanel: 'modx-panel-user'
                        ,prefix: 'extended'
                    }
                },{
                    xtype: 'modx-orm-form'
                    ,columnWidth: 0.6
                    ,title: _('editing_form')
                    ,id: 'modx-extended-form'
                    ,prefix: 'extended'
                    ,treePanel: 'modx-extended-tree'
                    ,formPanel: 'modx-panel-user'
                }]
            }]
        });
        return f;
    }
});
Ext.reg('modx-panel-user',MODx.panel.User);

/**
 * Displays a gender combo
 * 
 * @class MODx.combo.Gender
 * @extends Ext.form.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-gender
 */
MODx.combo.Gender = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [['',0],[_('user_male'),1],[_('user_female'),2]]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
    });
    MODx.combo.Gender.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Gender,Ext.form.ComboBox);
Ext.reg('modx-combo-gender',MODx.combo.Gender);