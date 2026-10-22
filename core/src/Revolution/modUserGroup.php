<?php

namespace MODX\Revolution;

/**
 * Represents a group of users with common attributes.
 *
 * @property string                                 $name        The name of the User Group
 * @property string                                 $description A user-specified description of the User Group
 * @property integer                                $parent      The parent group for this User Group. If none, will be 0
 * @property integer                                $rank        The rank of this group, used when sorting the groups
 * @property integer                                $dashboard
 *
 * @property modUserGroupMember[]                   $UserGroupMembers
 * @property modFormCustomizationProfileUserGroup[] $FormCustomizationProfiles
 *
 * @package MODX\Revolution
 */
class modUserGroup extends modPrincipal
{
    /**
     * Overrides xPDOObject::save to fire modX-specific events.
     *
     * {@inheritDoc}
     */
    public function save($cacheFlag = null)
    {
        $isNew = $this->isNew();

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnUserGroupBeforeSave', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'usergroup' => &$this,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        $saved = parent:: save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnUserGroupSave', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'usergroup' => &$this,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        return $saved;
    }

    /**
     * Overrides xPDOObject::remove to fire modX-specific events
     *
     * {@inheritDoc}
     */
    public function remove(array $ancestors = [])
    {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnUserGroupBeforeRemove', [
                'usergroup' => &$this,
                'ancestors' => $ancestors,
            ]);
        }

        if (!$this->removePrincipalAcls()) {
            return false;
        }

        $removed = parent:: remove($ancestors);

        if ($this->xpdo instanceof modX) {
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
        $c = $this->xpdo->newQuery(modUser::class);
        $c->select($this->xpdo->getSelectColumns(modUser::class, 'modUser'));
        $c->select([
            'role' => 'UserGroupRole.name',
            'role_name' => 'UserGroupRole.name',
        ]);
        $c->innerJoin(modUserGroupMember::class, 'UserGroupMembers');
        $c->leftJoin(modUserGroupRole::class, 'UserGroupRole', 'UserGroupMembers.role = UserGroupRole.id');
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

        return $this->xpdo->getCollection(modUser::class, $c);
    }

    /**
     * Get all resource groups related to the user group.
     *
     * @access public
     *
     * @param boolean $limit The number of Resource Groups to grab. Defaults to 0, which
     *                       grabs all Groups.
     * @param int     $start The starting index for the limit query.
     *
     * @return array An array of resource groups.
     */
    public function getResourceGroups($limit = false, $start = 0)
    {
        $c = $this->xpdo->newQuery(modResourceGroup::class);
        $c->innerJoin(modAccessResourceGroup::class, 'Acls', [
            'Acls.principal_class' => modUserGroup::class,
            'Acls.principal' => $this->get('id'),
        ]);
        if ($limit) {
            $c->limit($limit, $start);
        }

        return $this->xpdo->getCollection(modResourceGroup::class, $c);
    }
}
