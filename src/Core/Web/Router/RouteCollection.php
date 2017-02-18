<?php
/**
 * Route collection class
 */

namespace Core\Web\Router;

/**
 * Class RouteCollection
 * @package Core\Web\Router
 */
class RouteCollection extends \SplObjectStorage {

	/**
	 * Attach a Route to the collection.
	 * @param Route $attachObject
	 */
    public function attachRoute(Route $attachObject) {
        parent::attach($attachObject, null);
    }

	/**
	 * Fetch all routes stored on this collection of routes and return it.
	 * @return Route[]
	 */
    public function all() {
        $tmp = [];
        foreach($this as $route) { $tmp[] = $route; }
        return $tmp;
    }

}