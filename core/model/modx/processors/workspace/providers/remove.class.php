<?php
/**
 * Remove a provider
 *
 * @param integer $id The provider ID
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
class modProviderRemoveProcessor extends modProcessor {
    /** @var modTransportProvider $provider */
    public $provider;

    public function checkPermissions() {
        return $this->modx->hasPermission('providers');
    }
    public function getLanguageTopics() {
        return array('workspace');
    }

    public function initialize() {
        $id = $this->getProperty('id',false);
        if (empty($id)) return $this->modx->lexicon('provider_err_ns');
        $this->provider = $this->modx->getObject('transport.modTransportProvider',$id);
        if (empty($this->provider)) return $this->modx->lexicon('provider_err_nfs',array('id' => $id));
        return true;
    }

    public function process() {
        if ($this->provider->remove() == false) {
            return $this->failure($this->modx->lexicon('provider_err_remove'));
        }

        $this->logManagerAction();
        return $this->success('',$this->provider);
    }

    /**
     * Log the manager action
     *
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('provider_remove','transport.modTransportProvider',$this->provider->get('id'));
    }
}
return 'modProviderRemoveProcessor';