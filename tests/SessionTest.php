<?php
namespace vakata\session\test;

class SessionTest extends \PHPUnit\Framework\TestCase
{
	protected static $session = null;

	public static function setUpBeforeClass(): void {
		if (!isset($_SESSION)) {
			$_SESSION = [];
		}
	}

	public function testCreate() {
		self::$session = new \vakata\session\Session(new \vakata\session\handler\SessionCache(new \vakata\cache\Redis(), 'test'));
		$this->assertEquals(null, self::$session->get('initial'));
	}
	/**
	 * @depends testCreate
	 */
	public function testSet() {
		$this->assertEquals(2, self::$session->set('initial', 2));
		$this->assertEquals(2, self::$session->get('initial'));
	}
	/**
	 * @depends testSet
	 */
	public function testDel() {
		$this->assertEquals(true, self::$session->del('initial'));
		$this->assertEquals(null, self::$session->get('initial'));
	}
}
