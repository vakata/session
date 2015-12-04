<?php
namespace vakata\session\test;

class SessionDatabaseTest extends \PHPUnit_Framework_TestCase
{
	protected static $db = null;
	protected static $sessionDB = null;

	public static function setUpBeforeClass() {
		self::$db = new \vakata\database\DB('mysqli://root@127.0.0.1/test');
		self::$db->query("
			CREATE TEMPORARY TABLE IF NOT EXISTS test (
				id varchar(255) NOT NULL,
				data varchar(255) NOT NULL,
				created datetime NOT NULL,
				updated datetime NOT NULL,
				PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");
	}
	public static function tearDownAfterClass() {
		self::$db->query("
			DROP TEMPORARY TABLE test;
		");
	}
	protected function setUp() {
		// self::$db->query("TRUNCATE TABLE test;");
	}
	protected function tearDown() {
		// self::$db->query("TRUNCATE TABLE test;");
	}

	public function testInvalidCreate() {
		$this->setExpectedException('\vakata\database\DatabaseException');
		new \vakata\database\DB();
	}

	public function testCreate() {
		self::$sessionDB = new \vakata\session\SessionDatabase(self::$db, 'test');
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
		$this->assertEquals('test', self::$db->one('SELECT data FROM test WHERE id = ?', ['test']));
		$this->assertEquals(true, self::$sessionDB->write('test', 'test2'));
		$this->assertEquals('test2', self::$db->one('SELECT data FROM test WHERE id = ?', ['test']));
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
		$this->assertEquals(true, self::$sessionDB->write('expire1', 'expire1'));
		sleep(2);
		$this->assertEquals(true, self::$sessionDB->write('test', 'expire2'));
		$this->assertEquals(true, self::$sessionDB->gc(1));
		$this->assertEquals(null, self::$db->one('SELECT data FROM test WHERE id = ?', ['expire1']));
		$this->assertEquals('expire2', self::$db->one('SELECT data FROM test WHERE id = ?', ['test']));
	}
	/**
	 * @depends testWrite
	 */
	public function testDestroy() {
		$this->assertEquals(true, self::$sessionDB->destroy('test'));
		$this->assertEquals(null, self::$db->one('SELECT data FROM test WHERE id = ?', ['test']));
	}
}
