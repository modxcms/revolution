<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Security\User;


use Exception;
use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserProfile;
use MODX\Revolution\modX;
use MODX\Revolution\Smarty\modSmarty;

/**
 * Create a user
 *
 * @param string $newusername The username for the user
 * @param string $passwordnotifymethod The notification method for the user.
 *
 * @package MODX\Revolution\Processors\Security\User
 */
class Create extends CreateProcessor {
    public $classKey = modUser::class;
    public $languageTopics = ['user', 'login'];
    public $permission = 'new_user';
    public $objectType = 'user';
    public $beforeSaveEvent = 'OnBeforeUserFormSave';
    public $afterSaveEvent = 'OnUserFormSave';

    /** @var modUser $object */
    public $object;
    /** @var modUserProfile $profile */
    public $profile;
    /** @var Validation $validator */
    public $validator;
    public $newPassword = '';

    /**
     * Allow for Users to use derivative classes for their processors
     *
     * @static
     * @param modX $modx
     * @param $className
     * @param array $properties
     * @return Processor
     */
    public static function getInstance(modX $modx,$className,$properties = []) {
        $classKey = !empty($properties['class_key']) ? $properties['class_key'] : modUser::class;
        $object = $modx->newObject($classKey);

        if (!in_array($classKey, [modUser::class,''])) {
            $className = $classKey.'CreateProcessor';
            if (!class_exists($className)) {
                $className = static::class;
            }
        }
        /** @var Processor $processor */
        $processor = new $className($modx,$properties);
        return $processor;
    }

    public function initialize() {
        $this->setDefaultProperties(
            [
                'class_key' => $this->classKey,
                'blocked' => false,
                'active' => false,
            ]
        );
        $this->classKey = $this->getProperty('class_key', modUser::class);
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

        $this->validator = new Validation($this,$this->object,$this->profile);
        $this->validator->validate();

        return parent::beforeSave();
    }

    /**
     * Add User Group memberships to the User
     * @return modUserGroupMember[]
     */
    public function setUserGroups()
    {
        $memberships = [];
        $groups = $this->getProperty('groups', null);
        if ($groups !== null) {
            $primaryGroupId = 0;
            $groups = is_array($groups) ? $groups : json_decode($groups, true);
            $groupsAdded = [];
            $idx = 0;
            foreach ($groups as $group) {
                if (in_array($group['usergroup'], $groupsAdded)) {
                    continue;
                }

                /** @var modUserGroupMember $membership */
                $membership = $this->modx->newObject(modUserGroupMember::class);
                $membership->fromArray(
                    [
                        'user_group' => $group['usergroup'],
                        'role' => $group['role'],
                        'member' => $this->object->get('id'),
                        'rank' => isset($group['rank']) ? $group['rank'] : $idx
                    ]
                );
                if (empty($group['rank'])) {
                    $primaryGroupId = $group['usergroup'];
                }

                $usergroup = $this->modx->getObject(modUserGroup::class, $group['usergroup']);
                /* invoke OnUserBeforeAddToGroup event */
                $OnUserBeforeAddToGroup = $this->modx->invokeEvent('OnUserBeforeAddToGroup', [
                    'user' => &$this->object,
                    'usergroup' => &$usergroup,
                    'membership' => &$membership,
                ]
                );
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
                $this->modx->invokeEvent('OnUserAddToGroup', [
                    'user' => &$this->object,
                    'usergroup' => &$usergroup,
                    'membership' => &$membership,
                ]
                );

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
        $this->profile = $this->modx->newObject(modUserProfile::class);
        $this->profile->fromArray($this->getProperties());
        $this->profile->set('blocked',$this->getProperty('blocked',false));
        $this->profile->set('photo','');
        $this->object->addOne($this->profile,'Profile');
        return $this->profile;
    }

    /**
     * {@inheritDoc}
     * @return boolean
     * @throws Exception
     */
    public function afterSave() {
        $this->setUserGroups();
        $this->sendNotificationEmail();
        return parent::afterSave();
    }

    /**
     * Send the password notification email, if specified
     *
     * @return void
     * @throws Exception
     */
    public function sendNotificationEmail() {
        if ($this->getProperty('notify_new_user')) {
            $message = $this->modx->getOption('signupemail_message', null, $this->modx->lexicon('login_signup_email'), true);
            $placeholders = array_merge($this->modx->config, $this->profile->toArray(), $this->object->toArray(), [
                'uid' => $this->object->get('username'),
                'ufn' => $this->profile->get('fullname'),
                'sname' => $this->modx->getOption('site_name'),
                'surl' => $this->modx->getOption('url_scheme') . $this->modx->getOption('http_host') . $this->modx->getOption('manager_url'),
            ]);

            // Store previous placeholders
            $ph = $this->modx->placeholders;
            // now set those useful for modParser
            $this->modx->setPlaceholders($placeholders);
            $this->modx->getParser()->processElementTags('', $message, true, false, '[[', ']]', [], 10);
            $this->modx->getParser()->processElementTags('', $message, true, true, '[[', ']]', [], 10);
            // Then restore previous placeholders to prevent any breakage
            $this->modx->placeholders = $ph;

            $this->modx->getService('smarty', modSmarty::class, '', [
                'template_dir' => $this->modx->getOption('manager_path') . 'templates/' . $this->modx->getOption('manager_theme', null, 'default') . '/',
            ]);
            $this->modx->smarty->assign('_config', $this->modx->config);
            $this->modx->smarty->assign('content', $message);
            $message = $this->modx->smarty->fetch('email/default.tpl');
            $this->object->sendEmail($message, [
                'subject' => $this->modx->lexicon('login_email_subject'),
                'html' => true,
            ]);
        }
    }

    /**
     * {@inheritDoc}
     * @return array|string
     */
    public function cleanup() {
        $passwordNotifyMethod = $this->getProperty('passwordnotifymethod', 's');
        if (!empty($passwordNotifyMethod) && $passwordNotifyMethod == 's') {
            return $this->success($this->modx->lexicon('user_created_password_message', [
                'username' => $this->object->get('username'),
                'password' => $this->newPassword,
            ]), $this->object);
        } else {
            return $this->success('', $this->object);
        }
    }
}
