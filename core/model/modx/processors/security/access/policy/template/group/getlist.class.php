<?php
/**
 * Gets a list of policy template groups.
 *
 * @param boolean $combo (optional) If true, will append a 'no policy' row to
 * the beginning.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Default
 *
 * @package modx
 * @subpackage processors.security.access.policy.template.group
 */
class modAccessPolicyTemplateGroupGetListProcessor extends modObjectGetListProcessor {
    public $checkListPermission = false;
    public $objectType = 'permission';
    public $classKey = 'modAccessPolicyTemplateGroup';
    public $permission = 'policy_template_view';
    public $languageTopics = array('policy');

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $objectArray['cls'] = 'pedit';
        return $objectArray;
    }
}
return 'modAccessPolicyTemplateGroupGetListProcessor';