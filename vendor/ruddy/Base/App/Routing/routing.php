<?php
/**
 * Ruddy Framework Routing
 *
 * @author Nick Vlug <nick@ruddy.nl>
 * @author Gil Nimer <gil@ruddy.nl>
 */

namespace ruddy\Base\App\Routing;

class routing
{
    /**
     * Route Collection
     *
     * @var routeCollection
     */
    private $routes = null;

    /**
     * Constructor
     *
     * @param routeCollection $routes
     */
    public function __construct(routeCollection $routes)
    {
        $this->_routes = $routes;
    }

    public function matchRoute($main_uri)
    {
        $routes = $this->_routes;

        $routes->rewind();
        while($routes->valid()) {
            $object = $routes->current();

            $currURI    = str_replace($main_uri, '', $GLOBALS['_PATH']['URI']);
            $routeURI   = $object->getPath();
            $currArray  = $this->pathToArray($currURI);
            $routeArray = $this->pathToArray($routeURI);

            if(count($currArray) != count($routeArray)){
                $routes->next();
                continue;
            }

            if(preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\]`', $routeURI, $matches, PREG_SET_ORDER)) {
                // Loop through matches
                foreach($matches as $match)
                {
                    list($block, $pre, $type, $param) = $match;

                    // Strip the first char '/'
                    $block = substr($block, 1);

                    // Get Array Positions
                    $position = array_search($block, $routeArray);

                    // Get value
                    $value = (isset($currArray[$position])) ? $currArray[$position] : null;

                    // Check if parameter is valid
                    if($this->validType($type, $value) === false) {
                        continue;
                    }

                    // Set Parameter into the GET
                    $object->setParam($param, $value);

                    // Replace the parameter with the value
                    $routeURI = str_replace($block, $value, $routeURI);
                }
            }

            if($routeURI === $currURI) {
                return $object;
            }

            $routes->next();
        }

        return true;
    }

    /**
     * Check Type and filter the string
     *
     * @param $type
     * @param $str
     * @return bool|mixed
     */
    private function validType($type, $str)
    {
        switch($type)
        {
            case "i":
                return filter_var($str, FILTER_VALIDATE_INT);
                break;

            case "s":
                return preg_match('/[^A-Za-z0-9]/', $str) === 0;
                break;

            default:
                return false;
                break;
        }
    }

    private function pathToArray($path)
    {
        return explode('/', ltrim($path, '/'));
    }
} 