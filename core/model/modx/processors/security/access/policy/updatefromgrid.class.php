<?php
require_once (dirname(__FILE__).'/update.class.php');
/**
 * Update a policy from a grid
 *
 * @param integer $id The ID of the policy
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 * @param integer $parent (optional) A parent policy
 * @param string $class
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
class modAccessPolicyUpdateFromGridProcessor extends modAccessPolicyUpdateProcessor {
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
return 'modAccessPolicyUpdateFromGridProcessor';