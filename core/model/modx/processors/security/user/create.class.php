<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

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
    public $languageTopics = array('user', 'login');
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

        if ($this->modx->hasPermission('set_sudo')) {
            $sudo = $this->getProperty('sudo', null);
            if ($sudo !== null) {
                $this->object->setSudo(!empty($sudo));
            }
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
     * Send notification email for changed password
     */
    public function sendNotificationEmail() {
        if ($this->getProperty('passwordgenmethod') === 'user_email_specify') {
            $activationHash = md5(uniqid(md5($this->object->get('email') . '/' . $this->object->get('id')), true));

            /** @var modRegistry $registry */
            $registry = $this->modx->getService('registry', 'registry.modRegistry');
            /** @var modRegister $register */
            $register = $registry->getRegister('user', 'registry.modDbRegister');
            $register->connect();
            $register->subscribe('/pwd/change/');
            $register->send('/pwd/change/', [$activationHash => $this->object->get('username')], ['ttl' => 86400]);

            $this->modx->lexicon->load('core:login');

            // Send activation email
            $message                = $this->modx->lexicon('user_password_email');
            $placeholders           = array_merge($this->modx->config, $this->object->toArray());
            $placeholders['hash']   = $activationHash;

            // Store previous placeholders
            $ph = $this->modx->placeholders;
            // now set those useful for modParser
            $this->modx->setPlaceholders($placeholders);
            $this->modx->getParser()->processElementTags('', $message, true, false, '[[', ']]', [], 10);
            $this->modx->getParser()->processElementTags('', $message, true, true, '[[', ']]', [], 10);
            // Then restore previous placeholders to prevent any breakage
            $this->modx->placeholders = $ph;

            $this->modx->getService('smarty', 'smarty.modSmarty', '', ['template_dir' => $this->modx->getOption('manager_path') . 'templates/default/']);

            $this->modx->smarty->assign('_config', $this->modx->config);
            $this->modx->smarty->assign('content', $message, true);

            $sent    = $this->object->sendEmail(
                $this->modx->smarty->fetch('email/default.tpl'),
                [
                    'from'          => $this->modx->getOption('emailsender'),
                    'fromName'      => $this->modx->getOption('site_name'),
                    'sender'        => $this->modx->getOption('emailsender'),
                    'subject'       => $this->modx->lexicon('user_password_email_subject'),
                    'html'          => true,
                ]
            );

            if (!$sent) {
                return $this->failure($this->modx->lexicon('error_sending_email_to') . $this->object->get('email'));
            }
        }
    }

    /**
     * {@inheritDoc}
     * @return array|string
     */
    public function cleanup() {
        $passwordGenerationMethod = $this->getProperty('passwordgenmethod');
        if (!empty($passwordGenerationMethod) && !empty($this->newPassword)) {
            return $this->success(
                $this->modx->lexicon('user_updated_password_message',
                    array(
                        'password' => $this->newPassword,
                    )
                ),
                $this->object);
        } else {
            return $this->success('', $this->object);
        }
    }
}
return 'modUserCreateProcessor';
