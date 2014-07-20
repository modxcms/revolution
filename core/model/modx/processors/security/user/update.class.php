<?php
require_once (dirname(__FILE__).'/_validation.php');
/**
 * Update a user.
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
class modUserUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modUser';
    public $languageTopics = array('user');
    public $permission = 'save_user';
    public $objectType = 'user';
    public $beforeSaveEvent = 'OnBeforeUserFormSave';
    public $afterSaveEvent = 'OnUserFormSave';

    /** @var boolean $activeStatusChanged */
    public $activeStatusChanged = false;
    /** @var boolean $newActiveStatus */
    public $newActiveStatus = false;

    /** @var modUser $object */
    public $object;
    /** @var modUserProfile $profile */
    public $profile;
    /** @var modUserValidation $validator */
    public $validator;
    /** @var string $newPassword */
    public $newPassword = '';


    /**
     * Allow for Users to use derivative classes for their processors
     *
     * @static
     * @param modX $modx
     * @param $className
     * @param array $properties
     * @return modProcessor
     */
    public static function getInstance(modX &$modx,$className,$properties = array()) {
        $classKey = !empty($properties['class_key']) ? $properties['class_key'] : 'modUser';
        $object = $modx->newObject($classKey);

        if (!in_array($classKey,array('modUser',''))) {
            $className = $classKey.'UpdateProcessor';
            if (!class_exists($className)) {
                $className = 'modUserUpdateProcessor';
            }
        }
        /** @var modProcessor $processor */
        $processor = new $className($modx,$properties);
        return $processor;
    }

    /**
     * {@inheritDoc}
     * @return boolean|string
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'class_key' => $this->classKey,
        ));
        return parent::initialize();
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function beforeSet() {
        $this->setCheckbox('blocked');
        $this->setCheckbox('active');
        $this->setCheckbox('sudo');
        return parent::beforeSet();
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function beforeSave() {
        $this->setProfile();
        $this->setRemoteData();

        $sudo = $this->getProperty('sudo',null);
        if ($sudo !== null) {
            $this->object->setSudo(!empty($sudo));
        }

        $this->validator = new modUserValidation($this,$this->object,$this->profile);
        $this->validator->validate();
        $canChangeStatus = $this->checkActiveChange();
        if ($canChangeStatus !== true) {
            $this->addFieldError('active',$canChangeStatus);
        }
        return parent::beforeSave();
    }

    /**
     * Check for an active/inactive status change
     * @return boolean|string
     */
    public function checkActiveChange() {
        $this->activeStatusChanged = $this->object->isDirty('active');
        $this->newActiveStatus = $this->object->get('active');
        if ($this->activeStatusChanged) {
            $event = $this->newActiveStatus == true ? 'OnBeforeUserActivate' : 'OnBeforeUserDeactivate';
            $OnBeforeUserActivate = $this->modx->invokeEvent($event,array(
                'id' => $this->object->get('id'),
                'user' => &$this->object,
                'mode' => modSystemEvent::MODE_UPD,
            ));
            $canChange = $this->processEventResponse($OnBeforeUserActivate);
            if (!empty($canChange)) {
                return $canChange;
            }
        }
        return true;
    }

    /**
     * Set the profile data for the user
     * @return modUserProfile
     */
    public function setProfile() {
        $this->profile = $this->object->getOne('Profile');
        if (empty($this->profile)) {
            $this->profile = $this->modx->newObject('modUserProfile');
            $this->profile->set('internalKey',$this->object->get('id'));
            $this->profile->save();
            $this->object->addOne($this->profile,'Profile');
        }
        $this->profile->fromArray($this->getProperties());
        return $this->profile;
    }

    /**
     * Set the remote data for the user
     * @return mixed
     */
    public function setRemoteData() {
        $remoteData = $this->getProperty('remoteData',null);
        if ($remoteData !== null) {
            $remoteData = is_array($remoteData) ? $remoteData : $this->modx->fromJSON($remoteData);
            $this->object->set('remote_data',$remoteData);
        }
        return $remoteData;
    }

    /**
     * Set user groups for the user
     * @return array
     */
    public function setUserGroups() {
        $memberships = array();
        $groups = $this->getProperty('groups',null);
        if ($groups !== null) {
            $primaryGroupId = 0;
            /* remove prior user group links */
            $oldMemberships = $this->object->getMany('UserGroupMembers');
            /** @var modUserGroupMember $membership */
            foreach ($oldMemberships as $membership) { $membership->remove(); }

            /* create user group links */
            $groupsAdded = array();
            $groups = is_array($groups) ? $groups : $this->modx->fromJSON($groups);
            $idx = 0;
            foreach ($groups as $group) {
                if (in_array($group['usergroup'],$groupsAdded)) continue;
                $membership = $this->modx->newObject('modUserGroupMember');
                $membership->set('user_group',$group['usergroup']);
                $membership->set('role',$group['role']);
                $membership->set('member',$this->object->get('id'));
                $membership->set('rank',isset($group['rank']) ? $group['rank'] : $idx);
                if (empty($group['rank'])) {
                    $primaryGroupId = $group['usergroup'];
                }
                $memberships[] = $membership;
                $groupsAdded[] = $group['usergroup'];
                $idx++;
            }
            $this->object->addMany($memberships,'UserGroupMembers');
            $this->object->set('primary_group',$primaryGroupId);
            $this->object->save();
        }
        return $memberships;
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function afterSave() {
        $this->setUserGroups();
        $this->sendNotificationEmail();
        if ($this->activeStatusChanged) {
            $this->fireAfterActiveStatusChange();
        }
        return parent::afterSave();
    }

    /**
     * Fire the active status change event
     */
    public function fireAfterActiveStatusChange() {
        $event = $this->newActiveStatus == true ? 'OnUserActivate' : 'OnUserDeactivate';
        $this->modx->invokeEvent($event,array(
            'id' => $this->object->get('id'),
            'user' => &$this->object,
            'mode' => modSystemEvent::MODE_UPD,
        ));
    }

    /**
     * Send notification email for changed password
     */
    public function sendNotificationEmail() {
        if ($this->getProperty('passwordnotifymethod') == 'e') {
            $message = $this->modx->getOption('signupemail_message');
            $placeholders = array(
                'uid' => $this->object->get('username'),
                'pwd' => $this->newPassword,
                'ufn' => $this->profile->get('fullname'),
                'sname' => $this->modx->getOption('site_name'),
                'saddr' => $this->modx->getOption('emailsender'),
                'semail' => $this->modx->getOption('emailsender'),
                'surl' => $this->modx->getOption('url_scheme') . $this->modx->getOption('http_host') . $this->modx->getOption('manager_url'),
            );
            foreach ($placeholders as $k => $v) {
                $message = str_replace('[[+'.$k.']]',$v,$message);
            }
            $this->object->sendEmail($message);
        }
    }

    /**
     * {@inheritDoc}
     * @return array|string
     */
    public function cleanup() {
        $passwordNotifyMethod = $this->getProperty('passwordnotifymethod');
        if (!empty($passwordNotifyMethod) && !empty($this->newPassword) && $passwordNotifyMethod  == 's') {
            return $this->success($this->modx->lexicon('user_updated_password_message',array(
                'password' => $this->newPassword,
            )),$this->object);
        } else {
            return $this->success('',$this->object);
        }
    }
}
return 'modUserUpdateProcessor';
