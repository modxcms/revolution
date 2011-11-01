<?php
/**
 * Gets a provider
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
class modProviderGetProcessor extends modProcessor {
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
        $name = $this->getProperty('name',false);

        $c = !empty($id) ? array('id' => $id) : array('name' => $name);
        if (empty($id) && empty($name)) return $this->modx->lexicon('provider_err_ns');
        $this->provider = $this->modx->getObject('transport.modTransportProvider',$c);
        if (empty($this->provider)) return $this->modx->lexicon('provider_err_nfs',$c);
        return true;
    }

    public function process() {
        return $this->success('',$this->provider);
    }
}
return 'modProviderGetProcessor';