<?php
/**
 * Ruddy Framework Routing
 *
 * @author Gil Nimer <gil@ruddy.nl>
 */

namespace ruddy\Base\App\Routing;
use ruddy\File\Parser;

class routeCollection extends \SplObjectStorage
{
    /**
     * Add route
     *
     * @param route $object
     */
    public function addRoute(route $object)
    {
        parent::attach($object, null);
    }

    /**
     * Add multiple rounds from a diagram file
     *
     * @param $filename
     */
    public function addDiagram($filename)
    {
        $parser = null;
        $routes = null;
        $ext    = end(explode('.', $filename));

        switch($ext)
        {
            case 'json':
                $parser = new Parser('JSON');
                $routes = $parser->decodeFile($filename, true);
                break;

            case 'xml':
                $parser = new Parser('XML');
                $routes = $parser->decodeFile($filename);
                break;

            case 'php':
                $routes = require_once $this->_directory . $this->_appFile;
                break;
        }

        foreach($routes as $path => $value) {
            $defaults   = (!array_key_exists('defaults', $value)) ? array() : $value['defaults'];
            $methods    = (!array_key_exists('methods', $value)) ? array() : $value['methods'];
            $title      = (!array_key_exists('title', $value)) ? null : $value['title'];
            $main_title = (!array_key_exists('main_title', $value)) ? null : $value['main_title'];

            parent::attach(new route($path, $defaults, $methods, $title, $main_title), null);
        }
    }
} 