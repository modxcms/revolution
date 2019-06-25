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
            process: 'security/profile/update'
            ,reload: true
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,redirect: false
            ,method: 'remote'
            // ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
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
                xtype: 'textfield'
                ,fieldLabel: _('user_full_name')
                ,name: 'fullname'
                ,maxLength: 255
                ,allowBlank: false
                ,anchor: '100%'
            }, {
                xtype: 'textfield'
                ,fieldLabel: _('email')
                ,name: 'email'
                ,vtype: 'email'
                ,allowBlank: false
                ,anchor: '100%'
            },{
                xtype: 'datefield'
                ,fieldLabel: _('user_dob')
                ,name: 'dob'
                ,anchor: '100%'
            }, {
                id: 'modx-user-gender'
                ,name: 'gender'
                ,hiddenName: 'gender'
                ,fieldLabel: _('user_gender')
                ,xtype: 'modx-combo-gender'
                ,anchor: '100%'
            }, {
                fieldLabel: _('user_photo')
                ,name: 'photo'
                ,xtype: 'modx-combo-browser'
                ,hideFiles: true
                ,source: MODx.config['photo_profile_source'] || MODx.config.default_media_source
                ,hideSourceCombo: true
                ,anchor: '100%'
            }, {
                xtype: 'textfield'
                ,fieldLabel: _('user_phone')
                ,name: 'phone'
                ,anchor: '100%'
            }, {
                xtype: 'textfield'
                ,fieldLabel: _('user_mobile')
                ,name: 'mobilephone'
                ,anchor: '100%'
            }, {
                xtype: 'textfield'
                ,fieldLabel: _('user_fax')
                ,name: 'fax'
                ,anchor: '100%'
            }, {
                id: 'modx-user-website'
                ,name: 'website'
                ,fieldLabel: _('user_website')
                ,xtype: 'textfield'
                ,anchor: '100%'
                ,maxLength: 255
            }]
        }, {
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
    },
    getItemsRight(config) {
        var items = [{
            id: 'modx-user-address'
            ,name: 'address'
            ,fieldLabel: _('address')
            ,xtype: 'textarea'
            ,anchor: '100%'
            ,grow: true
        }, {
            xtype: 'textfield'
            ,fieldLabel: _('user_zip')
            ,name: 'zip'
            ,maxLength: 20
            ,anchor: '100%'
        }, {
            id: 'modx-user-city'
            ,name: 'city'
            ,fieldLabel: _('city')
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,maxLength: 255
        }, {
            xtype: 'textfield'
            ,fieldLabel: _('user_state')
            ,name: 'state'
            ,maxLength: 50
            ,anchor: '100%'
        }, {
            id: 'modx-user-country'
            ,fieldLabel: _('user_country')
            ,xtype: 'modx-combo-country'
            ,name: 'country'
            ,value: ''
            ,anchor: '100%'
        }];

        if (MODx.perm.change_password) {
            items.push({
                id: 'modx-user-newpassword'
                ,name: 'newpassword'
                ,xtype: 'hidden'
                ,value: false
            }, {
                id: 'modx-user-fs-newpassword'
                ,title: _('password_new')
                ,xtype: 'fieldset'
                ,cls: 'x-fieldset-checkbox-toggle' // add a custom class for checkbox replacement
                ,checkboxToggle: true
                ,collapsed: (config.user ? true : false)
                ,forceLayout: true
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
                }, {
                    xtype: 'textfield'
                    ,fieldLabel: _('change_password_new')
                    ,name: 'password_new'
                    ,inputType: 'password'
                    ,maxLength: 255
                    ,anchor: '100%'
                }, {
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
