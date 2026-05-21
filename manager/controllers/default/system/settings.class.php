<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

//use League\OAuth2\Client\Provider\Google;
//use Hayageek\OAuth2\Client\Provider\Yahoo;
//use Stevenmaguire\OAuth2\Client\Provider\Microsoft;
use Greew\OAuth2\Client\Provider\Azure;
use GuzzleHttp\Exception\GuzzleException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use MODX\Revolution\modManagerController;

/**
 * Loads the system settings page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SystemSettingsManagerController extends modManagerController {
    public $onSiteSettingsRender = '';
    public $siteSettingsMessage = '';

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('settings');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $this->addHtml('<script>
        // <[!CDATA[
        Ext.onReady(function() {
            MODx.add("modx-page-system-settings");
        });
        MODx.onSiteSettingsRender = "'.$this->onSiteSettingsRender.'";
        MODx.siteSettingsMessage = "'. $this->siteSettingsMessage.'";
        // ]]>
        </script>');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.settings.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/system/modx.grid.system.event.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/system/modx.panel.system.settings.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/system/modx.panel.oauth2email.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/system/settings.js');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = []) {
        $this->siteSettingsMessage = $this->processOauth2Token();

        $onSiteSettingsRender = $this->modx->invokeEvent('OnSiteSettingsRender');
        if (is_array($onSiteSettingsRender)) {
            $this->onSiteSettingsRender = implode("\"\n+ \"",$onSiteSettingsRender);
        }
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('system_settings');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return '';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return ['setting','events','mail'];
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Settings';
    }

    private function processOauth2Token()
    {
        $providerName = strtolower($this->modx->getOption('mail_smtp_auth_type'));
        $clientId = $this->modx->getOption('mail_smtp_oauth2_' . $providerName . '_client_id');
        $clientSecret = $this->modx->getOption('mail_smtp_oauth2_' . $providerName . '_client_secret');
        $tenantId = $this->modx->getOption('mail_smtp_oauth2_' . $providerName . '_tenant_id');

        $params = [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'accessType' => 'offline'
        ];

        $options = [];
        $provider = null;

        switch ($providerName) {
//            case 'google':
//                $provider = new Google($params);
//                $options = [
//                    'scope' => [
//                        'https://mail.google.com/'
//                    ]
//                ];
//                break;
//            case 'yahoo':
//                $provider = new Yahoo($params);
//                break;
//            case 'microsoft':
//                $provider = new Microsoft($params);
//                $options = [
//                    'scope' => [
//                        'wl.imap',
//                        'wl.offline_access'
//                    ]
//                ];
//                break;
            case 'azure':
                $params['tenantId'] = $tenantId;

                $provider = new Azure($params);
                $options = [
                    'scope' => [
                        'https://outlook.office.com/SMTP.Send',
                        'offline_access'
                    ]
                ];
                break;
        }

        if (null === $provider) {
            return $this->modx->lexicon('mail_oauth2.invalid_provider');
        }

        if (!empty($_GET['code'])) {
            if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
                // Check given state against previously stored one to mitigate CSRF attack
                unset($_SESSION['oauth2state']);
                return $this->modx->lexicon('mail_oauth2.invalid_state');
            } else {
                // Try to get an access token (using the authorization code grant)
                try {
                    $token = $provider->getAccessToken('authorization_code', [
                        'code' => $_GET['code']
                    ]);
                } catch (GuzzleException $e) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not get the email OAuth2 access token: ' . $e->getMessage());
                    return $this->modx->lexicon('mail_oauth2.invalid_connection');
                } catch (IdentityProviderException $e) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not get the email OAuth2 access token: ' . $e->getMessage());
                    return $this->modx->lexicon('mail_oauth2.invalid_authorization');
                }
                $setting = $this->modx->getObject('modSystemSetting', [
                    'key' => 'mail_smtp_oauth2_azure_refresh_token'
                ]);
                if (!$setting) {
                    $setting = $this->modx->newObject('modSystemSetting');
                    $setting->fromArray([
                        'key' => 'mail_smtp_oauth2_azure_refresh_token',
                        'value' => '',
                        'xtype' => 'textfield',
                        'namespace' => 'core',
                        'area' => 'mail'
                    ]);
                }
                $setting->set('value', $token->getRefreshToken());
                $setting->save();

                return '';
            }
        }
    }
}
