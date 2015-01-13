<?php
include_once dirname(__FILE__).'/update.class.php';
/**
 * Update Plugin event from the grid
 * @param string $data JSON string with plugin event data
 * @package modx
 * @subpackage processors.element.plugin.event
 */

class modPluginEventUpdateFromGridProcessor extends modPluginEventUpdateProcessor {

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

return 'modPluginEventUpdateFromGridProcessor';