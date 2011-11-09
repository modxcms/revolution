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

    /** @var modUser $object */
    public $object;
    /** @var modUserProfile $profile */
    public $profile;
    /** @var modUserValidation $validator */
    public $validator;
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

    public function initialize() {
        $this->setDefaultProperties(array(
            'class_key' => $this->classKey,
        ));
        $this->classKey = $this->getProperty('class_key');
        return parent::initialize();
    }

    public function beforeSet() {
        $this->setCheckbox('blocked');
        $this->setCheckbox('active');
        return parent::beforeSet();
    }


    public function beforeSave() {
        $this->setProfile();
        $this->setRemoteData();

        $this->validator = new modUserValidation($this,$this->object,$this->profile);
        $this->validator->validate();
        return parent::beforeSave();
    }

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

    public function setRemoteData() {
        $remoteData = $this->getProperty('remoteData',null);
        if ($remoteData !== null) {
            $remoteData = is_array($remoteData) ? $remoteData : $this->modx->fromJSON($remoteData);
            $this->object->set('remote_data',$remoteData);
        }
        return $remoteData;
    }

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
            $groups = is_array($groups) ? $groups : $this->modx->fromJSON($groups);
            foreach ($groups as $group) {
                $membership = $this->modx->newObject('modUserGroupMember');
                $membership->set('user_group',$group['usergroup']);
                $membership->set('role',$group['role']);
                $membership->set('member',$this->object->get('id'));
                $membership->set('rank',$group['rank']);
                if (empty($group['rank'])) {
                    $primaryGroupId = $group['usergroup'];
                }
                $memberships[] = $membership;
            }
            $this->object->addMany($memberships,'UserGroupMembers');
            $this->object->set('primary_group',$primaryGroupId);
        }
        return $memberships;
    }

    public function afterSave() {
        $this->sendNotificationEmail();
        return parent::afterSave();
    }

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
            return $this->success($this->modx->lexicon('user_created_password_message',array(
                'password' => $this->newPassword,
            )),$this->object);
        } else {
            return $this->success('',$this->object);
        }
    }
}
return 'modUserUpdateProcessor';