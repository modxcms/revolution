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

use PHPUnit\Framework\TestCase;

/**
 * Tests related to verifying and setting up the test environment.
 *
 * @package modx-test
 * @subpackage modx
 */
class modXSetupTest extends TestCase {
    /**
     * Test that the PDO extension is available and loaded.
     */
    public function testPDOExtension() {
        $exists = extension_loaded('pdo');
        $this->assertTrue($exists, "Required PDO extension is not loaded.");
    }
}
