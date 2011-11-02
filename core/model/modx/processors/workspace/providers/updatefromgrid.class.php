<?php
require_once (dirname(__FILE__).'/update.class.php');
/**
 * Update a provider from a grid
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
class modProviderUpdateFromGridProcessor extends modProviderUpdateProcessor {
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $data = $this->modx->fromJSON($data);
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $this->setProperties($data);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}
return 'modProviderUpdateFromGridProcessor';