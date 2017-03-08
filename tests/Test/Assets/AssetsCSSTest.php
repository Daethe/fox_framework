<?php
/**
 * Created by PhpStorm.
 * User: marcp
 * Date: 07/03/2017
 * Time: 23:29
 */

namespace CoreTests\Test\Assets;

use Core\Assets\AssetsCSS;
use Core\Exception\NotAnAssociativeArrayKeyException;

class AssetsCSSTest extends \PHPUnit\Framework\TestCase {

	private $path;
	private $testInstance;

	public function setUp() {
		$this->path = TEST_FIXTURES . 'assets/css/';
		$this->testInstance = new AssetsCSS($this->path);
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
		$path = TEST_FIXTURES . 'assets/css/';
		return array(
			array(
				array('<link rel="stylesheet" href="/'. $path .'file1.css">', 'test1', 'file1.css'),
				array('<style>a.correct{height:100%}</style>', 'test1', 'a.correct{height:100%}')
			),
			array(
				array('<link rel="stylesheet" href="/'. $path .'file2.css">', 'test2', 'file2.css'),
				array('<style>a.correct{width:100%}</style>', 'test2', 'a.correct{width:100%}'),
			)
		);
	}

	public function matcherProviderOneFile() {
		$path = TEST_FIXTURES . 'assets/css/';
		return array(
			array('<link rel="stylesheet" href="/'. $path .'file1.css">', 'test1', 'file1.css'),
			array('<link rel="stylesheet" href="/'. $path .'file2.css">', 'test2', 'file2.css')
		);
	}

	public function matcherProviderOnePlain() {
		return array(
			array('<style>a.correct{height:100%}</style>', 'test1', 'a.correct{height:100%}'),
			array('<style>a.correct{width:100%}</style>', 'test2', 'a.correct{width:100%}'),
		);
	}

}