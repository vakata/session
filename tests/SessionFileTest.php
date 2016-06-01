<?php
namespace vakata\session\test;

class SessionFileTest extends \PHPUnit_Framework_TestCase
{
	protected static $dir = null;
	protected static $sessionDB = null;

	public static function setUpBeforeClass() {
		self::$dir = __DIR__ . '/data';
		if (!is_dir(self::$dir)) {
			mkdir(self::$dir);
		}
	}
	public static function tearDownAfterClass() {
		foreach (scandir(self::$dir) as $file) {
			if (is_file(self::$dir . DIRECTORY_SEPARATOR . $file)) {
				unlink(self::$dir . DIRECTORY_SEPARATOR . $file);
			}
		}
		rmdir(self::$dir);
	}
	protected function setUp() {
		// self::$db->query("TRUNCATE TABLE test;");
	}
	protected function tearDown() {
		// self::$db->query("TRUNCATE TABLE test;");
	}

	public function testCreate() {
		self::$sessionDB = new \vakata\session\handler\SessionFile(self::$dir);
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
		$this->assertEquals('test', file_get_contents(self::$dir . DIRECTORY_SEPARATOR . 'test'));
		$this->assertEquals(true, self::$sessionDB->write('test', 'test2'));
		$this->assertEquals('test2', file_get_contents(self::$dir . DIRECTORY_SEPARATOR . 'test'));
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
		$this->assertEquals(false, file_exists(self::$dir . DIRECTORY_SEPARATOR . 'test'));
	}
}
