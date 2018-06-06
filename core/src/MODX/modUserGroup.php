<?php

namespace MODX;

use xPDO\Om\xPDOSimpleObject;
/**
 * Represents a group of users with common attributes.
 *
 * @property string $name The name of the User Group
 * @property string $description A user-specified description of the User Group
 * @property int $parent The parent group for this User Group. If none, will be 0
 * @property int $rank The rank of this group, used when sorting the groups
 *
 * @see modUser
 * @see modUserGroupRole
 * @see modUserGroupMember
 * @package modx
 */
class modUserGroup extends xPDOSimpleObject
{

    /**
     * Overrides xPDOObject::save to fire MODX-specific events.
     *
     * {@inheritDoc}
     */
    public function save($cacheFlag = null)
    {
        $isNew = $this->isNew();

        if ($this->xpdo instanceof MODX) {
            $this->xpdo->invokeEvent('OnUserGroupBeforeSave', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'usergroup' => &$this,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        $saved = parent:: save($cacheFlag);

        if ($saved && $this->xpdo instanceof MODX) {
            $this->xpdo->invokeEvent('OnUserGroupSave', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'usergroup' => &$this,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        return $saved;
    }


    /**
     * Overrides xPDOObject::remove to fire MODX-specific events
     *
     * {@inheritDoc}
     */
    public function remove(array $ancestors = [])
    {
        if ($this->xpdo instanceof MODX) {
            $this->xpdo->invokeEvent('OnUserGroupBeforeRemove', [
                'usergroup' => &$this,
                'ancestors' => $ancestors,
            ]);
        }

        $removed = parent:: remove($ancestors);

        // delete ACLs for this group
        $targets = explode(',', $this->xpdo->getOption('principal_targets', null, 'modAccessContext,modAccessResourceGroup,modAccessCategory'));
        array_walk($targets, 'trim');
        foreach ($targets as $target) {
            $fields = $this->xpdo->getFields($target);
            if (array_key_exists('principal_class', $fields) && array_key_exists('principal', $fields)) {
                $tablename = $this->xpdo->getTableName($target);
                $principal_class_field = $this->xpdo->escape('principal_class');
                $principal_field = $this->xpdo->escape('principal');
                if (!empty($tablename)) {
                    $this->xpdo->query("DELETE FROM {$tablename} WHERE {$principal_class_field} = 'modUserGroup' AND {$principal_field} = {$this->_fields['id']}");
                }
            }
        }

        if ($this->xpdo instanceof MODX) {
            $this->xpdo->invokeEvent('OnUserGroupRemove', [
                'usergroup' => &$this,
                'ancestors' => $ancestors,
            ]);
        }

        return $removed;
    }


    /**
     * Get all users in a user group.
     *
     * @access public
     *
     * @param array $criteria
     *
     * @return array An array of {@link modUser} objects.
     */
    public function getUsersIn(array $criteria = [])
    {
        $c = $this->xpdo->newQuery('modUser');
        $c->select($this->xpdo->getSelectColumns('modUser', 'modUser'));
        $c->select([
            'role' => 'UserGroupRole.name',
            'role_name' => 'UserGroupRole.name',
        ]);
        $c->innerJoin('modUserGroupMember', 'UserGroupMembers');
        $c->leftJoin('modUserGroupRole', 'UserGroupRole', 'UserGroupMembers.role = UserGroupRole.id');
        $c->where([
            'UserGroupMembers.user_group' => $this->get('id'),
        ]);

        $sort = !empty($criteria['sort']) ? $criteria['sort'] : 'modUser.username';
        $dir = !empty($criteria['dir']) ? $criteria['dir'] : 'DESC';
        $c->sortby($sort, $dir);

        if (isset($criteria['limit'])) {
            $start = !empty($criteria['start']) ? $criteria['start'] : 0;
            $c->limit($criteria['limit'], $start);
        }

        return $this->xpdo->getCollection('modUser', $c);
    }


    /**
     * Get all resource groups related to the user group.
     *
     * @access public
     *
     * @param boolean $limit The number of Resource Groups to grab. Defaults to 0, which
     * grabs all Groups.
     * @param int $start The starting index for the limit query.
     *
     * @return array An array of resource groups.
     */
    public function getResourceGroups($limit = false, $start = 0)
    {
        $c = $this->xpdo->newQuery('modResourceGroup');
        $c->innerJoin('modAccessResourceGroup', 'Acls', [
            'Acls.principal_class' => 'modUserGroup',
            'Acls.principal' => $this->get('id'),
        ]);
        if ($limit) $c->limit($limit, $start);

        return $this->xpdo->getCollection('modResourceGroup', $c);
    }
}
