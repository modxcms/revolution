<?php
require_once (dirname(dirname(__FILE__)) . '/item.class.php');
class Item_sqlite extends Item {
    public static function callTest() {
        return 'Item_sqlite';
    }
}