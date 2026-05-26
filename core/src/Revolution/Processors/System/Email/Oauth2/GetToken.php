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

//use League\OAuth2\Client\Provider\Google;
//use Hayageek\OAuth2\Client\Provider\Yahoo;
//use Stevenmaguire\OAuth2\Client\Provider\Microsoft;
use Greew\OAuth2\Client\Provider\Azure;
use GuzzleHttp\Exception\GuzzleException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use MODX\Revolution\Processors\Processor;
use xPDO\xPDO;

/**
 * Get the phpMailer OAuth2 token
 * @package MODX\Revolution\Processors\System\Email\Oauth2
 */
class GetToken extends Processor
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
        $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('mail_oauth2.get_token'));

        $providerName = strtolower($this->modx->getOption('mail_smtp_auth_type'));
        $clientId = $this->modx->getOption('mail_smtp_oauth2_' . $providerName . '_client_id');
        $clientSecret = $this->modx->getOption('mail_smtp_oauth2_' . $providerName . '_client_secret');
        $tenantId = $this->modx->getOption('mail_smtp_oauth2_' . $providerName . '_tenant_id');

        $redirectUri = MODX_URL_SCHEME . MODX_HTTP_HOST . MODX_MANAGER_URL . '?a=system/settings&tab=2';

        $params = [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri,
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
            return $this->failure($this->modx->lexicon('mail_oauth2.invalid_provider'));
        }

        $this->runAfterEvents();

        // Get the authorization url
        $authUrl = $provider->getAuthorizationUrl($options);
        $_SESSION['oauth2state'] = $provider->getState();
        return $this->success('', ['auth_url' => $authUrl]);
    }

    public function runAfterEvents()
    {
        $this->modx->logManagerAction('get_oauth2_token', '', $this->modx->context->key);
    }
}
