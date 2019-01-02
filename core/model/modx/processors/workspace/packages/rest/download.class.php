<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Download a package by passing in its location
 *
 * @var modX $this->modx
 *
 * @package modx
 * @subpackage processors.workspace.packages.rest
 */
class modPackageDownloadProcessor extends modProcessor {
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
     *
     * {@inheritDoc}
     * @return boolean
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('packages');
    }

    /**
     * The language topics to load
     *
     * {@inheritDoc}
     * @return array
     */
    public function getLanguageTopics() {
        return array('workspace');
    }

    /**
     * Ensure the info was properly passed and initialize the processor
     *
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        @set_time_limit(0);
        $info = $this->getProperty('info','');
        if (empty($info)) return $this->modx->lexicon('package_download_err_ns');
        if (!$this->parseInfo($info)) {
            return $this->modx->lexicon('invalid_data');
        }
        return parent::initialize();
    }

    /**
     * Run the processor, downloading and transferring the package, and creating the metadata in the database
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        if (!$this->loadProvider()) {
            return $this->failure($this->modx->lexicon('provider_err_nf'));
        }

        $this->package = $this->provider->transfer($this->signature, null, array('location' => $this->location));
        if (!$this->package) {
            return $this->failure($this->modx->lexicon('package_download_err_create', array('signature' => $this->signature)));
        }

        return $this->success('', $this->package);
    }

    /**
     * Load the provider for the package
     * @return boolean
     */
    public function loadProvider() {
        $provider = $this->getProperty('provider');
        if (empty($provider)) {
            $c = $this->modx->newQuery('transport.modTransportProvider');
            $c->where(array(
                'name:=' => 'modxcms.com',
                'OR:name:=' => 'modx.com',
            ));
            $this->provider = $this->modx->getObject('transport.modTransportProvider',$c);
            if (!empty($this->provider)) {
                $this->setProperty('provider',$this->provider->get('id'));
            }
        } else {
            $this->provider = $this->modx->getObject('transport.modTransportProvider',$provider);
        }
        return !empty($this->provider);
    }

    /**
     * Parse the information sent to the processor
     * @param string $info
     * @return boolean
     */
    public function parseInfo($info) {
        $parsed = false;
        $parsedInfo = explode('::',$info);
        if (!empty($parsedInfo) && !empty($parsedInfo[1])) {
            $this->location = $parsedInfo[0];
            $this->signature = $parsedInfo[1];
            $parsed = true;
        }
        return $parsed;
    }
}
return 'modPackageDownloadProcessor';
