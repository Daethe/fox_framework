<?php
/**
 * Created by PhpStorm.
 * User: marcp
 * Date: 08/03/2017
 * Time: 00:04
 */

namespace CoreTests\Test\Assets;

use Core\Assets\Assets;
use Core\Exception\NotAnAssociativeArrayException;
use Core\Exception\NotAStringException;
use Core\Exception\UnknownDirException;

class AssetsTest extends \PHPUnit\Framework\TestCase {

	private $testInstance;

	public function setUp() {
		$this->testInstance = new Assets(TEST_FIXTURES . 'assets/css/');
	}

	public function testRegisterFileWithCorrectparams() {
		$this->testInstance->register('test', 'correct.css');
		$this->assertEquals(['test' => 'correct.css'], $this->testInstance->_files);
	}

	public function testRegisterFileWithIncorrectParams() {
		$this->expectException(NotAnAssociativeArrayException::class);
		$this->testInstance->register(50, 'test.css');
	}

	public function testRegisterPlainWithCorrectparams() {
		$this->testInstance->registerPlain('test', '.is {correct: true}');
		$this->assertEquals(['test' => '.is {correct: true}'], $this->testInstance->_plains);
	}

	public function testRegisterPlainWithIncorrectParams() {
		$this->expectException(NotAnAssociativeArrayException::class);
		$this->testInstance->registerPlain(50, '.test {wrong: key}');
	}

	public function testConstructorWithInvalidPath() {
		$this->expectException(UnknownDirException::class);
		$this->testInstance = new Assets('/a/wrong/path/');
	}

	public function testConstructorWithoutPathString() {
		$this->expectException(NotAStringException::class);
		$this->testInstance = new Assets(1);
	}
	
}