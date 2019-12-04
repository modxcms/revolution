<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\PackageNamespace;

use MODX\Revolution\modNamespace;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modX;

/**
 * Removes namespaces.
 * @param string $name The name of the namespace.
 * @package MODX\Revolution\Processors\Workspace\PackageNamespace
 */
class RemoveMultiple extends Processor
{
    /** @var modNamespace $namespace */
    public $namespace;

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('namespaces');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['workspace', 'namespace', 'lexicon'];
    }

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $namespaces = $this->getProperty('namespaces');
        if (empty($namespaces)) {
            return $this->modx->lexicon('namespace_err_ns');
        }
        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $namespaceIds = explode(',', $this->getProperty('namespaces'));

        if (!empty($namespaceIds)) {
            foreach ($namespaceIds as $namespaceId) {
                /** @var modNamespace $namespace */
                $namespace = $this->modx->getObject(modNamespace::class, $namespaceId);
                if ($namespace === null) {
                    continue;
                }

                if ($namespace->get('name') === 'core') {
                    continue;
                }

                if ($namespace->remove() === false) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('namespace_err_remove'));
                    continue;
                }

                /* log manager action */
                $this->modx->logManagerAction('namespace_remove', modNamespace::class, $namespace->get('name'));
            }
        }
        return $this->success();
    }

    /**
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction('namespace_remove', modNamespace::class, $this->namespace->get('name'));
    }
}
