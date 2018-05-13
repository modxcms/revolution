<?php

namespace MODX\Processors\Security\Access;

use MODX\Processors\modObjectGetProcessor;

/**
 * Gets an ACL.
 *
 * @param string $type The class_key for the ACL.
 * @param string $id The ID of the ACL.
 *
 * @package modx
 * @subpackage processors.security.access
 */
class GetAcl extends modObjectGetProcessor
{
    public $objectType = 'access';
    public $permission = 'access_permissions';
    public $languageTopics = ['access'];


    public function initialize()
    {
        $this->classKey = $this->getProperty('type');
        $id = $this->getProperty('id');
        if (!$this->classKey || !$id) {
            return $this->modx->lexicon($this->objectType . '_type_err_ns');
        }
        $this->object = $this->modx->getObject($this->classKey, $id);
        if (!$this->object) {
            return $this->modx->lexicon($this->objectType . '_err_nf');
        }

        return true;
    }
}
