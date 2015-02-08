<?php
include_once dirname(__FILE__).'/getlist.class.php';
/**
 * Gets a list of roles
 *
 * @param boolean $addNone If true, will add a role of None
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to authority.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.role
 */

class modUserGroupRoleGetAuthorityListProcessor extends modUserGroupRoleGetListProcessor {
    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $objectArray['name'] = $object->get('name').' - '.$object->get('authority');
        $objectArray['id'] = $object->get('authority');

        return $objectArray;
    }
}

return 'modUserGroupRoleGetAuthorityListProcessor';
