<?php
/**
 * Uninstall a package
 *
 * @param string $signature The signature of the package.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
class modPackageUninstallProcessor extends modProcessor {
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
        ));
        $this->modx->log(modX::LOG_LEVEL_INFO,$this->modx->lexicon('package_uninstall_info_find',array('signature' => $this->getProperty('signature') )));
        $signature = $this->getProperty('signature');
        if (empty($signature)) {
            $this->modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
            return $this->modx->lexicon('package_err_ns');
        }
        $this->package = $this->modx->getObject('transport.modTransportPackage',$signature);
        if (empty($this->package)) {
            $this->modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
            return $this->modx->lexicon('package_err_nfs',array(
                'signature' => $signature,
            ));
        }
        return true;
    }

    public function process() {
        $transport = $this->package->getTransport();
        $this->modx->log(modX::LOG_LEVEL_INFO,$this->modx->lexicon('package_uninstall_info_prep'));

        /* uninstall package */
        $options = array(
            xPDOTransport::PREEXISTING_MODE => $this->getProperty('preexisting_mode'),
        );

        if ($this->package->uninstall($options) == false) {
            return $this->failure(sprintf($this->modx->lexicon('package_err_uninstall'),$this->package->getPrimaryKey()));
        }

        $this->modx->log(modX::LOG_LEVEL_WARN,$this->modx->lexicon('package_uninstall_info_success',array('signature' => $this->package->get('signature'))));
        sleep(2);
        $this->modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');

        $this->clearCache();
        $this->logManagerAction();
        return $this->success();
    }

    public function clearCache() {
        $this->modx->cacheManager->refresh(array($this->modx->getOption('cache_packages_key', null, 'packages') => array()));
        $this->modx->cacheManager->refresh();

    }

    public function logManagerAction() {
        $this->modx->logManagerAction('package_uninstall','transport.modTransportPackage',$this->package->get('id'));
    }
}
return 'modPackageUninstallProcessor';