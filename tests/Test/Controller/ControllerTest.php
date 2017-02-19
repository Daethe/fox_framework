<?php
/**
 * Created by PhpStorm.
 * User: marcp
 * Date: 19/02/2017
 * Time: 19:02
 */

namespace CoreTests\Test\Controller;


use \Core\Controller\Controller;
use \CoreTests\Fixtures\TestController;

class ControllerTest extends \PHPUnit\Framework\TestCase {

	private $classController;
	private $testController;

	public function setUp() {
		$this->classController = new Controller();
		$this->testController = new TestController();
	}

	public function testContructor() {
		$this->assertAttributeEquals([], 'layoutVariables', $this->classController);
		$this->assertAttributeEquals('Core\Controller\Controller', 'controller', $this->classController);

		$this->assertAttributeEquals([], 'layoutVariables', $this->testController);
		$this->assertAttributeEquals('CoreTests\Fixtures\TestController', 'controller', $this->testController);
	}

	public function testGetController() {
		$this->assertEquals('coretests\fixtures\test', $this->testController->getController());
	}

	/**
	 * @dataProvider matcherProvider
	 */
	public function testSetLayoutVariables($expected, $variables) {
		$this->testController->setLayoutVariables($variables);
		$this->assertAttributeEquals($expected, 'layoutVariables', $this->testController);
	}

	public function matcherProvider() {
		return array(
			array(['test0' => ['banana', 'apple']], ['test0' => ['banana', 'apple']]),
			array(['test1' => 'pear'], ['test1' => 'pear']),
			array(['test2' => 'jean'], ['test2' => 'jean'])
		);
	}

}