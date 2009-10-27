<?php
/**
 * Defines the legacy modWebUser class to represent web users.
 * @deprecated 2007-09-19 For migration purposes only.
 * @package modx
 */

/**
 * Include the modUser class so it doesn't have to be manually loaded.
 */
include_once MODX_CORE_PATH . 'model/modx/moduser.class.php';

/**
 * Represents the web_users table that is no longer used in MODx.
 *
 * This class exists only for migration purposes.
 *
 * @deprecated 2007-09-19 For migration purposes only.
 * @package modx
 */
class modWebUser extends modUser {
    public function getOne($class, $criteria= null) {
        if ($criteria === null && $class === 'modActiveUser') {
            if ($userid= $this->get('id')) {
                $userid= $userid * -1;
                $activeUserTable= $this->xpdo->getTableName('modActiveUser');
                $sql= "SELECT * FROM {$activeUserTable} WHERE `id` = :user_id LIMIT 1";
                $bindings= array(
                    ':user_id' => array ('value' => $userid, 'type' => PDO::PARAM_INT)
                );
                $criteria= new xPDOCriteria($this->xpdo, $sql, $bindings, true);
            }
        }
        return parent :: getOne($class, $criteria);
    }
}