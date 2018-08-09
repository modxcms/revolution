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
 * Scans for local packages to add to the workspace.
 *
 * @param integer $workspace The workspace to add to. Defaults to 1.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
class modPackageScanLocalProcessor extends modProcessor {
    /** @var modWorkspace $workspace */
    public $workspace;
    public function checkPermissions() {
        return $this->modx->hasPermission('packages');
    }
    public function getLanguageTopics() {
        return array('workspace');
    }

    public function initialize() {
        $workspace = $this->getProperty('workspace',1);
        $this->workspace = $this->modx->getObject('modWorkspace',$workspace);
        if (empty($this->workspace)) return $this->modx->lexicon('workspace_err_nf');
        return true;
    }

    public function process() {
        $packages = $this->getPackages();

        /* foreach package that was found, add an object */
        foreach ($packages as $signature) {
            /** @var modTransportPackage $package */
            $package = $this->modx->getObject('transport.modTransportPackage',array(
                'signature' => $signature,
            ));
            if (!empty($package)) continue;
            $this->createPackage($signature);
        }

        return $this->success();
    }

    /**
     * Scan the packages/ directory
     * @return array
     */
    public function getPackages() {
        $packages = array();
        $corePackagesDirectory = $this->modx->getOption('core_path').'packages/';
        $corePackagesDirectoryObject = dir($corePackagesDirectory);
        while (false !== ($name = $corePackagesDirectoryObject->read())) {
            if (in_array($name,array('.','..','.svn','.git','_notes'))) continue;

            $packageFilename = $corePackagesDirectory.'/'.$name;
            /* dont add in unreadable files or directories */
            if (!is_readable($packageFilename) || is_dir($packageFilename)) continue;

            /* must be a .transport.zip file */
            if (strlen($name) < 14 || substr($name,strlen($name)-14,strlen($name)) != '.transport.zip') continue;
            $packageSignature = substr($name,0,strlen($name)-14);

            /* must have a name and version at least */
            $p = explode('-',$packageSignature);
            if (count($p) < 2) continue;

            $packages[] = $packageSignature;
        }
        return $packages;
    }

    /**
     * Attempt to create and add the package to the DB
     * @param string $signature
     * @return boolean
     */
    public function createPackage($signature) {
        /** @var modTransportPackage $package */
        $package = $this->modx->newObject('transport.modTransportPackage');
        $package->set('signature', $signature);
        $package->set('state', 1);
        $package->set('created',strftime('%Y-%m-%d %H:%M:%S'));
        $package->set('workspace', $this->workspace->get('id'));

        /* set package version data */
        $sig = explode('-',$signature);
        if (is_array($sig)) {
            $package->set('package_name',$sig[0]);
            if (!empty($sig[1])) {
                $v = explode('.',$sig[1]);
                if (isset($v[0])) $package->set('version_major',$v[0]);
                if (isset($v[1])) $package->set('version_minor',$v[1]);
                if (isset($v[2])) $package->set('version_patch',$v[2]);
            }
            if (!empty($sig[2])) {
                $r = preg_split('/([0-9]+)/',$sig[2],-1,PREG_SPLIT_DELIM_CAPTURE);
                if (is_array($r) && !empty($r)) {
                    $package->set('release',$r[0]);
                    $package->set('release_index',(isset($r[1]) ? $r[1] : '0'));
                } else {
                    $package->set('release',$sig[2]);
                }
            }
        }

        return $package->save();
    }
}
return 'modPackageScanLocalProcessor';

