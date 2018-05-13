<?php

namespace MODX\Processors\Security\Group\Setting;

use MODX\modAccessibleObject;

/**
 * Remove a user group setting and its lexicon strings
 *
 * @param integer $group The group associated to the setting
 * @param string $key The setting key
 *
 * @package modx
 * @subpackage processors.security.group.setting
 */
class Remove extends \MODX\Processors\System\Settings\Remove
{
    public $classKey = 'modUserGroupSetting';


    public function initialize()
    {
        $key = $this->getProperty('key', '');
        $group = (int)$this->getProperty('group', 0);

        if (empty($key) || !$group) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        $primaryKey = [
            'key' => $key,
            'group' => $group,
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