<?php

namespace MODX\Processors\Security\User\Settings;

use MODX\modAccessibleObject;

/**
 * Remove a user setting and its lexicon strings
 *
 * @param integer $user The user associated to the setting
 * @param string $key The setting key
 *
 * @package modx
 * @subpackage processors.security.user.setting
 */
class Remove extends \MODX\Processors\System\Settings\Remove
{
    public $classKey = 'modUserSetting';


    public function initialize()
    {
        $key = $this->getProperty('key', '');
        $user = (int)$this->getProperty('user', 0);

        if (empty($key) || !$user) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        $primaryKey = [
            'key' => $key,
            'user' => $user,
        ];
        $this->object = $this->modx->getObject($this->classKey, $primaryKey);

        if (!$this->object) {
            return $this->modx->lexicon($this->objectType . '_err_nf');
        }

        if ($this->checkRemovePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('remove')) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }
}
