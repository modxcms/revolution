<?php
/**
 * Remove a provider
 *
 * @param integer $id The provider ID
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
class modProviderRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'transport.modTransportProvider';
    public $languageTopics = array('workspace');
    public $permission = 'providers';
    public $objectType = 'provider';
}
return 'modProviderRemoveProcessor';