<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\Providers;

use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\Transport\modTransportProvider;

/**
 * Update a provider
 * @param integer $id The ID of the provider
 * @param string $name The new name for the provider
 * @param string $description A short description
 * @param string $service_url The URL which the provider is hosted under
 * @package MODX\Revolution\Processors\Workspace\Providers
 */
class Update extends UpdateProcessor
{
    public $classKey = modTransportProvider::class;
    public $languageTopics = ['workspace'];
    public $permission = 'providers';
    public $objectType = 'provider';

    /** @var modTransportProvider $object */
    public $object;

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $name = $this->getProperty('name', '');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('provider_err_ns_name'));
        }

        $serviceUrl = $this->getProperty('service_url', '');
        if (empty($serviceUrl)) {
            $this->addFieldError('service_url', $this->modx->lexicon('provider_err_ns_url'));
        }

        $verified = $this->object->verify();
        if ($verified !== true) {
            $this->addFieldError('service_url', $this->modx->lexicon('provider_err_not_verified'));
        }

        return parent::beforeSave();
    }
}
