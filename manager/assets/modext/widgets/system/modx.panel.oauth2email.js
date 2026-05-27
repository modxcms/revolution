/**
 * @class MODx.panel.OAuth2Email
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-email
 */
MODx.panel.OAuth2Email = function (config) {
    config = config || {};
    config.auth_type = MODx.config.mail_smtp_auth_type.toLowerCase();
    config.client_id = MODx.config['mail_smtp_oauth2_' + config.auth_type + '_client_id'];
    config.client_secret = MODx.config['mail_smtp_oauth2_' + config.auth_type + '_client_secret'];
    config.tenant_id = MODx.config['mail_smtp_oauth2_' + config.auth_type + '_tenant_id'];
    config.redirect_url = MODx.config.http_host_remote + MODx.config.manager_url + '?a=system/settings&tab=2';
    config.refresh_token = MODx.config['mail_smtp_oauth2_' + config.auth_type + '_refresh_token'];

    if (MODx.siteSettingsMessage !== '') {
        MODx.msg.alert(_('error'), MODx.siteSettingsMessage);
    }

    Ext.applyIf(config, {
        id: 'modx-panel-oauth2-email',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'System/Email/Oauth2/GetToken'
        },
        bodyStyle: {},
        cls: 'container',
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [{
            xtype: 'panel',
            id: 'modx-email-panel',
            labelAlign: 'top',
            style: 'padding-top: 0',
            items: [{
                xtype: 'fieldset',
                cls: 'main-wrapper',
                style: 'margin: 0 0 15px',
                bodyStyle: 'padding: 0',
                items: [{
                    xtype: 'textfield',
                    id: 'modx-email-client-id',
                    anchor: '100%',
                    name: 'clien_id',
                    fieldLabel: _('setting_mail_smtp_oauth2_' + config.auth_type + '_client_id'),
                    labelStyle: 'margin-top: 0',
                    value: config.client_id
                }, {
                    xtype: 'textfield',
                    id: 'modx-email-client-secret',
                    anchor: '100%',
                    name: 'client_secret',
                    fieldLabel: _('setting_mail_smtp_oauth2_' + config.auth_type + '_client_secret'),
                    value: config.client_secret
                }, {
                    xtype: 'textfield',
                    id: 'modx-email-tenant-id',
                    anchor: '100%',
                    name: 'tenant_id',
                    fieldLabel: _('setting_mail_smtp_oauth2_' + config.auth_type + '_tenant_id'),
                    value: config.tenant_id,
                    hidden: config.auth_type !== 'azure'
                }, {
                    xtype: 'textfield',
                    anchor: '100%',
                    readOnly: true,
                    fieldLabel: _('mail_oauth2.redirect_url'),
                    value: config.redirect_url
                }, {
                    xtype: 'textfield',
                    anchor: '100%',
                    readOnly: true,
                    fieldLabel: _('setting_mail_smtp_oauth2_' + config.auth_type + '_refresh_token'),
                    value: config.refresh_token
                }]
            }, {
                xtype: 'toolbar',
                style: {
                    backgroundColor: 'transparent',
                    borderColor: 'transparent',
                    margin: '10px 0 0 -3px'
                },
                items: [{
                    xtype: 'button',
                    id: 'modx-email-oauth-button',
                    text: _('mail_oauth2.get_token'),
                    cls: 'primary-button',
                    listeners: {
                        click: {
                            fn: this.getToken,
                            scope: this
                        }
                    }
                }, {
                    xtype: 'button',
                    id: 'modx-email-update-settings-button',
                    text: _('mail_oauth2.update_settings'),
                    listeners: {
                        click: {
                            fn: this.updateSettings,
                            scope: this
                        }
                    }
                }]
            }]
        }]
    });
    MODx.panel.OAuth2Email.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.OAuth2Email, MODx.Panel, {
    getToken: function () {
        var panel = Ext.getCmp('modx-email-panel');
        panel.el.mask(_('working'));
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'System/Email/Oauth2/GetToken'
            },
            listeners: {
                success: {
                    fn: function (r) {
                        panel.el.unmask();
                        url = r.object.auth_url;
                        if (url) {
                            location.href = url;
                        } else {
                            MODx.msg.alert(_('error'), _('mail_oauth2.url_missing'));
                        }
                    }
                },
                failure: {
                    fn: function (r) {
                        panel.el.unmask();
                        MODx.msg.alert(_('error'), r.message);
                    }
                }
            }
        });
    },
    updateSettings: function () {
        var panel = Ext.getCmp('modx-email-panel'),
            clientId = Ext.getCmp('modx-email-client-id'),
            clientSecret = Ext.getCmp('modx-email-client-secret'),
            tenantId = Ext.getCmp('modx-email-tenant-id');
        panel.el.mask(_('working'));
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'System/Email/Oauth2/UpdateSettings',
                clientId: clientId.getValue(),
                clientSecret: clientSecret.getValue(),
                tenantId: tenantId.getValue()
            },
            listeners: {
                success: {
                    fn: function (r) {
                        panel.el.unmask();
                        MODx.msg.status({
                            title: _('success'),
                            message: _('mail_oauth2.update_successful'),
                            dontHide: r.message !== ''
                        });
                    }
                },
                failure: {
                    fn: function (r) {
                        panel.el.unmask();
                        MODx.msg.alert(_('error'), r.message);
                    }
                }
            }
        });
    }
});
Ext.reg('modx-panel-oauth2-email', MODx.panel.OAuth2Email);
