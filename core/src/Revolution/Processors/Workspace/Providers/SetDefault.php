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

use MODX\Revolution\modSystemSetting;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\Transport\modTransportProvider;

/**
 * Persist the active package provider as the default_provider system setting.
 *
 * @package MODX\Revolution\Processors\Workspace\Providers
 */
class SetDefault extends Processor
{
    public $permission = 'workspaces';

    /**
     * {@inheritDoc}
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['workspace', 'setting'];
    }

    /**
     * {@inheritDoc}
     * @return array|string
     */
    public function process()
    {
        $id = (int)$this->getProperty('id');
        if ($id < 1) {
            return $this->failure($this->modx->lexicon('provider_err_ns'));
        }

        /** @var modTransportProvider|null $provider */
        $provider = $this->modx->getObject(modTransportProvider::class, $id);
        if (!$provider) {
            return $this->failure($this->modx->lexicon('provider_err_nfs', ['id' => $id]));
        }

        /** @var modSystemSetting|null $setting */
        $setting = $this->modx->getObject(modSystemSetting::class, ['key' => 'default_provider']);
        if (!$setting) {
            $setting = $this->modx->newObject(modSystemSetting::class);
            $setting->fromArray([
                'key' => 'default_provider',
                'xtype' => 'modx-combo-provider',
                'namespace' => 'core',
                'area' => 'manager',
            ], '', true);
        }

        $setting->set('value', (string)$id);
        if ($setting->save() === false) {
            return $this->failure($this->modx->lexicon('setting_err_save'));
        }

        $this->modx->setOption('default_provider', (string)$id);
        $this->modx->reloadConfig();

        return $this->success('', [
            'id' => (int)$provider->get('id'),
            'name' => $provider->get('name'),
        ]);
    }
}
