<?php
/**
 * Main application Controller
 *
 * @method __construct()
 * @method render($view, $variables = [])
 * @method getController()
 * @method setLayoutVariables($variables = [])
 *
 * @link http://www.foxframework.com/
 * @copyright Copyright (c) 2017 Daethe
 * @license http://www.foxframework.com/license/
 */

namespace Core\Controller;

/**
 * Main application Controller
 * @package Core\Controller
 */
class Controller {

	/**
	 * @var array Variables used by the layout
	 */
    protected $layoutVariables;

	/**
	 * @var string Path of current view
	 */
    protected $viewPath;

	/**
	 * @var string Layout used by the controller
	 */
	protected $layout;

	/**
	 * @var string Title of the view
	 */
	protected $title;

	/**
	 * @var string Controller currently in use
	 */
	protected $controller;

	/**
	 * Controller constructor.
	 */
    public function __construct() {
        if (empty($this->layoutVariables)) {
            $this->layoutVariables = [];
        }
        $this->controller = get_class($this);
    }

	/**
	 * Render the view following is variables and the layout
	 * @param string $view      Name of the view to render
	 * @param array  $variables Variables used by the view
	 */
    public function render($view, $variables = []) {
        ob_start();
        extract($variables);
        require($this->viewPath . '/' . str_replace('.', '/', $view) . '.php');
        $content = ob_get_clean();
        extract($this->layoutVariables);
        require($this->viewPath . '/layouts/' . $this->layout . '.php');
    }

	/**
	 * Format the controller to get only name
	 * @return mixed Name of the controller without namespace and key 'Controller'
	 */
    public function getController() {
        return strtolower(
            str_replace(
                ['App\Controller\\', 'Controller'],
                '',
                get_class($this)
            )
        );
    }

	/**
	 * Set the layout variables
	 * @param array $variables
	 */
    public function setLayoutVariables($variables = []) {
        $this->layoutVariables = $variables;
    }

}