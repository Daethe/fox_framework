<?php

namespace Fox\Core;

use Fox\Core\Assets\AssetsManager;
use Fox\Core\Config\Config;
use Fox\Core\Database\Idiorm;
use Fox\Core\Exception\InvalidDatabaseTypeException;
use Fox\Core\Exception\InvalidKeyException;
use Fox\Core\Web\Router\Router;

/**
 * Manager of all application
 * @package Core
 */
class App {

	const DB_TYPE = [
		'mysql'
	];

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
	 * @var AssetsManager instance
	 */
    public static $assets;

    public function __construct() {}

	/**
	 * Load the entire application and initialize all instance
	 */
    public static function load() {
        require ROOT . '\Autoloader.php';
        \Fox\Autoloader::register();

        self::getRouter();
    }

	/**
	 * Create the assets manager instance if not exists or return the existing one
	 * @return AssetsManager instance
	 */
    public static function getAssets() {
        if (empty(self::$assets)) {
            self::$assets = new AssetsManager();
        }
        return self::$assets;
    }

	/**
	 * Get alias from configuration and return it
	 *
	 * @param $alias Wanted alias name
	 *
	 * @return mixed Value of the wanted alias
	 * @throws \Fox\Core\Exception\InvalidKeyException
	 */
    public static function getAlias($alias) {
		if (!isset(self::getConfig()->get('alias')[$alias])) {
			throw new InvalidKeyException('You must specify a correct key');
		}
        return self::getConfig()->get('alias')[$alias];
    }

	/**
	 * Build the configuration for use
	 * @return \Fox\Core\Application|mixed Application configuration
	 */
	public static function getConfig() {
		if (empty(self::$_config)) {
			self::$_config = new Config();
		}
		return self::$_config;
	}

	/**
	 * Setup the database if not setted up.
	 * @return \Fox\Core\Application Database instance
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

		if (in_array($config['type'], self::DB_TYPE)) {
			Idiorm::configure($config['type'] . ':host=' . $config['host'] . ';dbname=' . $config['name'] . ';charset=' . $config['charset']);
			Idiorm::configure('username', $config['username']);
			Idiorm::configure('password', $config['password']);
			self::$_dbInstance = Idiorm::get_db();
		} else {
			throw new InvalidDatabaseTypeException('This type is not supported');
		}
    }

}