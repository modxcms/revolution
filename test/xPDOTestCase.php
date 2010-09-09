<?php
/**
 * @package xpdo-test
 * @subpackage harness
 */
require_once 'PHPUnit/Framework.php';

/**
 * Extends the basic PHPUnit TestCase class to provide xPDO specific methods
 *
 * @package xpdo-test
 * @subpackage harness
 */
class xPDOTestCase extends PHPUnit_Framework_TestCase {

    protected $xpdo = null;

    public function setUp() {
        
    }

    public function tearDown() {
        $this->xpdo = null;
    }    
}