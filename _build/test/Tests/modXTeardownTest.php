<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 */
namespace MODX\Revolution\Tests;

use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

/**
 * Tests related to cleaning up the test environment.
 *
 * @package modx-test
 * @subpackage modx
 */
class modXTeardownTest extends XTestCase {
    public function testtearDownFixtures() {
        $this->assertTrue(true);
    }
}
