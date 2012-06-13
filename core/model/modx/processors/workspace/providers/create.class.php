<?php
/**
 * Create a provider
 *
 * @param string $name The name of the provider
 * @param string $description A short description
 * @param string $service_url The URL the provider is hosted under
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
class modProviderCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'transport.modTransportProvider';
    public $languageTopics = array('workspace');
    public $permission = 'providers';
    public $objectType = 'provider';
    
    /** @var modTransportProvider $object */
    public $object;

    public function beforeSave() {
        $name = $this->getProperty('name','');
        if (empty($name)) $this->addFieldError('name',$this->modx->lexicon('provider_err_ns_name'));

        $serviceUrl = $this->getProperty('service_url','');
        if (empty($serviceUrl)) $this->addFieldError('service_url',$this->modx->lexicon('provider_err_ns_url'));

        $verified = $this->object->verify();
        if ($verified !== true) {
            if (!is_bool($verified)) {
                $this->addFieldError('service_url',$verified);
            } else {
                $this->addFieldError('service_url',$this->modx->lexicon('provider_err_not_verified'));
            }
        }

        return parent::beforeSave();
    }
}
return 'modProviderCreateProcessor';