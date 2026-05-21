<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Email\Oauth2;

use MODX\Revolution\modSystemSetting;
use MODX\Revolution\Processors\Processor;

/**
 * Update the phpMailer OAuth2 settings
 * @package MODX\Revolution\Processors\System\Email\Oauth2
 */
class UpdateSettings extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('settings');
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $providerName = strtolower($this->modx->getOption('mail_smtp_auth_type'));
        $clientIdName = 'mail_smtp_oauth2_' . $providerName . '_client_id';
        $clientSecretName = 'mail_smtp_oauth2_' . $providerName . '_client_secret';
        $tenantIdName = 'mail_smtp_oauth2_' . $providerName . '_tenant_id';

        $this->updateMailSystemsetting($clientIdName, $this->getProperty('clientId'));
        $this->updateMailSystemsetting($clientSecretName, $this->getProperty('clientSecret'));
        switch ($providerName) {
            case 'azure':
                $this->updateMailSystemsetting($tenantIdName, $this->getProperty('tenantId'));
                break;
        }

        $this->modx->reloadConfig();

        return $this->success();
    }

    private function updateMailSystemsetting($key, $value)
    {
        $setting = $this->modx->getObject(modSystemSetting::class, [
            'key' => $key
        ]);
        if (!$setting) {
            $setting = $this->modx->newObject(modSystemSetting::class);
            $setting->fromArray([
                'key' => $key,
                'value' => '',
                'xtype' => 'textfield',
                'namespace' => 'core',
                'area' => 'mail'
            ]);
        }
        $setting->set('value', $value);
        $setting->save();
    }
}
