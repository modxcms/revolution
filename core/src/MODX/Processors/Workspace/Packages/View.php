<?php

namespace MODX\Processors\Workspace\Packages;

use MODX\Processors\modProcessor;
use MODX\Transport\modTransportPackage;

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
class View extends modProcessor
{
    /** @var modTransportPackage $package */
    public $package;


    public function checkPermissions()
    {
        return $this->modx->hasPermission('packages');
    }


    public function getLanguageTopics()
    {
        return ['workspace'];
    }


    public function initialize()
    {

        $signature = $this->getProperty('id');
        if (empty($signature)) return $this->modx->lexicon('package_err_ns');
        $this->package = $this->modx->getObject('transport.modTransportPackage', $signature);
        if (empty($this->package)) return $this->modx->lexicon('package_err_nf');

        return true;
    }


    public function process()
    {
        $collection = [];
        $packageArray = $this->package->toArray();

        $installed = $this->package->get('installed');
        $packageArray['installed'] = $installed == null ? $this->modx->lexicon('no') : $installed;

        $collection[] = $packageArray;

        return $this->success('', $collection);
    }
}