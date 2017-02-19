<?php
/**
 * Created by PhpStorm.
 * User: marcp
 * Date: 19/02/2017
 * Time: 01:50
 */

namespace CoreTests\Test\Assets;

use Core\Assets\Assets;

class AssetsTest extends \PHPUnit\Framework\TestCase {

	private $assetsTestInstance;

	protected function setUp() {
		$this->assetsTestInstance = new Assets();

		$this->assetsTestInstance->registerCssFile('test.css');
		$this->assetsTestInstance->registerJsFile('test.js');

		$this->assetsTestInstance->registerCss('html{height:100%}');
		$this->assetsTestInstance->registerJs('alert("test");');
	}

	public function testDumpWithCorrectType_Css() {
		$this->assertEquals(
			'<link rel="stylesheet" href="/' . Assets::$assetsPath . 'css/test.css"><style>html{height:100%}</style>',
			$this->assetsTestInstance->dump('css')
		);
	}

	public function testDumpWithCorrectType_Js() {
		$this->assertEquals(
			'<script src="/' . Assets::$assetsPath . 'js/test.js"></script><script>alert("test");</script>',
			$this->assetsTestInstance->dump('js')
		);
	}

	public function testDumpWithIncorrectType() {
		$this->assertEquals(null, $this->assetsTestInstance->dump());
	}

	public function testRegisterFileWithCorrectType() {
		$this->assertEquals(true, $this->assetsTestInstance->registerFile('css', 'test.css'));
	}

	public function testRegisterFileWithIncorrectType() {
		$this->assertEquals(false, $this->assetsTestInstance->registerFile('wrong', 'test.css'));
	}

}