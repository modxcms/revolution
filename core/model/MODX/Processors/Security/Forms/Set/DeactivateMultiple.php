<?php

namespace MODX\Processors\Security\Forms\Set;

/**
 * Deactivate multiple FC Sets
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
class DeactivateMultiple extends Deactivate
{
    public $sets = [];


    public function initialize()
    {
        $sets = $this->getProperty('sets', '');
        if (empty($sets)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }
        $this->sets = explode(',', $sets);

        return true;
    }


    public function process()
    {
        foreach ($this->sets as $set) {
            $this->setProperty('id', $set);
            $initialized = parent::initialize();
            if ($initialized === true) {
                $o = parent::process();
                if (!$o['success']) {
                    return $o;
                }
            } else {
                return $this->failure($initialized);
            }
        }

        return $this->success();
    }
}
