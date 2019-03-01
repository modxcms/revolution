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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/profile'
        }
        ,layout: 'anchor'
        ,cls: 'container'
        ,bodyStyle: 'background: none;'
        ,border: false
        ,items: [{
            html: _('profile')
            ,id: 'modx-profile-header'
            ,xtype: 'modx-header'
        },this.getTabs(config)]
    });
    MODx.panel.Profile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Profile,MODx.Panel,{
    getTabs: function(config) {
        var items = [{
            xtype: 'modx-panel-profile-update'
            ,id: 'modx-panel-profile-update'
            ,user: config.user
            ,preventRender: true
        }];
        if (MODx.perm.change_password) {
            items.push({
                xtype: 'modx-panel-profile-password-change'
                ,id: 'modx-panel-profile-password-change'
                ,user: config.user
                ,preventRender: true
            });
        }
        if (MODx.perm.view_document) {
            items.push({
                title: _('profile_recent_resources')
                ,bodyStyle: 'padding: 15px;'
                ,id: 'modx-profile-recent-docs'
                ,autoHeight: true
                ,layout: 'anchor'
                ,items: [{
                    html: '<p>'+_('profile_recent_resources_desc')+'</p><br />'
                    ,id: 'modx-profile-recent-docs-msg'
                    ,border: false
                },{
                    xtype: 'modx-grid-user-recent-resource'
                    ,user: config.user
                    ,preventRender: true
                }]
            });
        }
        return MODx.getPageStructure(items);
    }
});
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/profile/update'
            ,id: config.user
        }
        ,layout: 'form'
        ,buttonAlign: 'right'
        ,cls: 'container form-with-labels'
        ,labelAlign: 'top'
        ,defaults: {
            border: false
            ,msgTarget: 'under'
            ,anchor: '100%'
        }
        ,labelWidth: 150
        ,items: [{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,labelSeparator: ''
                ,anchor: '100%'
                ,border: false
            }
            ,items: [{
                columnWidth: .5
                ,items: {
                    xtype: 'textfield'
                    ,fieldLabel: _('user_full_name')
                    ,name: 'fullname'
                    ,anchor: '100%'
                    ,maxLength: 255
                    ,allowBlank: false
                }
            },{
                columnWidth: .5
                ,items: {
                    xtype: 'textfield'
                    ,fieldLabel: _('email')
                    ,name: 'email'
                    ,anchor: '100%'
                    ,vtype: 'email'
                    ,allowBlank: false
                }
            }]
        },{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,labelSeparator: ''
                ,anchor: '100%'
                ,border: false
            }
            ,items: [{
                columnWidth: .35
                ,items: {
                    xtype: 'datefield'
                    ,fieldLabel: _('user_dob')
                    ,name: 'dob'
                    ,anchor: '100%'
                    ,format: MODx.config.manager_date_format
                }
            },{
                columnWidth: .35
                ,items: {
                    fieldLabel: _('user_photo')
                    ,name: 'photo'
                    ,xtype: 'modx-combo-browser'
                    ,anchor: '100%'
                    ,hideFiles: true
                    ,source: MODx.config['photo_profile_source'] || MODx.config.default_media_source
                    ,hideSourceCombo: true
                }
            },{
                columnWidth: .3
                ,items: {
                    fieldLabel: _('user_gender')
                    ,name: 'gender'
                    ,hiddenName: 'gender'
                    ,xtype: 'modx-combo-gender'
                    ,anchor: '100%'
                }
            }]
        },{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,labelSeparator: ''
                ,anchor: '100%'
                ,border: false
            }
            ,items: [{
                columnWidth: .35
                ,items: {
                    xtype: 'textfield'
                    ,fieldLabel: _('user_phone')
                    ,name: 'phone'
                    ,anchor: '100%'
                }
            },{
                columnWidth: .35
                ,items: {
                    xtype: 'textfield'
                    ,fieldLabel: _('user_mobile')
                    ,name: 'mobilephone'
                    ,anchor: '100%'
                }
            },{
                columnWidth: .3
                ,items: {
                    xtype: 'textfield'
                    ,fieldLabel: _('user_fax')
                    ,name: 'fax'
                    ,anchor: '100%'
                }
            }]
        },{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,labelSeparator: ''
                ,anchor: '100%'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: {
                    xtype: 'textfield'
                    ,fieldLabel: _('user_website')
                    ,name: 'website'
                    ,maxLength: 25
                    ,anchor: '100%'
                }
            }]
        },{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,labelSeparator: ''
                ,anchor: '100%'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: {
                    xtype: 'modx-combo-country'
                    ,fieldLabel: _('user_country')
                    ,anchor: '100%'
                    ,value: ''
                }
            }]
        },{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,labelSeparator: ''
                ,anchor: '100%'
                ,border: false
            }
            ,items: [{
                columnWidth: .35
                ,items: {
                    xtype: 'textfield'
                    ,fieldLabel: _('user_state')
                    ,name: 'state'
                    ,maxLength: 50
                    ,anchor: '100%'
                }
            },{
                columnWidth: .35
                ,items: {
                    xtype: 'textfield'
                    ,fieldLabel: _('city')
                    ,name: 'city'
                    ,maxLength: 255
                    ,anchor: '100%'
                }
            },{
                columnWidth: .3
                ,items: {
                    xtype: 'textfield'
                    ,fieldLabel: _('user_zip')
                    ,name: 'zip'
                    ,maxLength: 25
                    ,anchor: '100%'
                }
            }]
        },{
            xtype: 'textarea'
            ,fieldLabel: _('address')
            ,name: 'address'
            ,anchor: '100%'
            ,grow: true
        }]
        // TODO: this button should be in a actionbar like any other panel
        ,buttons: [{
            text: _('save')
            ,scope: this
            ,handler: this.submit
            ,cls:'primary-button'
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
            url: MODx.config.connector_url
            ,params: {
                action: 'security/profile/get'
                ,id: this.config.user
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    Ext.get('modx-profile-header').update(_('profile')+': '+Ext.util.Format.htmlEncode(r.object.username));
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/profile/changepassword'
            ,id: config.user
        }
        // ,frame: true
        ,layout: 'form'
        ,buttonAlign: 'right'
        ,labelAlign: 'top'
        ,cls: 'container form-with-labels'
        // ,cls: 'main-wrapper'
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
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,labelSeparator: ''
                ,anchor: '100%'
                ,border: false
            }
            ,items: [{
                columnWidth: .5
                ,items: {
                    xtype: 'textfield'
                    ,fieldLabel: _('change_password_new')
                    ,name: 'password_new'
                    ,inputType: 'password'
                    ,maxLength: 255
                    ,anchor: '100%'
                }
            },{
                columnWidth: .5
                ,items: {
                    xtype: 'textfield'
                    ,fieldLabel: _('change_password_confirm')
                    ,name: 'password_confirm'
                    ,id: 'modx-password-confirm'
                    ,inputType: 'password'
                    ,maxLength: 255
                    ,anchor: '100%'
                }
            }]
        },{
            xtype: 'xcheckbox'
            ,boxLabel: _('password_method_screen')
            ,name: 'password_method_screen'
            ,id: 'modx-password-method-screen'
            ,inputValue: true
            ,hideLabel: true
            ,checked: true
        }]
        // TODO: this button should be in a actionbar like any other panel
        ,buttons: [{
            text: _('save')
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
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
