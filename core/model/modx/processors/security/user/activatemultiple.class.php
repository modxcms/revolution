<?php
/**
 * Activate multiple users
 *
 * @package modx
 * @subpackage processors.security.user
 */
class modUserActivateMultipleProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('save_user');
    }
    public function getLanguageTopics() {
        return array('user');
    }

    public function process() {
        $users = $this->getProperty('users');
        if (empty($users)) {
            return $this->failure($this->modx->lexicon('user_err_ns'));
        }
        $userIds = explode(',',$users);

        foreach ($userIds as $userId) {
            /** @var modUser $user */
            $user = $this->modx->getObject('modUser',$userId);
            if ($user == null) continue;

            $OnBeforeUserActivate = $this->modx->invokeEvent('OnBeforeUserActivate',array(
                'id' => $userId,
                'user' => &$user,
                'mode' => 'multiple',
            ));
            $canRemove = $this->processEventResponse($OnBeforeUserActivate);
            if (!empty($canRemove)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,$canRemove);
                continue;
            }

            $user->set('active',true);

            if ($user->save() === false) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,$this->modx->lexicon('user_err_save'));
            } else {
                $this->modx->invokeEvent('OnUserActivate',array(
                    'id' => $userId,
                    'user' => &$user,
                    'mode' => 'multiple',
                ));
            }
        }

        return $this->success();
    }
}
return 'modUserActivateMultipleProcessor';