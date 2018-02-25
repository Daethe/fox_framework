<?php
/**
 * Created by PhpStorm.
 * User: marcp
 * Date: 19/02/2017
 * Time: 17:38
 */

namespace FoxTests\Core\Test\Web\Router;

use Core\Web\Router\Route;
use Core\Web\Router\RouteCollection;
use Core\Web\Router\Router;

class RouterTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @dataProvider matcherProvider
	 *
	 * @param $router
	 * @param $path
	 * @param $expected
	 */
	public function testMatch($router, $path, $expected) {
		self::assertEquals($expected, (bool)$router->match($path));
	}
	
	public function testMatchWrongMethod() {
		$router = $this->getRouter();
		self::assertFalse($router->match('/users', 'POST'));
	}

	public function testBasePathConfigIsSettedProperly() {
		$router = new Router(new RouteCollection);
		$router->setBasePath('/webroot/');
		self::assertAttributeEquals('/webroot', 'basePath', $router);
	}

	public function testMatchRouterUsingBasePath() {
		$collection = new RouteCollection();
		$collection->attach(new Route('/users/', array(
			'_controller' => '\CoreTests\Fixtures\SomeController::usersCreate',
			'methods' => 'GET'
		)));
		$router = new Router($collection);
		$router->setBasePath('/localhost/webroot');
		foreach ($this->serverProvider() as $server) {
			$_SERVER = $server;
			self::assertTrue((bool)$router->matchCurrentRequest());
		}
	}

	private function serverProvider() {
		return array(
			array(
				'REQUEST_METHOD' => 'GET',
				'REQUEST_URI' => '/localhost/webroot/users/',
				'SCRIPT_NAME' => 'index.php'
			),
			array(
				'REQUEST_METHOD' => 'GET',
				'REQUEST_URI' => '/localhost/webroot/users/?foo=bar&bar=foo',
				'SCRIPT_NAME' => 'index.php'
			),
		);
	}

	public function testParamsWithDynamicFilterMatch() {
		$collection = new RouteCollection();
		$route = new Route(
			'/js/:filename.js',
			array(
				'_controller' => '\CoreTests\Fixtures\SomeController::dynamicFilterUrlMatch',
				'methods' => 'GET',
			)
		);
		$route->setFilters(array(':filename' => '([[:alnum:]\.]+)'), true);
		$collection->attachRoute($route);
		$router = new Router($collection);
		self::assertEquals(
			array(array('filename' => 'someJsFile')),
			$router->match('/js/someJsFile.js')->getParameters()
		);
		self::assertEquals(
			array(array('filename' => 'someJsFile.min')),
			$router->match('/js/someJsFile.min.js')->getParameters()
		);
		self::assertEquals(
			array(array('filename' => 'someJsFile.min.js')),
			$router->match('/js/someJsFile.min.js.js')->getParameters()
		);
	}

	public function testGenerate() {
		$router = $this->getRouter();
		self::assertSame('/users/', $router->generate('users'));
		self::assertSame('/user/123', $router->generate('user', array('id' => 123)));
	}

	/**
	 * @expectedException \Exception
	 */
	public function testGenerateNotExistent() {
		$router = $this->getRouter();
		self::assertSame('/notExists/', $router->generate('notThisRoute'));
	}

	/**
	 * @return Router
	 */
	private function getRouter() {
		$collection = new RouteCollection();
		$collection->attachRoute(new Route('/users/', array(
			'_controller' => '\CoreTests\Fixtures\SomeController::usersCreate',
			'methods' => 'GET',
			'name' => 'users'
		)));
		$collection->attachRoute(new Route('/user/:id', array(
			'_controller' => '\CoreTests\Fixtures\SomeController::user',
			'methods' => 'GET',
			'name' => 'user',
			'filters' => ['id' => '([\w-%]+)']
		)));
		$collection->attachRoute(new Route('/', array(
			'_controller' => '\CoreTests\Fixtures\SomeController::indexAction',
			'methods' => 'GET',
			'name' => 'index'
		)));
		return new Router($collection);
	}

	/**
	 * @return mixed[][]
	 */
	public function matcherProvider1() {
		$router = $this->getRouter();
		return array(
			array($router, '', true),
			array($router, '/', true),
			array($router, '/aaa', false),
			array($router, '/users', true),
			array($router, '/usersssss', false),
			array($router, '/user/1', true),
			array($router, '/user/%E3%81%82', true),
		);
	}

	/**
	 * @return mixed[][]
	 */
	public function matcherProvider2() {
		$router = $this->getRouter();
		$router->setBasePath('/api');
		return array(
			array($router, '', false),
			array($router, '/', false),
			array($router, '/aaa', false),
			array($router, '/users', false),
			array($router, '/user/1', false),
			array($router, '/user/%E3%81%82', false),
			array($router, '/api', true),
			array($router, '/api/aaa', false),
			array($router, '/api/users', true),
			array($router, '/api/userssss', false),
			array($router, '/api/user/1', true),
			array($router, '/api/user/%E3%81%82', true),
		);
	}

	/**
	 * @return string[]
	 */
	public function matcherProvider() {
		return array_merge($this->matcherProvider1(), $this->matcherProvider2());
	}

}