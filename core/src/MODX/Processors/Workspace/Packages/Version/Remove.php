<?php

namespace MODX\Processors\Workspace\Packages\Version;

use MODX\MODX;
use MODX\Processors\modProcessor;
use MODX\Transport\modTransportPackage;

/**
 * Remove a package
 *
 * @param string $signature The signature of the package.
 * @param boolean $force (optional) If true, will remove the package even if
 * uninstall fails. Defaults to false.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
class Remove extends modProcessor
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
        if ($this->package->remove() == false) {
            $this->modx->log(MODX::LOG_LEVEL_ERROR, $this->modx->lexicon('package_err_remove'));

            return $this->modx->error->failure($this->modx->lexicon('package_err_remove', ['signature' => $this->package->getPrimaryKey()]));
        }

        $this->clearCache();
        $this->removeTransportZip();
        $this->removeTransportDirectory();
        $this->logManagerAction();

        $this->modx->log(MODX::LOG_LEVEL_WARN, $this->modx->lexicon('package_remove_info_success'));
        sleep(2);
        $this->modx->log(MODX::LOG_LEVEL_INFO, 'COMPLETED');

        return $this->success();
    }


    public function clearCache()
    {
        $this->modx->cacheManager->refresh([$this->modx->getOption('cache_packages_key', null, 'packages') => []]);
        $this->modx->cacheManager->refresh();
    }


    public function removeTransportZip()
    {
        $this->modx->log(MODX::LOG_LEVEL_INFO, $this->modx->lexicon('package_remove_info_tzip_start'));
        $f = $this->modx->getOption('core_path') . 'packages/' . $this->package->signature . '.transport.zip';
        if (!file_exists($f)) {
            $this->modx->log(MODX::LOG_LEVEL_ERROR, $this->modx->lexicon('package_remove_err_tzip_nf'));
        } elseif (!@unlink($f)) {
            $this->modx->log(MODX::LOG_LEVEL_ERROR, $this->modx->lexicon('package_remove_err_tzip'));
        } else {
            $this->modx->log(MODX::LOG_LEVEL_INFO, $this->modx->lexicon('package_remove_info_tzip'));
        }
    }


    public function removeTransportDirectory()
    {
        $this->modx->log(MODX::LOG_LEVEL_INFO, $this->modx->lexicon('package_remove_info_tdir_start'));
        $f = $this->modx->getOption('core_path') . 'packages/' . $this->package->signature . '/';
        if (!file_exists($f)) {
            $this->modx->log(MODX::LOG_LEVEL_ERROR, $this->modx->lexicon('package_remove_err_tdir_nf'));
        } elseif (!$this->modx->cacheManager->deleteTree($f, true, false, [])) {
            $this->modx->log(MODX::LOG_LEVEL_ERROR, $this->modx->lexicon('package_remove_err_tdir'));
        } else {
            $this->modx->log(MODX::LOG_LEVEL_INFO, $this->modx->lexicon('package_remove_info_tdir'));
        }
    }


    public function logManagerAction()
    {
        $this->modx->logManagerAction('package_remove', 'transport.modTransportPackage', $this->package->get('id'));
    }
}
