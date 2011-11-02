<?php
require_once (dirname(__FILE__) . '/update.class.php');
/**
 * Updates a role from a grid. Passed as JSON data
 *
 * @param integer $id The ID of the role
 *
 * @package modx
 * @subpackage processors.security.role
 */
class modUserGroupRoleUpdateFromGridProcessor extends modUserGroupRoleUpdateProcessor {
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $properties = $this->modx->fromJSON($data);
        $this->setProperties($properties);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}
return 'modUserGroupRoleUpdateFromGridProcessor';