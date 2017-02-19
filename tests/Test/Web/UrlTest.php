<?php
/**
 * Created by PhpStorm.
 * User: marcp
 * Date: 19/02/2017
 * Time: 16:35
 */

namespace CoreTests\Test\Web;


use Core\Web\Server\Server;
use Core\Web\Url;

/**
 * @backupStaticAttributes disabled
 */
class UrlTest extends \PHPUnit\Framework\TestCase {

	public function tearDown() {
		Url::tearDown();
	}

	/**
	 * Test the `getHostInfo()` method with HTTP and a HTTP Host
	 */
	public function testGetHostInfoWithHttpHost() {
		$this->assertEquals('http://localhost', Url::getHostInfo());
	}

	/**
	 * Test the `getHostInfo()` method with HTTP and a ServerName
	 */
	public function testGetHostInfoWithServerName() {
		$this->setupServerName();
		$this->assertEquals('http://servername', Url::getHostInfo());
	}

	/**
	 * Test the `getHostInfo()` method with HTTP, a ServerName and a Port
	 */
	public function testGetHostInfoWithServerNameAndPort() {
		$this->setupServerName();
		Url::$_server->set('SERVER_PORT', 81);
		$this->assertEquals('http://servername:81', Url::getHostInfo());
	}

	/**
	 * Test the `getHostInfo()` method with HTTPS, ServerName and a Secure Port
	 */
	public function testGetHostInfoWithServerNameAndSecurePort() {
		$this->setupServerName();
		Url::$_server->set('SERVER_PORT', 444);
		Url::$_server->set('HTTPS', 1);
		$this->assertEquals('https://servername:444', Url::getHostInfo());
	}

	/**
	 * Setup the server variable for Server Name use
	 */
	private function setupServerName() {
		Url::$_server = new Server();
		Url::$_server->set('HTTP_HOST', null);
		Url::$_server->set('SERVER_NAME', 'servername');
	}

}