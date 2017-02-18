<?php
/**
 * Core class autoloader
 *
 * @method index()
 */

namespace App\Controller;

/**
 * Sample controller
 * @package App\Controller
 */
class SiteController extends AppController {

	/**
	 * Render the index view
	 */
    public function index() {
        $this->render('site.index', []);
    }

}