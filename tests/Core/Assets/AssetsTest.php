<?php

class AssetsTest extends PHPUnit\Framework\TestCase {

	/* ============================================================================================================== */
	/* 													TESTS                                                         */
	/* ============================================================================================================== */
	public function testDumpWithoutType() {
		$this->assertEquals(null, (new \Core\Assets\Assets())->dump());
	}

	public function testDumpWithType() {
		$assets = new \Core\Assets\Assets();
		$this->expectOutputString('<link rel="stylesheet" href="test.css">');
		$assets->registerFile('css', 'test.css');
		$assets->dump('css');
	}

	/* ============================================================================================================== */
	/* 													MOCKS                                                         */
	/* ============================================================================================================== */
	private function getMock($class, $methods) {
		return $this->getMockBuilder($class)->setMethods($methods)->getMock();
	}
}