<?php
/**
* This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package modx-test
 */

/**
 * Tests related to verifying and setting up the test environment.
 *
 * @package modx-test
 * @subpackage modx
 */
class modXSetupTest extends \PHPUnit\Framework\TestCase {
    /**
     * Test that the PDO extension is available and loaded.
     */
    public function testPDOExtension() {
        $exists = extension_loaded('pdo');
        $this->assertTrue($exists, "Required PDO extension is not loaded.");
    }
}
