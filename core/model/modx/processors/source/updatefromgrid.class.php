<?php
//require_once (dirname(__FILE__).'/update.class.php');
/**
 * Update a Source from the grid. Sent through JSON-encoded 'data' parameter.
 *
 * @param integer $id The ID of the Source
 * @param string $name The new name
 * @param string $description (optional) A short description
 *
 * @package modx
 * @subpackage processors.source
 */
 class modMediaSourceUpdateFromGridProcessor extends modSourceUpdateProcessor {
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
return 'modMediaSourceUpdateFromGridProcessor';
