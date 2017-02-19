<?php

namespace CoreTests\Test\Config;

use Core\Config\Config;
use Core\Exception\InvalidKeyException;
use Core\Exception\NotAnArrayException;

class ConfigTest extends \PHPUnit\Framework\TestCase {

	private $configInstance;

	protected function setUp() {
		$this->configInstance = new Config();
	}

	public function testGetWithCorrectKey() {
		$this->assertEquals(false, $this->configInstance->get('useDatabase'));
	}

	public function testGetWithIncorrectKey() {
		$this->expectException(InvalidKeyException::class);
		$this->configInstance->get('wrong');
	}

	public function testSetWithCorrectKey() {
		$this->assertEquals(true, $this->configInstance->set('useDatabase', true));
	}

	public function testSetWithIncorrectKey() {
		$this->expectException(InvalidKeyException::class);
		$this->configInstance->set('wrong', '');
	}

	public function testSetWithCorrectValue_string() {
		$this->assertEquals(true, $this->configInstance->set('useDatabase', 'true'));
	}

	public function testSetWithCorrectValue_array() {
		$this->assertEquals(true, $this->configInstance->set('alias', ['test' => 'correct']));
	}

	public function testSetWithIncorrectValue_string() {
		$this->expectException(NotAnArrayException::class);
		$this->configInstance->set('alias', 'wrong');
	}

	public function testSetWithIncorrectValue_array() {
		$this->expectException(NotAnArrayException::class);
		$this->configInstance->set('useDatabase', ['wrong' => true]);
	}

}