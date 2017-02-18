<?php

use \Core\App as CoreApp;

class AppTest extends PHPUnit\Framework\TestCase {

	public function testGetAssets() {
		$this->assertInstanceOf(\Core\Assets\Assets::class, CoreApp::getAssets());
	}

	public function testGetConfig() {
		$this->assertInstanceOf(\Core\Config\Config::class, CoreApp::getConfig());
	}
	
	public function testGetConfigWithKey() {
		
	}
	
	public function testGetConfigWithoutKey() {
		
	}

	public function testGetInstance() {
		$this->assertInstanceOf(CoreApp::class, CoreApp::getInstance());
	}

}