<?php

namespace Core\Web;

use \Core\App;
use Core\Web\Server\Server;

/**
 * Used for complete working of router class inside the view
 * @package Core\Web
 * @since 0.1
 */
class Url {

	/**
	 * Host information
	 *
	 * @type string
	 */
    protected static $_hostInfo;

	/**
	 * Host port
	 *
	 * @type int|string
	 */
    protected static $_port;

	/**
	 * Host secure port
	 *
	 * @type int|string
	 */
    protected static $_securePort;
	
	public static $_server;

	/**
	 * Generate the route following router instance
	 * @param $name Name of the route
	 * @param array $params Parameters of the route
	 * @return mixed Url of the route
	 * @throws \Exception
	 */
    public static function To($name, array $params) {
        return App::getRouter()->generate($name, $params);
    }

	/**
	 * Get the information about the host
	 * @return string Current host information
	 */
    public static function getHostInfo() {
		if (empty(self::$_server)) {
			self::$_server = new Server();
		}
		
        if (empty(self::$_hostInfo)) {
            $secure = self::getIsSecureConnection();
            $http = $secure ? 'https' : 'http';
            if (isset(self::$_server->getServer()['HTTP_HOST'])) {
                self::$_hostInfo = $http . '://' . self::$_server->get('HTTP_HOST');
            } elseif (isset(self::$_server->getServer()['SERVER_NAME'])) {
                self::$_hostInfo = $http . '://' . self::$_server->get('SERVER_NAME');
                $port = $secure ? self::getSecurePort() : self::getPort();
                if (($port !== 80 && !$secure) || ($port !== 443 && $secure)) {
                    self::$_hostInfo .= ':' . $port;
                }
            }
        }
        return self::$_hostInfo;
    }

	/**
	 * Check if the connection is made with HTTPS or only HTTP
	 * @return bool If HTTPS is used
	 */
    private static function getIsSecureConnection() {
        return isset(self::$_server->getServer()['HTTPS'])
			&& (strcasecmp(self::$_server->getServer()['HTTPS'], 'on') === 0
			|| self::$_server->getServer()['HTTPS'] == 1)
            || isset(self::$_server->getServer()['HTTP_X_FORWARDED_PROTO'])
			&& strcasecmp(self::$_server->getServer()['HTTP_X_FORWARDED_PROTO'], 'https') === 0
		;
    }

	/**
	 * Get the HTTPS port and return it
	 * @return int HTTPS port
	 */
    private static function getSecurePort() {
        if (self::$_securePort === null) {
            self::$_securePort =
				self::getIsSecureConnection() && isset(self::$_server->getServer()['SERVER_PORT'])
					? (int) self::$_server->get('SERVER_PORT')
					: 443
			;
        }

        return self::$_securePort;
    }

	/**
	 * Get the HTTP port and return it
	 * @return int HTTP port
	 */
    private static function getPort() {
        if (self::$_port === null) {
            self::$_port =
				!self::getIsSecureConnection() && isset(self::$_server->getServer()['SERVER_PORT'])
					? (int) self::$_server->get('SERVER_PORT')
					: 80
			;
        }

        return self::$_port;
    }

	public static function tearDown() {
		self::$_hostInfo = null;
		self::$_port = null;
		self::$_securePort = null;
	}

}