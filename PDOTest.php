<?php 
require_once 'PHPUnit2/Framework/TestCase.php';

$_nativeMode= true;
if (!class_exists('PDO')) {
   include_once(strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../xpdo/pdo.class.php');
   $_nativeMode= false;
}

class PDOTest extends PHPUnit2_Framework_TestCase {
	protected $pdo;

	protected function setUp() {
		$this->pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '3rg0t71');
	}

	public function testDBConnection() {
		// Should be no errors if we connected successfuly
		$this->assertTrue(strlen($this->pdo->errorCode()) == 0);
	}

	public function testPDOConnectionError() {
		$string_dsn= 'mysql:host= nosuchhost;dbname=nosuchdb';
		$mypdo = new PDO($string_dsn, "nonesuchuser", "nonesuchpass");
		// Should be an error set since we gave bogus info
		$this->assertTrue(strlen($mypdo->errorCode()) > 0, "Error code not being set on PDO object");
	}

}
