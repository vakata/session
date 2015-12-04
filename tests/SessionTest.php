<?php
namespace vakata\session\test;

class SessionTest extends \PHPUnit_Framework_TestCase
{
	protected static $session = null;

	public static function setUpBeforeClass() {
		if (!isset($_SESSION)) {
			$_SESSION = [];
		}
	}
	public static function tearDownAfterClass() {
	}
	protected function setUp() {
	}
	protected function tearDown() {
	}

	public function testCreate() {
		self::$session = new \vakata\session\Session(false);
		$this->assertEquals(null, self::$session->get('initial'));
	}
	/**
	 * @depends testCreate
	 */
	public function testSet() {
		$this->assertEquals(2, self::$session->set('initial', 2));
		$this->assertEquals(2, self::$session->get('initial'));
		$this->assertEquals(2, $_SESSION['initial']);
	}
	/**
	 * @depends testSet
	 */
	public function testGet() {
		$_SESSION['test'] = 1;
		$this->assertEquals(1, self::$session->get('test'));
	}
	/**
	 * @depends testSet
	 */
	public function testDel() {
		$this->assertEquals(true, self::$session->del('initial'));
		$this->assertEquals(null, self::$session->get('initial'));
		unset($_SESSION['test']);
		$this->assertEquals(null, self::$session->get('test'));
	}
}
