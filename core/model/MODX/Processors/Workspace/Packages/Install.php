<?php

namespace MODX\Processors\Workspace\Packages;

use MODX\MODX;
use MODX\Processors\modProcessor;
use MODX\Transport\modTransportPackage;
use xPDO\Transport\xPDOTransport;

/**
 * Install a package
 *
 * @param string $signature The signature of the package.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
class Install extends modProcessor
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
        $this->modx->log(MODX::LOG_LEVEL_INFO, $this->modx->lexicon('package_install_info_start', ['signature' => $this->getProperty('signature')]));
        $signature = $this->getProperty('signature');
        if (empty($signature)) {
            $this->modx->log(MODX::LOG_LEVEL_INFO, 'COMPLETED');

            return $this->modx->lexicon('package_err_ns');
        }
        $this->package = $this->modx->getObject('transport.modTransportPackage', $signature);
        if (empty($this->package)) {
            $this->modx->log(MODX::LOG_LEVEL_INFO, 'COMPLETED');

            return $this->modx->lexicon('package_err_nf');
        }

        return true;
    }


    public function process()
    {
        $this->modx->log(MODX::LOG_LEVEL_INFO, $this->modx->lexicon('package_install_info_found'));

        $installed = $this->package->install($this->getProperties());

        $this->clearCache();

        if (!$installed) {
            $msg = $this->modx->lexicon('package_err_install', ['signature' => $this->package->get('signature')]);
            $this->modx->log(MODX::LOG_LEVEL_ERROR, $msg);
            $this->modx->log(MODX::LOG_LEVEL_INFO, 'COMPLETED');

            return $this->failure($msg);
        } else {
            $msg = $this->modx->lexicon('package_install_info_success', ['signature' => $this->package->get('signature')]);
            $this->modx->log(MODX::LOG_LEVEL_WARN, $msg);
            $this->modx->log(MODX::LOG_LEVEL_INFO, 'COMPLETED');

            $this->modx->invokeEvent('OnPackageInstall', [
                'package' => $this->package,
                'action' => $this->package->previousVersionInstalled()
                    ? xPDOTransport::ACTION_UPGRADE
                    : xPDOTransport::ACTION_INSTALL,
            ]);

            return $this->success($msg);
        }
    }


    public function clearCache()
    {
        $this->modx->cacheManager->refresh([$this->modx->getOption('cache_packages_key', null, 'packages') => []]);
        $this->modx->cacheManager->refresh();
    }
}
