<?php
require_once (dirname(dirname(__FILE__)) . '/item.class.php');
class Item_sqlsrv extends Item {
    public static function callTest() {
        return 'Item_sqlsrv';
    }
}