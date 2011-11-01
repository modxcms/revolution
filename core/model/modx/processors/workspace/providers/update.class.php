<?php
/**
 * Update a provider
 *
 * @param integer $id The ID of the provider
 * @param string $name The new name for the provider
 * @param string $description A short description
 * @param string $service_url The URL which the provider is hosted under
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
class modProviderUpdateProcessor extends modProcessor {
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
        if (!$this->validate()) {
            return $this->failure();
        }
        $this->provider->fromArray($this->getProperties());

        $verified = $this->provider->verify();
        if ($verified !== true) {
            return $this->failure($verified);
        }

        if ($this->provider->save() == false) {
            return $this->failure($this->modx->lexicon('provider_err_save'));
        }

        $this->logManagerAction();
        return $this->success('',$this->provider);
    }

    /**
     * Validate the form field
     * @return boolean
     */
    public function validate() {
        $name = $this->getProperty('name','');
        if (empty($name)) $this->addFieldError('name',$this->modx->lexicon('provider_err_ns_name'));

        $serviceUrl = $this->getProperty('service_url','');
        if (empty($serviceUrl)) $this->addFieldError('service_url',$this->modx->lexicon('provider_err_ns_url'));

        return !$this->hasErrors();
    }

    /**
     * Log the manager action
     *
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('provider_update','transport.modTransportProvider',$this->provider->get('id'));
    }
}
return 'modProviderUpdateProcessor';