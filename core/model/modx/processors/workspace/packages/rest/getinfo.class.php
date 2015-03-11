<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages.rest
 */
class modPackageGetInfoProcessor extends modProcessor {
    /** @var modTransportProvider $provider */
    public $provider;

    public function checkPermissions() {
        return $this->modx->hasPermission('packages');
    }

    public function getLanguageTopics() {
        return array('workspace');
    }

    public function process() {
        if (!$this->loadProvider()) {
            return array();
        }

        $info = $this->provider->stats();
        if (empty($info)) {
            return $this->failure($this->modx->lexicon('provider_err_connect'));
        }

        return $this->success('', $info);
    }

    /**
     * Load the provider
     * @return boolean
     */
    public function loadProvider() {
        $loaded = false;
        $provider = $this->getProperty('provider',false);
        if (!empty($provider)) {
            $this->provider = $this->modx->getObject('transport.modTransportProvider',$provider);
            if (!empty($this->provider)) {
                $loaded = true;
            }
        }
        return $loaded;
    }
}
return 'modPackageGetInfoProcessor';
