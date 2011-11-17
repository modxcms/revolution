<?php
require_once (dirname(__FILE__).'/update.class.php');
/**
 * Update a user from a grid
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
class modUserUpdateFromGridProcessor extends modUserUpdateProcessor {
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
return 'modUserUpdateFromGridProcessor';