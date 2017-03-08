<?php
/**
 * Created by PhpStorm.
 * User: marcp
 * Date: 08/03/2017
 * Time: 19:06
 */

namespace CoreTests\Test\Assets;


use Core\Assets\AssetsCSS;
use Core\Assets\AssetsJS;
use Core\Assets\AssetsManager;

class AssetsManagerTest extends \PHPUnit\Framework\TestCase {

	private $testInstance;

	public function setUp() {
		$this->testInstance = new AssetsManager(TEST_FIXTURES . 'assets/css', TEST_FIXTURES . 'assets/js');
	}

	public function testCSSInstance() {
		$this->assertInstanceOf(AssetsCSS::class, $this->testInstance->CSS());
	}

	public function testJSInstance() {
		$this->assertInstanceOf(AssetsJS::class, $this->testInstance->JS());
	}

}