<?php

namespace MODX\Processors\Workspace\Packages;

use MODX\MODX;
use MODX\Processors\modProcessor;
use MODX\Transport\modTransportPackage;
use xPDO\Transport\xPDOTransport;

/**
 * Uninstall a package
 *
 * @param string $signature The signature of the package.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
class Uninstall extends modProcessor
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
        $this->setDefaultProperties([
            'signature' => '',
        ]);
        $this->modx->log(MODX::LOG_LEVEL_INFO, $this->modx->lexicon('package_uninstall_info_find', ['signature' => $this->getProperty('signature')]));
        $signature = $this->getProperty('signature');
        if (empty($signature)) {
            $this->modx->log(MODX::LOG_LEVEL_INFO, 'COMPLETED');

            return $this->modx->lexicon('package_err_ns');
        }
        $this->package = $this->modx->getObject('transport.modTransportPackage', $signature);
        if (empty($this->package)) {
            $this->modx->log(MODX::LOG_LEVEL_INFO, 'COMPLETED');

            return $this->modx->lexicon('package_err_nfs', [
                'signature' => $signature,
            ]);
        }

        return true;
    }


    public function process()
    {
        $this->package->getTransport();
        $this->modx->log(MODX::LOG_LEVEL_INFO, $this->modx->lexicon('package_uninstall_info_prep'));

        /* uninstall package */
        $options = [
            xPDOTransport::PREEXISTING_MODE => $this->getProperty('preexisting_mode'),
        ];

        if ($this->package->uninstall($options) == false) {
            return $this->failure(sprintf($this->modx->lexicon('package_err_uninstall'), $this->package->getPrimaryKey()));
        }

        $this->modx->log(MODX::LOG_LEVEL_WARN, $this->modx->lexicon('package_uninstall_info_success', ['signature' => $this->package->get('signature')]));
        sleep(2);
        $this->modx->log(MODX::LOG_LEVEL_INFO, 'COMPLETED');

        $this->logManagerAction();

        $this->modx->invokeEvent('OnPackageUninstall', [
            'package' => $this->package,
        ]);

        $this->clearCache();

        return $this->success();
    }


    public function clearCache()
    {
        $this->modx->cacheManager->refresh([$this->modx->getOption('cache_packages_key', null, 'packages') => []]);
        $this->modx->cacheManager->refresh();

    }


    public function logManagerAction()
    {
        $this->modx->logManagerAction('package_uninstall', 'transport.modTransportPackage', $this->package->get('id'));
    }
}
