<?php

/**
 * Test class for ocssoapclient.
 * Generated by PHPUnit.
 */
class ocssoapclientTest extends PHPUnit_Framework_TestCase {
	/**
	 * Runs the test methods of this class.
	 *
	 * @access public
	 * @static
	 *
	 */
	public static function main() {
		require_once 'PHPUnit/TextUI/TestRunner.php';
		
		$suite = new PHPUnit_Framework_TestSuite('ocssoapclientTest');
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp() {
		global $DB, $CFG_GLPI;
		
		$_SERVER['REQUEST_URI'] = '/plugins';
		require_once (__DIR__.'/../../../inc/includes.php');
		
		$config = parse_ini_file(__DIR__.'/../../test_config/soapclient.ini');
		$this->url = $config['url'];
		$this->user = $config['user'];
		$this->pass = $config['pass'];
		
		$this->client = new PluginOcsinventoryngOcsSoapClient(0, $this->url, $this->user, $this->pass);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown() {}

	public function testConnect() {
		$this->assertTrue($this->client->checkConnection());
	}

	public function testFalseLoginConnect() {
		$invalidClient = new PluginOcsinventoryngOcsSoapClient($this->url, 'foo', 'bar');
		$this->assertFalse($invalidClient->checkConnection());
	}

	public function testFalseAddressConnect() {
		$invalidClient = new PluginOcsinventoryngOcsSoapClient('dummy', $this->user, $this->pass);
		$this->assertFalse($invalidClient->checkConnection());
	}

	/**
	 * @depends testConnect
	 */
	public function testGetConfig() {
		$result = $this->client->getConfig('OCS_FILES_PATH');
		$expected = array (
			"IVALUE" => 0,
			"TVALUE" => '/tmp'
		);
		$this->assertEquals($expected, $result);
	}

	/**
	 * @depends testGetConfig
	 */
	public function testSetConfig() {
		$oldconf = $this->client->getConfig('OCS_FILES_PATH');
		$this->client->setConfig('OCS_FILES_PATH', 1, 'test');
		$result = $this->client->getConfig('OCS_FILES_PATH');
		$expected = array (
			"IVALUE" => 1,
			"TVALUE" => 'test'
		);
		
		$this->assertEquals($expected, $result);
		
		// Put old config back
		$this->client->setConfig('OCS_FILES_PATH', $oldconf['IVALUE'], $oldconf['TVALUE']);
		
		$result = $this->client->getConfig('OCS_FILES_PATH');
		$expected = array (
			"IVALUE" => 0,
			"TVALUE" => '/tmp'
		);
		$this->assertEquals($expected, $result);
	}

}
?>