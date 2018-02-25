<?php
/**
 * Created by PhpStorm.
 * User: marcp
 * Date: 18/02/2017
 * Time: 23:58
 */

namespace FoxTests\Core\Test;

use Core\App as CoreApp;
use Core\Assets\AssetsManager as CoreAssets;
use Core\Config\Config as CoreConfig;
use Core\Exception\InvalidDatabaseTypeException;
use Core\Exception\InvalidKeyException;

class AppTest extends \PHPUnit\Framework\TestCase {

	public function testGetAliasWithCorrectKey() {
		$this->assertEquals('test', CoreApp::getAlias('correctKey'));
	}

	public function testGetAliasWithIncorrectKey() {
		$this->expectException(InvalidKeyException::class);
		CoreApp::getAlias('incorrectKey');
	}

	public function testGetConfig() {
		$this->assertInstanceOf(CoreConfig::class, CoreApp::getConfig());
	}

	public function testGetDbWithCorrectType() {
		$this->assertInstanceOf(\PDO::class, CoreApp::getDb());
	}

	public function testGetDbWithIncorrectType() {
		CoreApp::getConfig()->set('database', [
			'type' => 'invalid'
		]);

		$this->expectException(InvalidDatabaseTypeException::class);
		CoreApp::setupDB();
	}

	public function testGetInstance() {
		$this->assertInstanceOf(CoreApp::class, CoreApp::getInstance());
	}

}