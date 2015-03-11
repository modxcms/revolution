<?php
include_once MODX_CORE_PATH . 'model/modx/processors/system/settings/create.class.php';
/**
 * Create a User Group setting
 *
 * @param integer $group/$fk The group to create the setting for
 * @param string $key The setting key
 * @param string $value The value of the setting
 * @param string $name The lexicon name for the setting
 * @param string $description The lexicon description for the setting
 * @param string $area The area for the setting
 * @param string $namespace The namespace for the setting
 *
 * @package modx
 * @subpackage processors.security.group.setting
 */
class modUserGroupSettingCreateProcessor extends modSystemSettingsCreateProcessor {
    public $classKey = 'modUserGroupSetting';
    public $languageTopics = array('setting', 'namespace', 'user');

    public function beforeSave() {
        $group = (int)$this->getProperty('fk', $this->getProperty('group', 0));
        if (!$group) {
            $this->addFieldError('fk', $this->modx->lexicon('user_group_err_ns'));
        }
        $this->object->set('group', $group);
        return parent::beforeSave();
    }

    /**
     * Check to see if a Setting already exists with this key and usergroup
     * @return boolean
     */
    public function alreadyExists() {
        return $this->doesAlreadyExist(array(
            'key' => $this->object->get('key'),
            'group' => $this->object->get('group'),
        ));
    }
}

return 'modUserGroupSettingCreateProcessor';
