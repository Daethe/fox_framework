<?php

class UrlTest extends PHPUnit\Framework\TestCase {

	public function testToWithoutParameters() {
		$this->expectOutputString('/');
		\Core\Web\Url::To('site.index', []);
	}

}