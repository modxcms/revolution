<?php
/**
 * @deprecated 2.0.0-alpha-6 - Nov 1, 2008
 * @package modx
 * @subpackage mysql
 */
class modLexiconFocus extends modLexiconTopic {
    function modLexiconFocus(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>