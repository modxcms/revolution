<?php
/**
 * Sort users and user groups, effectively repositioning users into proper
 * groups
 *
 * @param json $data The encoded tree data
 *
 * @package modx
 * @subpackage processors.security.group
 */
class modUserGroupSortProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission(array('usergroup_save' => true));
    }
    public function getLanguageTopics() {
        return array('user');
    }
    public function process() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->failure($this->modx->lexicon('invalid_data'));
        $data = urldecode($data);
        $data = $this->modx->fromJSON($data);
        if (empty($data)) return $this->failure($this->modx->lexicon('invalid_data'));
        
        $this->sortGroups($data);
        if ($this->modx->hasPermission('usergroup_user_edit')) {
            $this->sortUsers($data);
        }
        return $this->success();
    }

    /**
     * Sort and rearrange any groups in the data
     * @param array $data
     * @return void
     */
    public function sortGroups(array $data) {
        $groups = array();
        $this->getGroupsFormatted($groups,$data);

        /* readjust groups */
        foreach ($groups as $groupArray) {
            if (!empty($groupArray['id'])) {
                /** @var modUserGroup $userGroup */
                $userGroup = $this->modx->getObject('modUserGroup',$groupArray['id']);
                if (empty($userGroup)) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'Could not sort group '.$groupArray['id'].' because it could not be found.');
                    continue;
                }
                $oldParentId = $userGroup->get('parent');
            } else {
                $userGroup = $this->modx->newObject('modUserGroup');
                $oldParentId = 0;
            }

            if ($groupArray['parent'] == $userGroup->get('id')) {
                continue;
            }

            if ($groupArray['parent'] == 0 || $oldParentId != $groupArray['parent']) {
                /* get new parent, if invalid, skip, unless is root */
                if ($groupArray['parent'] != 0) {
                    /** @var modUserGroup $parentUserGroup */
                    $parentUserGroup = $this->modx->getObject('modUserGroup',$groupArray['parent']);
                    if (empty($parentUserGroup)) continue;
                    $depth = $parentUserGroup->get('depth') + 1;
                } else {
                    $depth = 0;
                }

                /* save new parent and depth */
                $userGroup->set('parent',$groupArray['parent']);
                $userGroup->set('depth',$depth);
            }
            if ($groupArray['id'] != 0) {
                $userGroup->save();
            }
        }
    }

    /**
     * Sort and rearrange any users in the data
     * @param array $data
     * @return void
     */
    public function sortUsers(array $data) {
        $users = array();
        $this->getUsersFormatted($users,$data);
        /* readjust users */
        foreach ($users as $userArray) {
            if (empty($userArray['id'])) continue;
            /** @var modUser $user */
            $user = $this->modx->getObject('modUser',$userArray['id']);
            if (empty($user)) continue;

            /* get new parent, if invalid, skip, unless is root */
            if ($userArray['new_group'] != 0 && $userArray['new_group'] != $userArray['old_group']) {
                /** @var modUserGroup $membership */
                $membership = $this->modx->getObject('modUserGroupMember',array(
                    'user_group' => $userArray['new_group'],
                    'member' => $user->get('id'),
                ));
                if (empty($membership)) {
                    $membership = $this->modx->newObject('modUserGroupMember');
                    $membership->set('user_group',$userArray['new_group']);
                }
                $membership->set('member',$user->get('id'));
                if ($membership->save()) {
                    /* remove user from old group */
                    if (!empty($userArray['old_group'])) {
                        /** @var modUserGroup $oldMembership */
                        $oldMembership = $this->modx->getObject('modUserGroupMember',array(
                            'user_group' => $userArray['old_group'],
                            'member' => $user->get('id'),
                        ));
                        if ($oldMembership) {
                            $oldMembership->remove();
                        }
                    }
                }
            }
        }
    }

    protected function getGroupsFormatted(&$ar_nodes,$cur_level,$parent = 0) {
        $order = 0;
        foreach ($cur_level as $id => $children) {
            $id = substr($id,2); /* get rid of CSS id n_ prefix */
            if (substr($id,0,2) == 'ug') {
                $ar_nodes[] = array(
                    'id' => substr($id,3),
                    'parent' => substr($parent,3),
                    'order' => $order,
                );
                $order++;
            }
            $this->getGroupsFormatted($ar_nodes,$children,$id);
        }
    }

    protected function getUsersFormatted(&$ar_nodes,$cur_level,$parent = 0) {
        $order = 0;
        foreach ($cur_level as $id => $children) {
            $id = substr($id,2); /* get rid of CSS id n_ prefix */
            if (substr($id,0,4) == 'user') {
                $userMap = substr($id,5);
                $userMap = explode('_',$userMap);
                $ar_nodes[] = array(
                    'id' => $userMap[0],
                    'old_group' => $userMap[1],
                    'new_group' => substr($parent,3),
                    'order' => $order,
                );
                $order++;
            }
            $this->getUsersFormatted($ar_nodes,$children,$id);
        }
    }

}
return 'modUserGroupSortProcessor';