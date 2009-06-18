<?php
/**
 * Represents a group of users with common attributes.
 *
 * @package modx
 */
class modUserGroup extends xPDOSimpleObject {
    /**#@+
     * Creates a modUserGroup instance.
     *
     * {@inheritdoc}
     */
	function modUserGroup(&$xpdo) {
		$this->__construct($xpdo);
	}
    /** @ignore */
	function __construct(&$xpdo) {
		parent::__construct($xpdo);
	}
    /**#@-*/

	/**
	 * Get all users in a user group.
	 *
     * @access public
	 * @return array An array of {@link modUser} objects.
	 */
	function getUsersIn() {
        $c = $this->xpdo->newQuery('modUser');
        $c->innerJoin('modUserGroupMember','modUserGroupMember');
        $c->where(array(
            'modUserGroupMember.user_group' => $this->get('id'),
        ));
        $users = $this->xpdo->getCollection('modUser',$c);
        return $users;
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
  	function getResourceGroups($limit = false,$start = 0) {
        $c = $this->xpdo->newQuery('modResourceGroup');
        $c->innerJoin('modAccessResourceGroup','Acls',array(
            'Acls.principal_class' => 'modUserGroup',
            'Acls.principal' => $this->get('id'),
        ));
        if ($limit) $c->limit($limit,$start);
        return $this->xpdo->getCollection('modResourceGroup',$c);
	}
}