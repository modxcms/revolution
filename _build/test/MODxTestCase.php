<?php
require_once 'PHPUnit/Framework.php';

/**
 * Extends the basic PHPUnit TestCase class to provide MODx specific methods
 *
 * @package modx-test
 */
class MODxTestCase extends PHPUnit_Framework_TestCase {

    protected $modx = null;

    public function setUp() {
        
    }

    public function tearDown() {
        $this->modx = null;
    }    
}