/**
 * Loads the profile page
 * 
 * @class MODx.page.Profile
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-page-profile
 */
MODx.page.Profile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-profile'
            ,renderTo: 'modx-panel-profile-div'
            ,user: config.user
        }]
    });
    MODx.page.Profile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Profile,MODx.Component);
Ext.reg('modx-page-profile',MODx.page.Profile);

MODx.panel.Profile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-profile'
        ,url: MODx.config.connectors_url+'security/profile.php'
        ,layout: 'fit'
        ,cls: 'container'
        ,bodyStyle: 'background: none;'
        ,border: false
        ,items: [{
            html: '<h2>'+_('profile')+'</h2>'
            ,id: 'modx-profile-header'
            ,cls: 'modx-page-header'
            ,border: false
            ,autoHeight: true
            ,anchor: '100%'
        },MODx.getPageStructure([{
            xtype: 'modx-panel-profile-update'
            ,id: 'modx-panel-profile-update'
            ,user: config.user
            ,preventRender: true
        },{
            xtype: 'modx-panel-profile-password-change'
            ,id: 'modx-panel-profile-password-change'
            ,user: config.user
            ,preventRender: true
        },{
            title: _('profile_recent_resources')
            ,bodyStyle: 'padding: 15px;'
            ,id: 'modx-profile-recent-docs'
            ,autoHeight: true
            ,items: [{
                html: '<p>'+_('profile_recent_resources_desc')+'</p><br />'
                ,id: 'modx-profile-recent-docs-msg'
                ,border: false
            },{
                xtype: 'modx-grid-user-recent-resource'
                ,user: config.user
                ,preventRender: true
            }]
        }],{
            border: true
            ,defaults: { bodyStyle: 'padding: 15px; '}
            ,id: 'modx-panel-profile-tabs'
        })]
    });
    MODx.panel.Profile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Profile,MODx.Panel);
Ext.reg('modx-panel-profile',MODx.panel.Profile);

/**
 * The information panel for the profile
 * 
 * @class MODx.panel.UpdateProfile
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype: panel-profile-update
 */
MODx.panel.UpdateProfile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('general_information')
        ,id: 'modx-panel-profile-update'
        ,url: MODx.config.connectors_url+'security/profile.php'
        ,baseParams: {
            action: 'update'
            ,id: config.user
        }
        ,layout: 'form'
        ,buttonAlign: 'center'
        ,cls: 'container form-with-labels'
        ,labelAlign: 'top'
        ,defaults: { border: false ,msgTarget: 'under' }
        ,labelWidth: 150
        ,items: [{
            xtype: 'textfield'
            ,fieldLabel: _('user_full_name')
            ,name: 'fullname'
            ,maxLength: 255
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('email')
            ,name: 'email'
            ,vtype: 'email'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_phone')
            ,name: 'phone'
            ,width: 200
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_mobile')
            ,name: 'mobilephone'
            ,width: 200
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_fax')
            ,name: 'fax'
            ,width: 200
        },{
            xtype: 'datefield'
            ,fieldLabel: _('user_dob')
            ,name: 'dob'
            ,width: 200
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_state')
            ,name: 'state'
            ,maxLength: 50
            ,width: 150
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_zip')
            ,name: 'zip'
            ,maxLength: 20
            ,width: 150
        }]
        ,buttons: [{
            text: _('save')
            ,scope: this
            ,handler: this.submit
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
        }
    });
    MODx.panel.UpdateProfile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.UpdateProfile,MODx.FormPanel,{
    setup: function() {
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'security/profile.php'
            ,params: {
                action: 'get'
                ,id: this.config.user
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                },scope:this}
            }
        });
    }
});
Ext.reg('modx-panel-profile-update',MODx.panel.UpdateProfile);

/**
 * A panel for changing the user password
 * 
 * @class MODx.panel.ChangeProfilePassword
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype modx-panel-profile-password-change
 */
MODx.panel.ChangeProfilePassword = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('reset_password')
        ,url: MODx.config.connectors_url+'security/profile.php'
        ,baseParams: {
            action: 'changepassword'
            ,id: config.user
        }
        ,frame: true
        ,layout: 'form'
        ,buttonAlign: 'center'
        ,labelAlign: 'top'
        ,cls: 'container form-with-labels'
        ,defaults: { border: false ,msgTarget: 'under' }
        ,labelWidth: 150
        ,items: [{
            xtype: 'textfield'
            ,fieldLabel: _('password_old')
            ,name: 'password_old'
            ,inputType: 'password'
            ,maxLength: 255
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('password')
            ,name: 'password_new'
            ,inputType: 'password'
            ,maxLength: 255
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('password_confirm')
            ,name: 'password_confirm'
            ,id: 'modx-password-confirm'
            ,inputType: 'password'
            ,maxLength: 255
            ,anchor: '100%'
        },{
            xtype: 'xcheckbox'
            ,boxLabel: _('password_method_screen')
            ,name: 'password_method_screen'
            ,id: 'modx-password-method-screen'
            ,inputValue: true
            ,hideLabel: true
            ,checked: true
        }]
        ,buttons: [{
            text: _('save')
            ,scope: this
            ,handler: this.submit
        }]
        ,listeners: {
            'success': {fn:this.success,scope:this}
        }
    });
    MODx.panel.ChangeProfilePassword.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ChangeProfilePassword,MODx.FormPanel,{
    success: function(o) {
        MODx.msg.alert(_('success'),o.result.message);
    }
});
Ext.reg('modx-panel-profile-password-change',MODx.panel.ChangeProfilePassword);