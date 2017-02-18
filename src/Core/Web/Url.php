<?php

namespace Core\Web;

use \Core\App;

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
        if (empty(self::$_hostInfo)) {
            $secure = self::getIsSecureConnection();
            $http = $secure ? 'https' : 'http';
            if (isset($_SERVER['HTTP_HOST'])) {
                self::$_hostInfo = $http . '://' . $_SERVER['HTTP_HOST'];
            } elseif (isset($_SERVER['SERVER_NAME'])) {
                self::$_hostInfo = $http . '://' . $_SERVER['SERVER_NAME'];
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
        return isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] == 1)
            || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0;
    }

	/**
	 * Get the HTTPS port and return it
	 * @return int HTTPS port
	 */
    private function getSecurePort() {
        if (self::$_securePort === null) {
            self::$_securePort = self::getIsSecureConnection() && isset($_SERVER['SERVER_PORT']) ? (int) $_SERVER['SERVER_PORT'] : 443;
        }

        return self::$_securePort;
    }

	/**
	 * Get the HTTP port and return it
	 * @return int HTTP port
	 */
    private function getPort() {
        if (self::$_port === null) {
            self::$_port = !self::getIsSecureConnection() && isset($_SERVER['SERVER_PORT']) ? (int) $_SERVER['SERVER_PORT'] : 80;
        }

        return self::$_port;
    }

}