<?php
/**
 * Created by PhpStorm.
 * User: marcp
 * Date: 19/02/2017
 * Time: 18:43
 */

namespace FoxTests\Core\Test\Web\Router;


use Core\Web\Router\Route;

class RouteTest extends \PHPUnit\Framework\TestCase {

	private $route;

	public function setUp() {
		$this->route = new Route('/index', [
			'_controller' => '\CoreTests\Fixtures\SomeController::index',
			'methods' => 'GET',
			'target' => 'testtarget',
			'name' => 'index'
		]);
	}

	public function testGetTarget() {
		$this->assertEquals('testtarget', $this->route->getTarget());
	}

	public function testSetMethods() {
		$this->route->setMethods(['SomeMethodsGet', 'SomeMethodsPost']);
		$this->assertAttributeSame(['SomeMethodsGet', 'SomeMethodsPost'], 'methods', $this->route);
	}

	public function testSetName() {
		$this->route->setName('testName');
		$this->assertAttributeEquals('testName', 'name', $this->route);
	}

	public function testSetTarget() {
		$this->route->setTarget('anothertarget');
		$this->assertAttributeEquals('anothertarget', 'target', $this->route);
	}

	public function testSetUrl() {
		$this->route->setUrl('testindex');
		$this->assertAttributeEquals('testindex/', 'url', $this->route);

		$this->route->setUrl('/anothertestindex');
		$this->assertAttributeEquals('/anothertestindex/', 'url', $this->route);
	}

}