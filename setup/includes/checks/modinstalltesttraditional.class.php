<?php
/**
 * Handles all traditional build-specific checks
 *
 * @package setup
 * @subpackage tests
 */
class modInstallTestTraditional extends modInstallTest {
    function modInstallTestTraditional(&$install) {
        $this->__construct($install);
    }
    function __construct(&$install) {
        $this->install =& $install;
    }

}