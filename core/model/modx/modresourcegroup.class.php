<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modResourceGroup extends modAccessibleSimpleObject {
    function modResourceGroup(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    function getDocumentsIn() {
        $criteria= $this->xpdo->newQuery('modResource');
        $criteria->innerJoin('modResourceGroupResource', 'dgd', array (
            '`modResource`.`id` = `dgd`.`document`',
        ));
        $criteria->where(array ('dgd.document_group' => $this->id));
        $collection= $this->xpdo->getCollection('modResource', $criteria);
        return $collection;
    }

    function getUserGroupsIn() {
        $ugdgs= $this->xpdo->getCollection('modAccessResourceGroup', array (
            'target' => $this->id,
            'principal_class' => 'modUserGroup',
        ));
        $dgs= array ();
        foreach ($ugdgs as $ugdg) {
            $dgs[$ugdg->membergroup]= $ugdg->getOne('modUserGroup');
        }
        return $dgs;
    }
}
?>