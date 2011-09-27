<?php
/**
 * Update a user profile
 *
 * @package modx
 * @subpackage processors.security.profile
 */
class modProfileUpdateProcessor extends modProcessor {
    /** @var modUserProfile $profile */
    public $profile;

    public function checkPermissions() {
        return $this->modx->hasPermission('change_profile');
    }
    public function getLanguageTopics() {
        return array('user');
    }

    public function initialize() {
        $this->profile = $this->modx->user->getOne('Profile');
        if (empty($this->profile)) {
            return $this->modx->lexicon('user_profile_err_not_found');
        }
        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return array|string
     */
    public function process() {
        $this->prepare();

        /* save profile */
        if ($this->profile->save() == false) {
            return $this->failure($this->modx->lexicon('user_profile_err_save'));
        } else {
            /* log manager action */
            $this->modx->logManagerAction('save_profile','modUser',$this->modx->user->get('id'));
        }

        return $this->success($this->modx->lexicon('success'),$this->profile->toArray());
    }

    public function prepare() {
        $properties = $this->getProperties();
        
        /* format and set data */
        $dob = $this->getProperty('dob');
        if (!empty($dob)) {
            $properties['dob'] = strtotime($dob);
        }
        $this->profile->fromArray($properties);
    }
    
}
return 'modProfileUpdateProcessor';