<?php
/**
 * Handles all SDK build-specific checks
 *
 * @package setup
 * @subpackage tests
 */
class modInstallTestSdk extends modInstallTest {
    function modInstallTestSdk(&$install) {
        $this->__construct($install);
    }
    function __construct(&$install) {
        $this->install =& $install;
    }

}