<?php
/**
 * @package modx
 * @subpackage mysql
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/modlexicontopic.class.php');
class modLexiconTopic_mysql extends modLexiconTopic {
    function modLexiconTopic_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>