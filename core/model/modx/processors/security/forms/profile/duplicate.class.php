<?php
/**
 * Duplicate a FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
class modFormCustomizationProfileDuplicateProcessor extends modObjectDuplicateProcessor {
    public $classKey = 'modFormCustomizationProfile';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';
    public $objectType = 'profile';
    public $checkSavePermission = false;

    public function beforeSave() {
        $this->newObject->set('active',false);

        return parent::beforeSave();
    }

    public function afterSave() {
        $this->duplicateUserGroupAccess();
        $this->duplicateSets();

        return parent::afterSave();
    }

    /**
     * Duplicate the user group access on the old profile
     * @return void
     */
    public function duplicateUserGroupAccess() {
        $profileUserGroups = $this->modx->getCollection('modFormCustomizationProfileUserGroup',array(
            'profile' => $this->object->get('id'),
        ));
        /** @var modFormCustomizationProfileUserGroup $profileUserGroup */
        foreach ($profileUserGroups as $profileUserGroup) {
            /** @var modFormCustomizationProfileUserGroup $newProfileUserGroup */
            $newProfileUserGroup = $this->modx->newObject('modFormCustomizationProfileUserGroup');
            $newProfileUserGroup->set('usergroup',$profileUserGroup->get('usergroup'));
            $newProfileUserGroup->set('profile',$this->newObject->get('id'));
            $newProfileUserGroup->save();
        }
    }

    /**
     * Duplicate all the Sets of the old Profile
     * 
     * @return void
     */
    public function duplicateSets() {
        $sets = $this->object->getMany('Sets');
        /** @var modFormCustomizationSet $set */
        foreach ($sets as $set) {
            /** @var modFormCustomizationSet $newSet */
            $newSet = $this->modx->newObject('modFormCustomizationSet');
            $newSet->fromArray($set->toArray());
            $newSet->set('profile',$this->newObject->get('id'));
            $newSet->save();

            $rules = $set->getMany('Rules');
            /** @var modActionDom $rule */
            foreach ($rules as $rule) {
                /** @var modActionDom $newRule */
                $newRule = $this->modx->newObject('modActionDom');
                $newRule->fromArray($rule->toArray());
                $newRule->set('set',$newSet->get('id'));
                $newRule->save();
            }
        }
    }
}
return 'modFormCustomizationProfileDuplicateProcessor';