<?php
/**
 * Route class
 */

namespace Core\Web\Router;

use Core\Fig\Http\Message\RequestMethodInterface;

/**
 * Class Route
 * @package Core\Web\Router
 */
class Route {

	/**
	 * URL of this Route
	 * @var string
	 */
    private $url;

	/**
	 * Accepted HTTP methods for this route.
	 * @var string[]
	 */
    private $methods = [
        RequestMethodInterface::METHOD_GET,
        RequestMethodInterface::METHOD_POST,
        RequestMethodInterface::METHOD_PUT,
        RequestMethodInterface::METHOD_DELETE
    ];

	/**
	 * Target for this route, can be anything.
	 * @var mixed
	 */
    private $target;

	/**
	 * The name of this route, used for reversed routing
	 * @var string
	 */
    private $name;

	/**
	 * Custom parameter filters for this route
	 * @var array
	 */
    private $filters = [];

	/**
	 * Array containing parameters passed through request URL
	 * @var array
	 */
    private $parameters = [];

	/**
	 * Set named parameters to target method
	 * @example [ [0] => [ ["link_id"] => "12312" ] ]
	 * @var bool
	 */
    private $parametersByName;

	/**
	 * Configuration
	 *
	 * @var array
	 */
    private $config;

    /* ========================================================================== */
    /* CLASS METHOD                                                               */
    /* ========================================================================== */

	/**
	 * Class constructor
	 *
	 * @param       $resource
	 * @param array $config
	 */
    public function __construct($resource, array $config) {
        $this->url     = $resource;
        $this->config  = $config;
        $this->methods = isset($config['methods']) ? (array) $config['methods'] : [];
        $this->target  = isset($config['target']) ? $config['target'] : null;
        $this->name    = isset($config['name']) ? $config['name'] : null;
        $this->filters = isset($config['filters']) ? $config['filters'] : [];
    }

	/**
	 * Set a default filter if not defined for this route
	 * @param $matches
	 * @return mixed|string
	 */
    public function substituteFilter($matches) {
        if (isset($matches[1], $this->filters[$matches[1]])) {
            return $this->filters[$matches[1]];
        }
        return '([\w-%]+)';
    }

	/**
	 * Send the route to right Controller and call Controller function
	 */
    public function dispatch() {
        $action = explode('::', $this->config['_controller']);

        if ($this->parametersByName) {
            $this->parameters = [$this->parameters];
        }

        $this->action = !empty($action[1]) && trim($action[1]) !== '' ? $action[1] : null;

        if (!is_null($this->action)) {
            $instance = new $action[0];
            call_user_func_array([$instance, $this->action], $this->parameters);
        } else {
            $instance = new $action[0]($this->parameters);
        }
    }

    /* ========================================================================== */
    /* GETTER METHOD                                                              */
    /* ========================================================================== */

	/**
	 * Whole class getter
	 *
	 * @param $variable
	 * @return mixed The value of wanted variable
	 */
    public function get($variable) {
        return $this->{$variable};
    }

	/**
	 * Route action getter
	 *
	 * @return mixed Route action
	 */
    public function getAction() {
        return $this->action;
    }

	/**
	 * Route name getter
	 *
	 * @return mixed|null|string Route name
	 */
    public function getName() {
        return $this->name;
    }

	/**
	 * Route methods getter
	 *
	 * @return array|\string[] Route methods
	 */
    public function getMethods() {
        return $this->methods;
    }

	/**
	 * Route parameters getter
	 *
	 * @return array Route parameters
	 */
    public function getParameters() {
        return $this->parameters;
    }

	/**
	 * Route regex getter
	 *
	 * @return mixed Route regular expression
	 */
    public function getRegex() {
        return preg_replace_callback('/(:\w+)/', array(&$this, 'substituteFilter'), $this->url);
    }

	/**
	 * Route target getter
	 *
	 * @return mixed|null Route target
	 */
    public function getTarget() {
        return $this->target;
    }

	/**
	 * Route url getter
	 *
	 * @return string Route url
	 */
    public function getUrl() {
        return $this->url;
    }

    /* ========================================================================== */
    /* SETTER METHOD                                                              */
    /* ========================================================================== */

	/**
	 * Set the filters being used by the route
	 * @param array $filters
	 * @param bool $parametersByName
	 */
    public function setFilters(array $filters, $parametersByName = false) {
        $this->filters = $filters;
        $this->parametersByName = $parametersByName;
    }

	/**
	 * Define the method usable for route
	 * @param array $methods
	 */
    public function setMethods(array $methods) {
        $this->methods = $methods;
    }

	/**
	 * Set the name of the route
	 * @param $name
	 */
    public function setName($name) {
        $this->name = (string)$name;
    }

	/**
	 * Set the parameters of the route
	 * @param array $parameters
	 */
    public function setParameters(array $parameters) {
        $this->parameters = $parameters;
    }

	/**
	 * Set the target of this route
	 * @param $target
	 */
    public function setTarget($target) {
        $this->target = $target;
    }

	/**
	 * Set the route url
	 * @param $url
	 */
    public function setUrl($url) {
        $url = (string)$url;
        if (substr($url, -1) !== '/') {
            $url .= '/';
        }
        $this->url = $url;
    }

}