<?php
/**
 * Represents a group of users with common attributes.
 *
 * @package modx
 */
class modUserGroup extends xPDOSimpleObject {
    /**
     * Overrides xPDOObject::save to fire modX-specific events.
     *
     * {@inheritDoc}
     */
    public function save($cacheFlag= null) {
        $isNew = $this->isNew();

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnUserGroupBeforeSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'usergroup' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }

        $saved = parent :: save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnUserGroupSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'usergroup' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }
        return $saved;
    }
    /**
     * Overrides xPDOObject::remove to fire modX-specific events
     *
     * {@inheritDoc}
     */
     public function remove(array $ancestors= array ()) {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnUserGroupBeforeRemove',array(
                'usergroup' => &$this,
                'ancestors' => $ancestors,
            ));
        }

        $removed = parent :: remove($ancestors);

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnUserGroupRemove',array(
                'usergroup' => &$this,
                'ancestors' => $ancestors,
            ));
        }

        return $removed;
     }


    /**
     * Get all users in a user group.
     *
     * @access public
     * @return array An array of {@link modUser} objects.
     */
    public function getUsersIn(array $criteria = array()) {
        $c = $this->xpdo->newQuery('modUser');
        $c->select('
            `modUser`.*,
            `UserGroupRole`.`name` AS `role`,
            `UserGroupRole`.`name` AS `role_name`
        ');
        $c->innerJoin('modUserGroupMember','UserGroupMembers');
        $c->leftJoin('modUserGroupRole','UserGroupRole','`UserGroupMembers`.`role` = `UserGroupRole`.`id`');
        $c->where(array(
            'UserGroupMembers.user_group' => $this->get('id'),
        ));

        $sort = !empty($criteria['sort']) ? $criteria['sort'] : '`modUser`.`username`';
        $dir = !empty($criteria['dir']) ? $criteria['dir'] : 'DESC';
        $c->sortby($sort,$dir);

        if (isset($criteria['limit'])) {
            $start = !empty($criteria['start']) ? $criteria['start'] : 0;
            $c->limit($criteria['limit'],$start);
        }
        return $this->xpdo->getCollection('modUser',$c);
    }

    /**
     * Get all resource groups related to the user group.
     *
     * @access public
     * @param $limit The number of Resource Groups to grab. Defaults to 0, which
     * grabs all Groups.
     * @param $start The starting index for the limit query.
     * @return array An array of resource groups.
     */
    public function getResourceGroups($limit = false,$start = 0) {
        $c = $this->xpdo->newQuery('modResourceGroup');
        $c->innerJoin('modAccessResourceGroup','Acls',array(
            'Acls.principal_class' => 'modUserGroup',
            'Acls.principal' => $this->get('id'),
        ));
        if ($limit) $c->limit($limit,$start);
        return $this->xpdo->getCollection('modResourceGroup',$c);
    }
}