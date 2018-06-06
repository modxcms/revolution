<?php

namespace MODX\Processors\Security\Forms\Profile;
/**
 * Deactivate multiple FC Profiles
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */

class DeactivateMultiple extends Deactivate
{
    public $profiles = [];


    public function initialize()
    {
        $profiles = $this->getProperty('profiles', '');
        if (empty($profiles)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }
        $this->profiles = explode(',', $profiles);

        return true;
    }


    public function process()
    {
        foreach ($this->profiles as $profile) {
            $this->setProperty('id', $profile);
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
