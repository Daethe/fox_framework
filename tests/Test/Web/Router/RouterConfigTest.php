<?php
/**
 * Created by PhpStorm.
 * User: marcp
 * Date: 19/02/2017
 * Time: 18:38
 */

namespace CoreTests\Test\Web\Router;


use Core\Exception\InvalidArgumentException;
use Core\Web\Router\RouterConfig;

class RouterConfigTest extends \PHPUnit\Framework\TestCase {

	public function testLoadFromFileWithFile() {
		$expected = [
			'assets' => [ '/:file', 'assets.dump', 'GET', '\Core\Controller\\' ], // Don't remove this one. Working for all assets in the application'
			'index' => [
				'/',
				'some.index',
				'GET',
				'\CoreTests\Fixtures\\'
			],
			'users' => [
				'/users',
				'some.users',
				'POST',
				'\CoreTests\Fixtures\\'
			]
		];
		$result = RouterConfig::loadFromFile(__DIR__ . '/../../../Config/config.php');

		$this->assertSame($expected, $result);
	}

	public function testLoadFromFileWithoutFile() {
		$this->expectException(InvalidArgumentException::class);
		RouterConfig::loadFromFile('fileNotExisting');
	}

}