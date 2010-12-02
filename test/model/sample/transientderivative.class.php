<?php
require_once(dirname(__FILE__) . '/transient.class.php');
class TransientDerivative extends Transient {
    public static function callTest() {
        return 'TransientDerivative';
    }
}