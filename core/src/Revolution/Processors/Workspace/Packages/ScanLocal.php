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
use MODX\Revolution\modWorkspace;
use MODX\Revolution\Transport\modTransportPackage;

/**
 * Scans for local packages to add to the workspace.
 * @param integer $workspace The workspace to add to. Defaults to 1.
 * @package MODX\Revolution\Processors\Workspace\Packages
 */
class ScanLocal extends Processor
{
    /** @var modWorkspace $workspace */
    public $workspace;

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
        $workspace = $this->getProperty('workspace', 1);
        $this->workspace = $this->modx->getObject(modWorkspace::class, $workspace);
        if ($this->workspace === null) {
            return $this->modx->lexicon('workspace_err_nf');
        }
        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $packages = $this->getPackages();

        /* foreach package that was found, add an object */
        foreach ($packages as $signature) {
            /** @var modTransportPackage $package */
            $package = $this->modx->getObject(modTransportPackage::class, [
                'signature' => $signature,
            ]);
            if ($package !== null) {
                continue;
            }
            $this->createPackage($signature);
        }

        return $this->success();
    }

    /**
     * Scan the packages/ directory
     * @return array
     */
    public function getPackages()
    {
        $packages = [];
        $corePackagesDirectory = $this->modx->getOption('core_path') . 'packages/';
        $corePackagesDirectoryObject = dir($corePackagesDirectory);
        while (false !== ($name = $corePackagesDirectoryObject->read())) {
            if (in_array($name, ['.', '..', '.svn', '.git', '_notes'])) {
                continue;
            }

            $packageFilename = $corePackagesDirectory . '/' . $name;
            /* dont add in unreadable files or directories */
            if (!is_readable($packageFilename) || is_dir($packageFilename)) {
                continue;
            }

            /* must be a .transport.zip file */
            if (strlen($name) < 14 || substr($name, strlen($name) - 14, strlen($name)) !== '.transport.zip') {
                continue;
            }
            $packageSignature = substr($name, 0, -14);

            /* must have a name and version at least */
            $p = explode('-', $packageSignature);
            if (count($p) < 2) {
                continue;
            }

            $packages[] = $packageSignature;
        }
        return $packages;
    }

    /**
     * Attempt to create and add the package to the DB
     * @param string $signature
     * @return boolean
     */
    public function createPackage($signature)
    {
        /** @var modTransportPackage $package */
        $package = $this->modx->newObject(modTransportPackage::class);
        $package->set('signature', $signature);
        $package->set('state', 1);
        $package->set('created', date('Y-m-d H:i:s'));
        $package->set('workspace', $this->workspace->get('id'));

        /* set package version data */
        $sig = explode('-', $signature);
        if (is_array($sig)) {
            $package->set('package_name', $sig[0]);
            if (!empty($sig[1])) {
                $v = explode('.', $sig[1]);
                if (isset($v[0])) {
                    $package->set('version_major', $v[0]);
                }
                if (isset($v[1])) {
                    $package->set('version_minor', $v[1]);
                }
                if (isset($v[2])) {
                    $package->set('version_patch', $v[2]);
                }
            }
            if (!empty($sig[2])) {
                $r = preg_split('/(\d+)/', $sig[2], -1, PREG_SPLIT_DELIM_CAPTURE);
                if (is_array($r) && !empty($r)) {
                    $package->set('release', $r[0]);
                    $package->set('release_index', (isset($r[1]) ? $r[1] : '0'));
                } else {
                    $package->set('release', $sig[2]);
                }
            }
        }

        return $package->save();
    }
}
