<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\Packages;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modX;
use MODX\Revolution\Transport\modTransportPackage;

/**
 * Gets a chunk.
 * @param integer $id The ID of the chunk.
 * @package MODX\Revolution\Processors\Workspace\Packages
 */
class Update extends Processor
{
    /** @var modTransportPackage $package */
    public $package;

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('packages');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['workspace'];
    }

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $this->setDefaultProperties([
            'signature' => '',
        ]);
        $this->modx->log(modX::LOG_LEVEL_INFO,
            $this->modx->lexicon('package_uninstall_info_find', ['signature' => $this->getProperty('signature')]));
        $signature = $this->getProperty('signature');
        if (empty($signature)) {
            $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');
            return $this->modx->lexicon('package_err_ns');
        }
        $this->package = $this->modx->getObject(modTransportPackage::class, $signature);
        if ($this->package === null) {
            $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');
            return $this->modx->lexicon('package_err_nfs', [
                'signature' => $signature,
            ]);
        }
        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $this->package->fromArray($this->getProperties());

        if (!$this->package->save()) {
            return $this->failure($this->modx->lexicon('package_err_save'));
        }

        $this->logManagerAction();
        return $this->success('', $this->package);
    }

    public function logManagerAction()
    {
        $this->modx->logManagerAction('package_update', modTransportPackage::class, $this->package->get('id'));
    }
}
