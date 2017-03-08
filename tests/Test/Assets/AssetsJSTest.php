<?php
/**
 * Created by PhpStorm.
 * User: marcp
 * Date: 07/03/2017
 * Time: 23:29
 */

namespace CoreTests\Test\Assets;

use Core\Assets\AssetsJS;
use Core\Exception\NotAnAssociativeArrayKeyException;

class AssetsJSTest extends \PHPUnit\Framework\TestCase {

	private $path;
	private $testInstance;

	public function setUp() {
		$this->path = TEST_FIXTURES . 'assets/js/';
		$this->testInstance = new AssetsJS($this->path);
	}

	/**
	 * @dataProvider matcherProviderOneFile
	 */
	public function testDumpOneFileWithValidKey($expected, $key, $file) {
		$this->testInstance->register($key, $file);
		$this->assertEquals($expected, $this->testInstance->dumpOneFile($key));
	}

	public function testDumpOneFileWithInvalidKey() {
		$this->expectException(NotAnAssociativeArrayKeyException::class);
		$this->testInstance->dumpOneFile('invalidKey');
	}

	/**
	 * @dataProvider matcherProviderOnePlain
	 */
	public function testDumpOnePlainWithValidKey($expected, $key, $plain) {
		$this->testInstance->registerPlain($key, $plain);
		$this->assertEquals($expected, $this->testInstance->dumpOnePlain($key));
	}

	public function testDumpOnePlainWithInvalidKey() {
		$this->expectException(NotAnAssociativeArrayKeyException::class);
		$this->testInstance->dumpOnePlain('invalidKey');
	}

	/**
	 * @dataProvider matcherProvider
	 */
	public function testDump($file, $plain) {
		$this->testInstance->register($file[1], $file[2]);
		$this->testInstance->registerPlain($plain[1], $plain[2]);
		$this->assertEquals(
			$file[0] . $plain[0],
			$this->testInstance->dump()
		);
	}

	public function matcherProvider() {
		$path = TEST_FIXTURES . 'assets/js/';
		return array(
			array(
				array('<script src="/'. $path .'file1.js"></script>', 'test1', 'file1.js'),
				array('<script>alert("test");</script>', 'test1', 'alert("test");'),
			),
			array(
				array('<script src="/'. $path .'file2.js"></script>', 'test2', 'file2.js'),
				array('<script>console.log("test");</script>', 'test2', 'console.log("test");'),
			)
		);
	}

	public function matcherProviderOneFile() {
		$path = TEST_FIXTURES . 'assets/js/';
		return array(
			array('<script src="/'. $path .'file1.css"></script>', 'test1', 'file1.css'),
			array('<script src="/'. $path .'file2.css"></script>', 'test2', 'file2.css'),
		);
	}

	public function matcherProviderOnePlain() {
		return array(
			array('<script>alert("test");</script>', 'test1', 'alert("test");'),
			array('<script>console.log("test");</script>', 'test2', 'console.log("test");'),
		);
	}

}