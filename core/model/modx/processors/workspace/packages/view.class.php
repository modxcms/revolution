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
 * View a package
 *
 * @deprecated Is this even used anymore?
 *
 * @param string $id The signature of the package.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
class modPackageViewProcessor extends modProcessor {
    /** @var modTransportPackage $package */
    public $package;

    public function checkPermissions() {
        return $this->modx->hasPermission('packages');
    }
    public function getLanguageTopics() {
        return array('workspace');
    }
    public function initialize() {

        $signature = $this->getProperty('id');
        if (empty($signature)) return $this->modx->lexicon('package_err_ns');
        $this->package = $this->modx->getObject('transport.modTransportPackage',$signature);
        if (empty($this->package)) return $this->modx->lexicon('package_err_nf');
        return true;
    }

    public function process() {
        $this->modx->deprecated('2.1.5', '', 'modPackageViewProcessor support');

        $collection= array();
        $packageArray = $this->package->toArray();

        $installed = $this->package->get('installed');
        $packageArray['installed'] = $installed == null ? $this->modx->lexicon('no') : $installed;

        $collection[]= $packageArray;

        return $this->success('', $collection);
    }
}
return 'modPackageViewProcessor';
