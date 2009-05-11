<?php
/**
 * Represents a group of users with common attributes.
 *
 * @package modx
 */
class modUserGroup extends xPDOSimpleObject {
	function modUserGroup(&$xpdo) {
		$this->__construct($xpdo);
	}
	function __construct(&$xpdo) {
		parent::__construct($xpdo);
	}

	/**
	 * Get all users in a user group.
	 *
	 * @return array An array of users.
	 */
	function getUsersIn() {
		$ugms = $this->xpdo->getCollection('modUserGroupMember',array('user_group' => $this->get('id')));

		$users = array();
		foreach ($ugms as $ugm) {
			if ($user = $ugm->getOne('modUser')) {
				$users[$ugm->member] = $user;
			}
		}
		return $users;
	}

	/**
	 * Get all resource groups related to the user group.
	 *
	 * @return array An array of documents.
	 */
  	function getDocumentsIn() {
		$ugdgs = $this->xpdo->getCollection('modAccessResourceGroup',array(
            'principal' => $this->get('id'),
            'principal_class' => 'modUserGroup'
        ));
		$dgs = array();
		foreach ($ugdgs as $ugdg) {
			if ($dg = $ugdg->getOne('Target')) {
				$dgs[$ugdg->documentgroup] = $dg;
			}
		}
		return $dgs;
	}
}