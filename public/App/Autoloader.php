<?php
/**
 * App class autoloader
 *
 * @method register()
 * @method autoload($class)
 */

namespace App;

/**
 * App class autoloader
 * @package App
 */
class Autoloader {

	/**
	 * Register a class to the base php autoload
	 */
    static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

	/**
	 * Load the wanted class and require it
	 * @param $class Name of the class
	 */
    static function autoload($class) {
        if (strpos($class, __NAMESPACE__ . '\\') === 0) {
            $class = str_replace(__NAMESPACE__ . '\\', '', $class);
            $class = str_replace('\\', '/', $class);
            require __DIR__ . '/' . $class . '.php';
        }
    }

}