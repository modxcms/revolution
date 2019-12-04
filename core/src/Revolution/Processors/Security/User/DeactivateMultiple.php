<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\User;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modUser;
use MODX\Revolution\modX;

/**
 * Deactivate multiple users
 * @package MODX\Revolution\Processors\Security\User
 */
class DeactivateMultiple extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('save_user');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['user'];
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $users = $this->getProperty('users');
        if (empty($users)) {
            return $this->failure($this->modx->lexicon('user_err_ns'));
        }
        $userIds = explode(',', $users);

        foreach ($userIds as $userId) {
            /** @var modUser $user */
            $user = $this->modx->getObject(modUser::class, $userId);
            if ($user === null) {
                continue;
            }

            $OnBeforeUserActivate = $this->modx->invokeEvent('OnBeforeUserDeactivate', [
                'id' => $userId,
                'user' => &$user,
                'mode' => 'multiple',
            ]);
            $canRemove = $this->processEventResponse($OnBeforeUserActivate);
            if (!empty($canRemove)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, $canRemove);
                continue;
            }

            $user->set('active', false);

            if ($user->save() === false) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('user_err_save'));
            } else {
                $this->modx->invokeEvent('OnUserDeactivate', [
                    'id' => $userId,
                    'user' => &$user,
                    'mode' => 'multiple',
                ]);
            }
        }

        return $this->success();
    }
}
