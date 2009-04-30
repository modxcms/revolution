<?php
/**
 * @package modx
 * @subpackage mysql
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../modchunk.class.php');
class modChunk_mysql extends modChunk {
    function modChunk_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}