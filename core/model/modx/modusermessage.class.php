<?php
/**
 * Represents a user message.
 *
 * @package modx
 */
class modUserMessage extends xPDOSimpleObject {
    public function getOne($alias, $criteria= null) {
        if (($alias === 'Recipient' || $alias === 'Sender') && $criteria === null) {
            if ($fkMeta= $this->getFKDefinition($alias)) {
                if ($userid= $this->get($fkMeta['local'])) {
                    if ($userid < 0) {
                        $class= 'modWebUser';
                        $userid= abs($userid);
                    }
                    elseif ($userid > 0) {
                        $class= 'modManagerUser';
                    }
                    $userTable= $this->xpdo->getTableName($class);
                    $sql= "SELECT * FROM {$userTable} WHERE `id` = :user_id";
                    $bindings= array(
                        ':user_id' => array ('value' => $userid, 'type' => PDO::PARAM_INT)
                    );
                    $criteria= new xPDOCriteria($this->xpdo, $sql, $bindings, true);
                }
            }
        }
        return parent :: getOne($alias, $criteria);
    }

}