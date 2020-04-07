<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\Packages\Dependency;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\Transport\modTransportPackage;
use MODX\Revolution\Transport\modTransportProvider;

/**
 * Download a package by resolving dependent package constraints
 * @package MODX\Revolution\Processors\Workspace\Packages\Dependency
 */
class Download extends Processor
{
    /** @var modTransportProvider $provider */
    public $provider;

    /** @var string $location The actual file location of the download */
    public $location;

    /** @var string $signature The signature of the transport package */
    public $signature;

    /** @var modTransportPackage $package */
    public $package;

    /**
     * Ensure user has access to do this
     * {@inheritDoc}
     * @return boolean
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('packages');
    }

    /**
     * The language topics to load
     * {@inheritDoc}
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['workspace'];
    }

    /**
     * Ensure the info was properly passed and initialize the processor
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        @set_time_limit(0);
        $signature = $this->getProperty('signature', '');
        $name = $this->getProperty('name', '');
        $constraints = $this->getProperty('constraints', '');
        if (empty($signature)) {
            return $this->modx->lexicon('package_download_err_ns');
        }
        if (empty($name)) {
            return $this->modx->lexicon('package_download_err_ns');
        }
        if (empty($constraints)) {
            return $this->modx->lexicon('package_download_err_ns');
        }

        /** @var modTransportPackage $package */
        $package = $this->modx->getObject(modTransportPackage::class, $signature);
        $resolution = $package->findResolution($name, $constraints);

        $resolution = $resolution[0];

        $this->signature = $resolution['signature'];
        $this->location = $resolution['location'];

        return parent::initialize();
    }

    /**
     * Run the processor, downloading and transferring the package, and creating the metadata in the database
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        if (!$this->loadProvider()) {
            return $this->failure($this->modx->lexicon('provider_err_nf'));
        }

        $this->package = $this->provider->transfer($this->signature, null, ['location' => $this->location]);
        if (!$this->package) {
            $this->failure($this->modx->lexicon('package_download_err_create', ['signature' => $this->signature]));
        }

        return $this->success('', $this->package);
    }

    /**
     * Load the provider for the package
     * @return boolean
     */
    public function loadProvider()
    {
        $provider = $this->getProperty('provider');
        if (empty($provider)) {
            $c = $this->modx->newQuery(modTransportProvider::class);
            $c->where([
                'name:=' => 'modxcms.com',
                'OR:name:=' => 'modx.com',
            ]);
            $this->provider = $this->modx->getObject(modTransportProvider::class, $c);
            if ($this->provider !== null) {
                $this->setProperty('provider', $this->provider->get('id'));
            }
        } else {
            $this->provider = $this->modx->getObject(modTransportProvider::class, $provider);
        }
        return $this->provider !== null;
    }

}
