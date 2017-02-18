<?php

namespace Core;

use Core\Assets\Assets;
use Core\Config\Config;
use Core\Database\Idiorm;
use Core\Web\Router\RouterConfig;
use Core\Web\Router\Router;

/**
 * Manager of all application
 * @package Core
 */
class App {

	/**
	 * @var Application instance
	 */
    private static $_instance;

	/**
	 * @var Application database instance
	 */
    private static $_dbInstance;

	/**
	 * @var Application router instance
	 */
    private static $_routerInstance;

	/**
	 * @var Application configuration
	 */
    private static $_config;

	/**
	 * @var Assets object instance
	 */
    public static $assets;

    private function __Construct() {}

	/**
	 * Load the entire application and initialize all instance
	 */
    public static function load() {
        require 'Autoloader.php';
        \Core\Autoloader::register();

        require __DIR__ . '/../App/Autoloader.php';
        \App\Autoloader::register();

        self::getRouter();
    }

	/**
	 * Create the assets instance if not exists or return the existing one
	 * @return Assets object instance
	 */
    public static function getAssets() {
        if (empty(self::$assets)) {
            self::$assets = new Assets();
        }
        return self::$assets;
    }

	/**
	 * Get alias from configuration and return it
	 * @param $alias Wanted alias name
	 * @return mixed Value of the wanted alias
	 */
    public static function getAlias($alias) {
        return self::getConfig()->get('alias')[$alias];
    }

	/**
	 * Build the configuration for use
	 * @return \Core\Application|mixed Application configuration
	 */
	public static function getConfig() {
		if (empty(self::$_config)) {
			self::$_config = new Config();
		}
		return self::$_config;
	}

	/**
	 * Setup the database if not setted up.
	 * @return \Core\Application Database instance
	 */
	public static function getDb() {
		if (empty(self::$_dbInstance)) {
			self::setupDB();
		}
		return self::$_dbInstance;
	}

	/**
	 * Create an instance of the application if not exists
	 * @return App|Application Application instance
	 */
    public static function getInstance() {
        if (empty(self::$_instance)) {
            self::$_instance = new App();
        }
        return self::$_instance;
    }

	/**
	 * Create the router instance if not exists following the router configuration file
	 * @return Application|Router Router instance
	 */
    public static function getRouter() {
        if (empty(self::$_routerInstance)) {
            self::$_routerInstance = Router::parseConfig(self::getConfig()->get('router'));
            self::$_routerInstance->matchCurrentRequest();
        }
        return self::$_routerInstance;
    }

	/**
	 * Setup the database for use in controller.
	 * This one follow database configuration file.
	 */
    public static function setupDB() {
        $config = self::getConfig()->get('database');

        if (empty(self::$_dbInstance)) {
            switch($config['type']) {
                case 'mysql':
                    Idiorm::configure('mysql:host=' . $config['host'] . ';dbname=' . $config['name'] . ';charset=' . $config['charset']);
                    Idiorm::configure('username', $config['username']);
                    Idiorm::configure('password', $config['password']);
                    self::$_dbInstance = Idiorm::get_db();
                    break;
                default:
                    break;
            }
        }
    }

}