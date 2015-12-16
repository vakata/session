<?php
namespace vakata\session\test;

class SessionCacheTest extends \PHPUnit_Framework_TestCase
{
	protected static $db = null;
	protected static $sessionDB = null;

	public static function setUpBeforeClass() {
		self::$db = new \vakata\cache\Memcache();
	}
	public static function tearDownAfterClass() {
	}
	protected function setUp() {
		// self::$db->query("TRUNCATE TABLE test;");
	}
	protected function tearDown() {
		// self::$db->query("TRUNCATE TABLE test;");
	}

	public function testCreate() {
		self::$sessionDB = new \vakata\session\handler\SessionCache(self::$db, 'test');
		$this->assertEquals(true, self::$sessionDB instanceof \SessionHandlerInterface);
	}
	/**
	 * @depends testCreate
	 */
	public function testOpenClose() {
		$this->assertEquals(true, self::$sessionDB->open('test', 'test'));
		$this->assertEquals(true, self::$sessionDB->close('test'));
	}
	public function testWrite() {
		$this->assertEquals(true, self::$sessionDB->write('test', 'test'));
		$this->assertEquals('test', self::$db->get('test', 'test'));
		$this->assertEquals(true, self::$sessionDB->write('test', 'test2'));
		$this->assertEquals('test2', self::$db->get('test', 'test'));
	}
	/**
	 * @depends testWrite
	 */
	public function testRead() {
		$this->assertEquals('test2', self::$sessionDB->read('test'));
		$this->assertEquals('', self::$sessionDB->read('test-not-existing'));
	}

	/**
	 * @depends testWrite
	 */
	public function testGc() {
		$this->assertEquals(true, self::$sessionDB->gc(1));
	}
	/**
	 * @depends testWrite
	 */
	public function testDestroy() {
		$this->assertEquals(true, self::$sessionDB->destroy('test'));
		$this->setExpectedException('\vakata\cache\CacheException');
		$this->assertEquals(null, self::$db->get('test', 'test'));
	}
}
