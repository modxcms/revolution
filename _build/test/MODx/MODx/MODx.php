<?php
/**
 * Copyright 2010 by MODx, LLC.
 *
 * @package modx-test
 */
/**
 * Tests related to basic MODx class methods
 *
 * @package modx-test
 * @subpackage modx
 */
class MODxTest extends MODxTestCase {
    public function testPDOExtension() {
        $exists = extension_loaded('pdo');
        $this->assertTrue($exists);
    }

    public function testVerifyMODx() {
        $modx = MODxTestHarness::_getConnection();
        $success = is_object($modx) && $modx instanceof modX;
        $this->assertTrue($success);
    }
}