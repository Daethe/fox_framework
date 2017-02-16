<?php
/**
 * Auxiliary Config class, to parse a php file.
 */

namespace Core\Web\Router;

use Core\Exception\InvalidArgumentException;

/**
 * Auxiliary Config class, to parse a php file.
 * @package Core\Web\Router
 */
final class RouterConfig {

	/**
	 * Avoid instantiation.
	 * @codeCoverageIgnore
	 */
    private function __construct() {}

	/**
	 * Load all configuration from the 'router' file
	 * @param $file The router configuration file
	 * @return mixed Router configuration
	 * @throws InvalidArgumentException
	 */
    public static function loadFromFile($file) {
        if (!is_file($file)) {
            throw new InvalidArgumentException(sprintf("The file %s doesn't exists.", $file));
        }

        $file = require($file);
        return $file['router'];
    }

}