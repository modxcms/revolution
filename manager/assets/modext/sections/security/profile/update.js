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
        formpanel: 'modx-panel-profile-update'
        ,buttons: [{
            process: 'Security/Profile/Update'
            ,reload: true
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,redirect: false
            ,method: 'remote'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,id: 'modx-abtn-cancel'
        }]
        ,components: [{
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
            action: 'Security/Profile'
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
            action: 'Security/Profile/Update'
            ,id: config.user
        }
        ,layout: 'column'
        ,buttonAlign: 'right'
        ,cls: 'container form-with-labels'
        ,labelAlign: 'top'
        ,border: false
        ,defaults: {
            layout: 'form'
            ,labelAlign: 'top'
            ,labelSeparator: ''
            ,anchor: '100%'
            ,border: false
        }
        ,labelWidth: 150
        ,items: [{
            columnWidth: .5
            ,items: [{
                id: 'modx-user-username'
                ,name: 'username'
                ,fieldLabel: _('username')
                ,description: _('user_username_desc')
                ,xtype: 'statictextfield'
                ,allowBlank: false
                ,anchor: '100%'
            },{
                id: 'modx-user-email'
                ,name: 'email'
                ,fieldLabel: _('user_email')
                ,xtype: 'textfield'
                ,vtype: 'email'
                ,allowBlank: false
                ,anchor: '100%'
                ,maxLength: 255
            },{
                id: 'modx-user-fullname'
                ,name: 'fullname'
                ,fieldLabel: _('user_full_name')
                ,xtype: 'textfield'
                ,anchor: '100%'
                ,maxLength: 255
            },{
                id: 'modx-user-photo'
                ,name: 'photo'
                ,fieldLabel: _('user_photo')
                ,xtype: 'modx-combo-browser'
                ,hideFiles: true
                ,source: MODx.config['photo_profile_source'] || MODx.config.default_media_source
                ,hideSourceCombo: true
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
                        id: 'modx-user-dob'
                        ,name: 'dob'
                        ,fieldLabel: _('user_dob')
                        ,xtype: 'datefield'
                        ,anchor: '100%'
                        ,format: MODx.config.manager_date_format
                    }
                },{
                    columnWidth: .5
                    ,items: {
                        id: 'modx-user-gender'
                        ,name: 'gender'
                        ,hiddenName: 'gender'
                        ,fieldLabel: _('user_gender')
                        ,xtype: 'modx-combo-gender'
                        ,anchor: '100%'
                    }
                }]
            },{
                id: 'modx-user-website'
                ,name: 'website'
                ,fieldLabel: _('user_website')
                ,xtype: 'textfield'
                ,anchor: '100%'
                ,maxLength: 255
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
                        id: 'modx-user-phone'
                        ,name: 'phone'
                        ,fieldLabel: _('user_phone')
                        ,xtype: 'textfield'
                        ,anchor: '100%'
                        ,maxLength: 255
                    }
                },{
                    columnWidth: .5
                    ,items: {
                        id: 'modx-user-mobilephone'
                        ,name: 'mobilephone'
                        ,fieldLabel: _('user_mobile')
                        ,xtype: 'textfield'
                        ,anchor: '100%'
                        ,maxLength: 255
                    }
                }]
            },{
                id: 'modx-user-fax'
                ,name: 'fax'
                ,fieldLabel: _('user_fax')
                ,xtype: 'textfield'
                ,anchor: '100%'
                ,maxLength: 255
            }]
        },{
            columnWidth: .5
            ,defaults: {
                msgTarget: 'under'
            }
            ,items: this.getItemsRight(config)
        }]
        ,listeners: {
            'setup': {fn:this.setup, scope:this}
        }
    });
    MODx.panel.UpdateProfile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.UpdateProfile,MODx.FormPanel,{
    setup: function() {
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'Security/Profile/Get'
                ,id: this.config.user
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    Ext.get('modx-profile-header').update(_('profile')+': '+Ext.util.Format.htmlEncode(r.object.username));
                },scope:this}
            }
        });
    },
    getItemsRight: function (config) {
        var items = [{
            id: 'modx-user-country'
            ,name: 'country'
            ,fieldLabel: _('user_country')
            ,xtype: 'modx-combo-country'
            ,anchor: '100%'
            ,value: ''
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
                columnWidth: .3
                ,items: {
                    id: 'modx-user-state'
                    ,name: 'state'
                    ,fieldLabel: _('user_state')
                    ,xtype: 'textfield'
                    ,anchor: '100%'
                    ,maxLength: 100
                }
            },{
                columnWidth: .4
                ,items: {
                    id: 'modx-user-city'
                    ,name: 'city'
                    ,fieldLabel: _('city')
                    ,xtype: 'textfield'
                    ,anchor: '100%'
                    ,maxLength: 255
                }
            },{
                columnWidth: .3
                ,items: {
                    id: 'modx-user-zip'
                    ,name: 'zip'
                    ,fieldLabel: _('user_zip')
                    ,xtype: 'textfield'
                    ,anchor: '100%'
                    ,maxLength: 25
                }
            }]
        },{
            id: 'modx-user-address'
            ,name: 'address'
            ,fieldLabel: _('address')
            ,xtype: 'textarea'
            ,anchor: '100%'
            ,grow: true
        }];

        if (MODx.perm.change_password) {
            items.push({
                id: 'modx-user-newpassword'
                ,name: 'newpassword'
                ,xtype: 'hidden'
                ,value: false
            },{
                id: 'modx-user-fs-newpassword'
                ,title: _('password_new')
                ,xtype: 'fieldset'
                ,cls: 'x-fieldset-checkbox-toggle' // add a custom class for checkbox replacement
                ,checkboxToggle: true
                ,collapsed: (config.user ? true : false)
                ,forceLayout: true
                ,defaults: {
                    msgTarget: 'under'
                }
                ,listeners: {
                    'expand': {
                        fn: function (p) {
                            Ext.getCmp('modx-user-newpassword').setValue(true);
                            this.markDirty();
                        }, scope: this
                    }
                    ,'collapse': {
                        fn: function (p) {
                            Ext.getCmp('modx-user-newpassword').setValue(false);
                            this.markDirty();
                        }, scope: this
                    }
                }
                ,items: [{
                    xtype: 'textfield'
                    ,fieldLabel: _('password_old')
                    ,name: 'password_old'
                    ,inputType: 'password'
                    ,maxLength: 255
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('change_password_new')
                    ,name: 'password_new'
                    ,inputType: 'password'
                    ,maxLength: 255
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('change_password_confirm')
                    ,name: 'password_confirm'
                    ,id: 'modx-password-confirm'
                    ,inputType: 'password'
                    ,maxLength: 255
                    ,anchor: '100%'
                }]
            });
        }

        return items;
    }
});
Ext.reg('modx-panel-profile-update',MODx.panel.UpdateProfile);
