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
use MODX\Revolution\Transport\modTransportPackage;
use Parsedown;
use xPDO\Transport\xPDOTransport;

/**
 * Gets an attribute of a package
 * @param string $signature The signature of the package
 * @param string $attr The attribute to select
 * @package MODX\Revolution\Processors\Workspace\Packages
 */
class GetAttribute extends Processor
{
    /** @var modTransportPackage $package */
    public $package;

    /** @var xPDOTransport $transport */
    public $transport;

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
        $signature = $this->getProperty('signature');
        if (empty($signature)) {
            return $this->modx->lexicon('package_err_ns');
        }
        $this->package = $this->modx->getObject(modTransportPackage::class, $signature);
        if ($this->package === null) {
            return $this->modx->lexicon('package_err_nf');
        }

        $this->transport = $this->package->getTransport();
        if (!$this->transport) {
            return $this->modx->lexicon('package_err_nf');
        }
        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $attributes = [];
        $attributesToGet = explode(',', $this->getProperty('attributes', ''));
        $parseDown = new Parsedown();
        $parseDown->setSafeMode(true);
        foreach ($attributesToGet as $attribute) {
            $data = $this->transport->getAttribute($attribute);
            $attributes[$attribute] = in_array($attribute,
                ['changelog', 'license', 'readme']) ? $parseDown->text(htmlentities($data)) : $data;

            /* if setup options, include setup file */
            if ($attribute === 'setup-options') {
                @ob_start();
                $options = $this->package->toArray();
                $options[xPDOTransport::PACKAGE_ACTION] = $this->package->previousVersionInstalled() ? xPDOTransport::ACTION_UPGRADE : xPDOTransport::ACTION_INSTALL;
                $attributeFile = $this->modx->getOption('core_path') . 'packages/' . $this->package->signature . '/' . $attribute . '.php';
                if ($attribute !== '' && file_exists($attributeFile)) {
                    $modx =& $this->modx;
                    $attributes['setup-options'] = include $attributeFile;
                }
                @ob_end_clean();
            }
        }

        return $this->success('', $attributes);
    }
}
