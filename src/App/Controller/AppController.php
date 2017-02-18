<?php
/**
 * Main application controller
 * This one is used to make a bridge through the core controller inside the Core namespace
 *
 * @method __construct()
 */

namespace App\Controller;

/**
 * Main application controller
 * This one is used to make a bridge through the core controller inside the Core namespace
 *
 * @package App\Controller
 * @since 0.1
 */
class AppController extends \Core\Controller\Controller {

	/**
	 * @var string Layout used by the controller
	 */
    protected $layout = 'default';

	/**
	 * Define the viewPath currently the App/Views
	 */
    public function __construct() {
        $this->viewPath = ROOT . '/App/Views';
        parent::__construct();
    }

}