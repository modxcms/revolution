<?php
/**
 * @package modx
 */
class modResourceGroup extends modAccessibleSimpleObject {
    function modResourceGroup(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    function getResources() {
        $c= $this->xpdo->newQuery('modResource');
        $c->innerJoin('modResourceGroupResource', 'dgd', array (
            '`modResource`.`id` = `dgd`.`document`',
        ));
        $c->where(array ('dgd.document_group' => $this->get('id')));
        $collection= $this->xpdo->getCollection('modResource', $c);
        return $collection;
    }

    function getUserGroups() {
        $access= $this->xpdo->getCollection('modAccessResourceGroup', array (
            'target' => $this->get('id'),
            'principal_class' => 'modUserGroup',
        ));
        $groups= array();
        foreach ($access as $arg) {
            $groups[$arg->get('membergroup')]= $arg->getOne('Target');
        }
        return $groups;
    }
}