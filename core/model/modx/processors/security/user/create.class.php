<?php
require_once (dirname(__FILE__).'/_validation.php');
/**
 * Create a user
 *
 * @param string $newusername The username for the user
 * @param string $passwordnotifymethod The notification method for the user.
 *
 * @package modx
 * @subpackage processors.security.user
 */
class modUserCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modUser';
    public $languageTopics = array('user');
    public $permission = 'new_user';
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
            $className = $classKey.'CreateProcessor';
            if (!class_exists($className)) {
                $className = 'modUserCreateProcessor';
            }
        }
        /** @var modProcessor $processor */
        $processor = new $className($modx,$properties);
        return $processor;
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'class_key' => $this->classKey,
            'blocked' => false,
            'active' => false,
        ));
        $this->classKey = $this->getProperty('class_key','modUser');
        $this->setProperty('blocked',$this->getProperty('blocked') ? true : false);
        return parent::initialize();
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function beforeSave() {
        $this->addProfile();

        $sudo = $this->getProperty('sudo',null);
        if ($sudo !== null) {
            $this->object->setSudo(!empty($sudo));
        }

        $this->validator = new modUserValidation($this,$this->object,$this->profile);
        $this->validator->validate();

        return parent::beforeSave();
    }

    /**
     * Add User Group memberships to the User
     * @return modUserGroupMember[]
     */
    public function setUserGroups()
    {
        $memberships = array();
        $groups = $this->getProperty('groups', null);
        if ($groups !== null) {
            $primaryGroupId = 0;
            $groups = is_array($groups) ? $groups : json_decode($groups, true);
            $groupsAdded = array();
            $idx = 0;
            foreach ($groups as $group) {
                if (in_array($group['usergroup'], $groupsAdded)) {
                    continue;
                }

                /** @var modUserGroupMember $membership */
                $membership = $this->modx->newObject('modUserGroupMember');
                $membership->fromArray(array(
                    'user_group' => $group['usergroup'],
                    'role' => $group['role'],
                    'member' => $this->object->get('id'),
                    'rank' => isset($group['rank']) ? $group['rank'] : $idx
                ));
                if (empty($group['rank'])) {
                    $primaryGroupId = $group['usergroup'];
                }

                $usergroup = $this->modx->getObject('modUserGroup', $group['usergroup']);
                /* invoke OnUserBeforeAddToGroup event */
                $OnUserBeforeAddToGroup = $this->modx->invokeEvent('OnUserBeforeAddToGroup', array(
                    'user' => &$this->object,
                    'usergroup' => &$usergroup,
                    'membership' => &$membership,
                ));
                $canSave = $this->processEventResponse($OnUserBeforeAddToGroup);
                if (!empty($canSave)) {
                    $this->object->save();
                    return $this->failure($canSave);
                }

                if ($membership->save()) {
                    $memberships[] = $membership;
                } else {
                    $this->object->save();
                    return $this->failure($this->modx->lexicon('user_group_member_err_save'));
                }

                /* invoke OnUserAddToGroup event */
                $this->modx->invokeEvent('OnUserAddToGroup', array(
                    'user' => &$this->object,
                    'usergroup' => &$usergroup,
                    'membership' => &$membership,
                ));

                $groupsAdded[] = $group['usergroup'];
                $idx++;
            }
            $this->object->addMany($memberships, 'UserGroupMembers');
            $this->object->set('primary_group', $primaryGroupId);
            $this->object->save();
        }
        return $memberships;
    }

    /**
     * @return modUserProfile
     */
    public function addProfile() {
        $this->profile = $this->modx->newObject('modUserProfile');
        $this->profile->fromArray($this->getProperties());
        $this->profile->set('blocked',$this->getProperty('blocked',false));
        $this->profile->set('photo','');
        $this->object->addOne($this->profile,'Profile');
        return $this->profile;
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function afterSave() {
        $this->setUserGroups();
        $this->sendNotificationEmail();
        return parent::afterSave();
    }

    /**
     * Send the password notification email, if specified
     * @return void
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
        if (!empty($passwordNotifyMethod) && $passwordNotifyMethod  == 's') {
            return $this->success($this->modx->lexicon('user_created_password_message',array(
                'password' => $this->newPassword,
            )),$this->object);
        } else {
            return $this->success('',$this->object);
        }
    }
}
return 'modUserCreateProcessor';
