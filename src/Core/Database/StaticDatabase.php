<?php

namespace Core\Database;

use \PDO;

class StaticDatabase {

	// ----------------------- //
	// ---    CONSTANTS    --- //
	// ----------------------- //
	/**
	 * Default database connection
	 */
	const DEFAULT_CONNECTION = 'default';

	/**
	 * Limit clause style
	 */
	const LIMIT_STYLE_TOP_N = "top";

	/**
	 * Limit clause style
	 */
	const LIMIT_STYLE_LIMIT = "limit";

	// ------------------------ //
	// ---    PROPERTIES    --- //
	// ------------------------ //
	/**
	 * Map of configuration settings
	 * @type array
	 */
	protected static $config = [];

	/**
	 * Class configuration
	 * @type array
	 */
	protected static $defaultConfig = array(
		'dsn' => 'sqlite::memory:',
		'id_column' => 'id',
		'id_column_overrides' => array(),
		'error_mode' => PDO::ERRMODE_EXCEPTION,
		'username' => null,
		'password' => null,
		'driver_options' => null,
		'identifier_quote_character' => null, // if this is null, will be autodetected
		'limit_clause_style' => null, // if this is null, will be autodetected
		'logging' => false,
		'logger' => null,
		'caching' => false,
		'caching_auto_clear' => false,
		'return_result_sets' => false,
	);

	/**
	 * Map of database connections, instances of the PDO class
	 * @type array
	 */
	protected static $db = [];

	/**
	 * Reference to previously used PDOStatement object to enable low-level access, if needed
	 * @type null
	 */
	protected static $lastStatement;

	/**
	 * Last query run, only populated if logging is enabled
	 * @type string
	 */
	protected static $lastQuery;

	/**
	 * Pass configuration settings to the class in the form of
	 * key/value pairs. As a shortcut, if the second argument
	 * is omitted and the key is a string, the setting is
	 * assumed to be the DSN string used by PDO to connect
	 * to the database (often, this will be the only configuration
	 * required to use Database). If you have more than one setting
	 * you wish to configure, another shortcut is to pass an array
	 * of settings (and omit the second argument).
	 * @param string $key
	 * @param mixed $value
	 * @param string $connection Which connection to use
	 */
	public static function configure($key, $value = null, $connection = self::DEFAULT_CONNECTION) {
		self::setupDbConfig($connection);

		if (is_array($key)) {
			foreach ($key as $confKey => $confValue) {
				self::configure($confKey, $confValue, $connection);
			}
		} else {
			if (is_null($value)) {
				$value = $key;
				$key = 'dsn';
			}
			self::$config[$connection][$key] = $value;
		}
	}

	// ------------------------ //
	// ---  SETUP  METHODS  --- //
	// ------------------------ //
	/**
	 * Set up the database connection used by the class
	 * @param string $connection Which connection to use
	 */
	protected static function setupDb($connection = self::DEFAULT_CONNECTION) {
		if (!array_key_exists($connection, self::$db) || !is_object(self::$db[$connection])) {
			self::setupDbConfig($connection);

			$db = new PDO(
				self::$config[$connection]['dsn'],
				self::$config[$connection]['username'],
				self::$config[$connection]['password'],
				self::$config[$connection]['driver_options']
			);

			$db->setAttribute(PDO::ATTR_ERRMODE, self::$config[$connection]['error_mode']);
			self::setDb($db, $connection);
		}
	}

	/**
	 * Ensures configuration (multiple connections) is at least set to default.
	 * @param string $connection Which connection to use
	 */
	protected static function setupDbConfig($connection) {
		if (!array_key_exists($connection, self::$config)) {
			self::$config[$connection] = self::$defaultConfig;
		}
	}

	/**
	 * Detect and initialise the character used to quote identifiers
	 * (table names, column names etc). If this has been specified
	 * manually using Database::configure('identifier_quote_character', 'some-char'),
	 * this will do nothing.
	 * @param string $connection Which connection to use
	 */
	protected static function setupIdentifierQuoteCharacter($connection) {
		if (is_null(self::$config[$connection]['identifier_quote_character'])) {
			self::$config[$connection]['identifier_quote_character'] = self::detectIdentifierQuoteCharacter($connection);
		}
	}

	/**
	 * Detect and initialise the limit clause style ("SELECT TOP 5" /
	 * "... LIMIT 5"). If this has been specified manually using
	 * Database::configure('limit_clause_style', 'top'), this will do nothing.
	 * @param string $connection Which connection to use
	 */
	public static function setupLimitClauseStyle($connection) {
		if (is_null(self::$config[$connection]['limit_clause_style'])) {
			self::$config[$connection]['limit_clause_style'] = self::detectLimitClauseStyle($connection);
		}
	}

	// ------------------------ //
	// ---  DETECT METHODS  --- //
	// ------------------------ //
	/**
	 * Return the correct character used to quote identifiers (table
	 * names, column names etc) by looking at the driver being used by PDO.
	 * @param string $connection Which connection to use
	 * @return string
	 */
	protected static function detectIdentifierQuoteCharacter($connection) {
		switch (self::getDb($connection)->getAttribute(PDO::ATTR_DRIVER_NAME)) {
			case 'pgsql':
			case 'sqlsrv':
			case 'dblib':
			case 'mssql':
			case 'sybase':
			case 'firebird':
				return '"';
			case 'mysql':
			case 'sqlite':
			case 'sqlite2':
			default:
				return '`';
		}
	}

	/**
	 * Returns a constant after determining the appropriate limit clause
	 * style
	 * @param string $connection Which connection to use
	 * @return string Limit clause style keyword/constant
	 */
	protected static function detectLimitClauseStyle($connection) {
		switch (self::getDb($connection)->getAttribute(PDO::ATTR_DRIVER_NAME)) {
			case 'sqlsrv':
			case 'dblib':
			case 'mssql':
				return Database::LIMIT_STYLE_TOP_N;
			default:
				return Database::LIMIT_STYLE_LIMIT;
		}
	}

	// ------------------------ //
	// --- EXECUTE  METHODS --- //
	// ------------------------ //
	/**
	 * Internal helper method for executing statements. Stores statement
	 * object in ::_lastStatement, accessible publicly through ::get_last_statement()
	 *
     * @param string $query
	 * @param array $parameters An array of parameters to be bound in to the query
	 * @param string $connection Which connection to use
	 *
     * @return bool Response of PDOStatement::execute()
	 */
	protected static function execute($query, $parameters = [], $connection = self::DEFAULT_CONNECTION) {
		$statement = self::getDb($connection)->prepare($query);
		self::$lastStatement = $statement;

		foreach ($parameters as $key => &$parameter) {
			if (is_null($parameter)) {
				$type = PDO::PARAM_NULL;
			} elseif (is_bool($parameter)) {
				$type = PDO::PARAM_BOOL;
			} elseif (is_int($parameter)) {
				$type = PDO::PARAM_INT;
			} else {
				$type = PDO::PARAM_STR;
			}

			$statement->bindParam(is_int($key) ? ++$key : $key, $parameter, $type);
		}

		$q = $statement->execute();
		self::$lastQuery = $q;
		return $q;
	}

	/**
	 * Executes a raw query as a wrapper for PDOStatement::execute.
	 * Useful for queries that can't be accomplished through Database,
	 * particularly those using engine-specific features.
	 * @example raw_execute('SELECT `name`, AVG(`order`) FROM `customer` GROUP BY `name` HAVING AVG(`order`) > 10')
	 * @example raw_execute('INSERT OR REPLACE INTO `widget` (`id`, `name`) SELECT `id`, `name` FROM `other_table`')
	 * @param string $query The raw SQL query
	 * @param array $parameters Optional bound parameters
	 * @param string $connection Which connection to use
	 * @return bool Success
	 */
	public static function rawExecute($query, $parameters = [], $connection = self::DEFAULT_CONNECTION) {
		self::setupDb($connection);
		return self::execute($query, $parameters, $connection);
	}

	// ------------------------ //
	// ---  GETTER METHODS  --- //
	// ------------------------ //
	/**
	 * Retrieve configuration options by key, or as whole array.
	 * @param string $key
	 * @param string $connection
	 * @return mixed
	 */
	public static function getConfig($key = null, $connection = self::DEFAULT_CONNECTION) {
		if ($key) {
			return self::$config[$connection][$key];
		} else {
			return self::$config[$connection];
		}
	}

	/**
	 * Get a list of the available connection names
	 * @return array
	 */
	public static function getConnections() {
		return array_keys(self::$db);
	}

	/**
	 * Returns the PDO instance used by the Database class to communicate with
	 * the database. This can be called if any low-level DB access is
	 * required outside the class. If multiple connections are used,
	 * accepts an optional key name for the connection.
	 * @param string $connection Which connection to use
	 * @return PDO
	 */
	public static function getDb($connection = self::DEFAULT_CONNECTION) {
		self::setupDb($connection);
		return self::$db[$connection];
	}

	/**
	 * Get the last query executed. Returns last query from all
	 * connections if no connection_name is specified
	 * @return string
	 */
	public static function getLastQuery() {
		return self::$lastQuery;
	}

	/**
	 * Returns the PDOStatement instance last used by any connection wrapped by the Database.
	 * Useful for access to PDOStatement::rowCount() or error information
	 * @return PDOStatement
	 */
	public static function getLastStatement() {
		return self::$lastStatement;
	}

	// ------------------------ //
	// ---  SETTER METHODS  --- //
	// ------------------------ //
	/**
	 * Set the PDO object used by Database class to communicate with the database.
	 * This is public in case the Database should use a ready-instantiated
	 * PDO object as its database connection. Accepts an optional string key
	 * to identify the connection if multiple connections are used.
	 * @param PDO $db
	 * @param string $connection Which connection to use
	 */
	public static function setDb($db, $connection = self::DEFAULT_CONNECTION) {
		self::setupDbConfig($connection);
		self::$db[$connection] = $db;
		if (!is_null(self::$db[$connection])) {
			self::setupIdentifierQuoteCharacter($connection);
			self::setupLimitClauseStyle($connection);
		}
	}

	// ------------------------ //
	// --- FACTORY  METHODS --- //
	// ------------------------ //
	/**
	 * Despite its slightly odd name, this is actually the factory
	 * method used to acquire instances of the class. It is named
	 * this way for the sake of a readable interface, ie
	 * Database::forTable('table_name')->find_one()-> etc. As such,
	 * this will normally be the first method called in a chain.
	 * @param string $table
	 * @param string $connection Which connection to use
	 * @return Database
	 */
	public static function forTable($table, $connection = self::DEFAULT_CONNECTION) {
		self::setupDb($connection);
		return new self($table, [], $connection);
	}

}