<?php
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
class modPackageRemoveProcessor extends modProcessor {
    /** @var modTransportPackage $package */
    public $package;

    public function checkPermissions() {
        return $this->modx->hasPermission('packages');
    }
    public function getLanguageTopics() {
        return array('workspace');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'signature' => '',
            'force' => false,
        ));
        if (in_array($this->getProperty('force'),array('true',1,true))) $this->setProperty('force',true);

        $signature = $this->getProperty('signature');
        if (empty($signature)) return $this->modx->lexicon('package_err_ns');
        $this->package = $this->modx->getObject('transport.modTransportPackage',$signature);
        if (empty($this->package)) return $this->modx->lexicon('package_err_nf');
        return true;
    }
    
    public function process() {
        $this->modx->log(xPDO::LOG_LEVEL_INFO,$this->modx->lexicon('package_remove_info_gpack'));
        
        $transportZip = $this->modx->getOption('core_path').'packages/'.$this->package->signature.'.transport.zip';
        $transportDir = $this->modx->getOption('core_path').'packages/'.$this->package->signature.'/';
        if (file_exists($transportZip) && file_exists($transportDir)) {
            /* remove transport package */
            if ($this->package->removePackage($this->getProperty('force')) == false) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR,$this->modx->lexicon('package_err_remove'));
                return $this->failure($this->modx->lexicon('package_err_remove',array('signature' => $this->package->getPrimaryKey())));
            }
        } else {
            /* for some reason the files were removed, so just remove the DB object instead */
            $this->package->remove();
        }

        $this->clearCache();
        $this->removeTransportZip($transportZip);
        $this->removeTransportDirectory($transportDir);
        
        return $this->cleanup();
    }

    /**
     * Cleanup and return the result
     * 
     * @return array
     */
    public function cleanup() {
        $this->modx->log(modX::LOG_LEVEL_WARN,$this->modx->lexicon('package_remove_info_success'));
        sleep(2);
        $this->modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');

        $this->modx->invokeEvent('OnPackageRemove', array(
            'package' => $this->package
        ));
        
        return $this->success();
    }

    /**
     * Remove the transport package archive
     * 
     * @param string $transportZip
     * @return void
     */
    public function removeTransportZip($transportZip) {
        $this->modx->log(xPDO::LOG_LEVEL_INFO,$this->modx->lexicon('package_remove_info_tzip_start'));
        if (!file_exists($transportZip)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR,$this->modx->lexicon('package_remove_err_tzip_nf'));
        } else if (!@unlink($transportZip)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR,$this->modx->lexicon('package_remove_err_tzip'));
        } else {
            $this->modx->log(xPDO::LOG_LEVEL_INFO,$this->modx->lexicon('package_remove_info_tzip'));
        }
    }

    /**
     * Remove the transport package directory
     * 
     * @param string $transportDir
     * @return void
     */
    public function removeTransportDirectory($transportDir) {
        $this->modx->log(xPDO::LOG_LEVEL_INFO,$this->modx->lexicon('package_remove_info_tdir_start'));
        if (!file_exists($transportDir)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR,$this->modx->lexicon('package_remove_err_tdir_nf'));
        } else if (!$this->modx->cacheManager->deleteTree($transportDir,true,false,array())) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR,$this->modx->lexicon('package_remove_err_tdir'));
        } else {
            $this->modx->log(xPDO::LOG_LEVEL_INFO,$this->modx->lexicon('package_remove_info_tdir'));
        }
    }

    /**
     * Empty the site cache
     * @return void
     */
    public function clearCache() {
        $this->modx->getCacheManager();
        $this->modx->cacheManager->refresh(array($this->modx->getOption('cache_packages_key', null, 'packages') => array()));
        $this->modx->cacheManager->refresh();
        sleep(2);
    }
}
return 'modPackageRemoveProcessor';