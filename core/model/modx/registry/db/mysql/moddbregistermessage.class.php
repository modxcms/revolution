<?php
/**
 * @package modx
 * @subpackage registry.db.mysql
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/moddbregistermessage.class.php');
/**
 * @package modx
 * @subpackage mysql
 */
class modDbRegisterMessage_mysql extends modDbRegisterMessage {
    public static function getValidMessages(modDbRegister &$register, $topic, $topicBase, $topicMsg, $limit, array $options = array()) {
        $messages = array();
        $fetchMode = isset($options['fetchMode']) ? $options['fetchMode'] : PDO::FETCH_OBJ;
        $msgTable = $register->modx->getTableName('registry.db.modDbRegisterMessage');
        $topicTable = $register->modx->getTableName('registry.db.modDbRegisterTopic');
        $limitClause = $limit > 0 ? "LIMIT {$limit}" : '';
        $query = new xPDOCriteria(
            $register->modx,
            "SELECT msg.* FROM {$msgTable} msg JOIN {$topicTable} topic ON msg.valid <= :now AND (topic.name = :topic OR (topic.name = :topicbase AND msg.id = :topicmsg)) AND topic.id = msg.topic ORDER BY msg.created ASC {$limitClause}",
            array(
                ':now' => strftime('%Y-%m-%d %H:%M:%S'),
                ':topic' => $topic,
                ':topicbase' => $topicBase,
                ':topicmsg' => $topicMsg
            )
        );
        if ($query->stmt && $query->stmt->execute()) {
            $messages = $query->stmt->fetchAll($fetchMode);
        }
        return $messages;
    }
}
