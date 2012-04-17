<?php
/**
 * Duplicates a chunk.
 *
 * @param integer $id The chunk to duplicate
 * @param string $name The name of the new chunk.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
class modUserDuplicateProcessor extends modObjectDuplicateProcessor {
    public $classKey = 'modUser';
    public $languageTopics = array('user');
    public $permission = 'new_user';
    public $objectType = 'user';
    public $nameField = 'username';
    public $beforeSaveEvent = 'OnBeforeUserDuplicate';
    public $afterSaveEvent = 'OnUserDuplicate';

    public function getNewName() {
        $name = $this->getProperty('new_username','');
        $newName = !empty($name) ? $name : $this->object->get('username').'_copy';
        return $newName;
    }

    public function beforeSave() {
        /* copy profile */
        $profile = $this->object->getOne('Profile');
        if ($profile) {
            /** @var modUserProfile $newProfile */
            $newProfile = $this->modx->newObject('modUserProfile');
            $newProfile->fromArray($profile->toArray());
            $this->newObject->addOne($newProfile);
        }

        /* copy user group memberships */
        $memberships = $this->object->getMany('UserGroupMembers');
        $newMemberships = array();
        /** @var modUserGroupMember $membership */
        foreach ($memberships as $membership) {
            /** @var modUserGroupMember $newMembership */
            $newMembership = $this->modx->newObject('modUserGroupMember');
            $newMembership->fromArray($membership->toArray());
            $newMemberships[] = $newMembership;
        }
        $this->newObject->addMany($newMemberships);

        /* copy settings */
        $settings = $this->object->getMany('UserSettings');
        $newSettings = array();
        /** @var modUserSetting $setting */
        foreach ($settings as $setting) {
            /** @var modUserSetting $newSetting */
            $newSetting = $this->modx->newObject('modUserSetting');
            $newSetting->fromArray($setting->toArray());
            $newSetting->set('key',$setting->get('key'));
            $newSettings[] = $newSetting;
        }
        $this->newObject->addMany($newSettings);

        return parent::beforeSave();
    }
}
return 'modUserDuplicateProcessor';