<?php
/**
 * Ruddy Framework Application
 *
 * @author Gil Nimer <gil@ruddy.nl>
 */

namespace ruddy\Base\App;

use ruddy\File\Parser;
use ruddy\Base\App;
use ruddy\Base\App\Routing as routing;

class application
{
    /**
     * App name
     *
     * @var string
     */
    private $_app       = null;

    /**
     * Apps folders
     *
     * @var array
     */
    private $_apps      = array( );

    /**
     * App file
     *
     * @var string
     */
    private $_appFile   = 'app.json';

    /**
     * Diagram file
     *
     * @var string
     */
    private $_diagram   = 'diagram.json';

    /**
     * Full Title
     *
     * @var string
     */
    private $_title     = null;

    /**
     * App Directory
     *
     * @var array|string
     */
    private $_directory = array( );

    /**
     * Constructor
     *
     * @param $application
     * @param string $file
     */
    public function __construct($application, $file = 'app.json')
    {
        $this->_app         = $application;
        $this->_apps        = $this->getFolders();
        $this->_appFile     = $file;
        $this->_directory   = $GLOBALS['_PATH']['CURR_APP'] = $GLOBALS['_PATH']['APPS'] .DIRECTORY_SEPARATOR. $application;
    }

    /**
     * Run application - Valid application Data & Route pages
     *
     * @param Routing\routeCollection $routes
     * @throws \Exception
     */
    public function run(routing\routeCollection $routes = null)
    {
        //check if app folder exists inside applications folder
        if(!in_array($this->_app, $this->_apps)){
            throw new \Exception('Application does not exists');
        }

        //Check if app file is readable
        if(!is_readable($this->_directory .'\\'. $this->_appFile)) {
            throw new \Exception('App file is not readable');
        }

        //Get app data from file
        $data = $this->getAppData();

        //App data validation instance
        $check = new validation($data);

        //Check if platform is valid
        if(!$check->isValidPlatform()) {
            echo 'Invalid platform!';
            return;
        }

        //Check if URL is valid
        if(!$uri = $check->isValidURL()) {
            echo 'Invalid URL!';
            return;
        }

        //Check if routes/diagram filename is set in app data
        if(isset($data['routes'])) {
            //Diagram filename
            $diagram = '\\'. $data['routes'];

            //Check if diagram is valid
            if(!is_readable($this->_directory . $diagram)) {
                throw new \Exception('Routes file is not readable!');
            }

            //routeCollection addDiagram
            $routes = new routing\routeCollection();
            $routes->addDiagram($this->_directory . $diagram);
        }

        //Check if routeCollection is null
        if(is_null($routes)) {
            throw new \Exception('Invalid routes collection!');
        }

        //Routing, get route
        $routing = new routing\routing($routes);
        $route = $routing->matchRoute($uri);
        if(!($route instanceof routing\route)) {
            throw new \Exception('404, page does not exists!');
        }

        //Set full title
        $mainTitle      = ($route->getMainTitle()) ? $route->getMainTitle() : $data['title'];
        $routeTitle     = (!is_null($route->getTitle())) ? $route->getTitle() : null;
        $this->_title   = $mainTitle.$routeTitle;

        //Call controller function
        $this->callController($route);
    }

    /**
     * Call controller function
     *
     * @param Routing\route $route
     * @return mixed
     * @throws \Exception
     */
    public function callController(routing\route $route)
    {
        // Get Controller Info
        $ctrl = $route->getDefault('controller');

        // Check if ctrl exists
        if(is_null($ctrl) === true) {
            throw new \Exception("Invalid controller parameter");
        }

        // Split NameSpace and Function
        list($controller, $function) = array_pad(explode(":", $ctrl, 2) , 2, NULL);

        // Check if Function exists
        if(is_null($function) === true) {
            throw new \Exception("Controller function does not exists");
        }

        require_once ($this->_directory . DIRECTORY_SEPARATOR ."controllers". DIRECTORY_SEPARATOR ."{$controller}.php");

        // Build Namespace
        $nameSpace = "applications\\{$this->_app}\\controllers\\{$controller}";

        // Make sure namespace exists
        if(class_exists($nameSpace) !== true) {
            throw new \Exception("Controller does not exists '{$nameSpace}'");
        }

        // Build Params
        $params = count($route->getParams()) > 0 ? $route->getParams() : array();

        //Set new instance
        $controller = new $nameSpace();

        $controller->title($this->_title);

        // Execute function
        return call_user_func_array(array($controller, $function), $params);
    }

    /**
     * Set application file
     *
     * @param $relative_name
     * @return mixed
     * @throws \Exception
     */
    public function setAppFile($relative_name)
    {
        if(!is_string($relative_name) || is_null($relative_name)) {
            throw new \Exception('$relative_name must be a string type and not null!');
        }

        return $this->_appFile = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $relative_name);
    }

    /**
     * Get applications folders
     *
     * @return array
     */
    private function getFolders()
    {
        $folders = array_diff(scandir($GLOBALS['_PATH']['APPS']), array('..', '.'));
        sort($folders);
        return $folders;
    }

    /**
     * Get applications data
     *
     * @return bool|mixed|null
     */
    private function getAppData()
    {
        $parser = null;
        $data   = null;
        $ext    = end(explode('.', $this->_appFile));

        switch($ext)
        {
            case 'json':
                $parser = new Parser('JSON');
                $data = $parser->decodeFile($this->_directory .'\\'. $this->_appFile, true);
                break;

            case 'xml':
                $parser = new Parser('XML');
                $data = $parser->decodeFile($this->_directory .'\\'. $this->_appFile);
                break;

            case 'php':
                $data = require_once $this->_directory . $this->_appFile;
                break;
        }

        return $data;
    }
} 