<?php
/**
 * Ruddy Framework Routing
 *
 * @author Nick Vlug <nick@ruddy.nl>
 * @author Gil Nimer <gil@ruddy.nl>
 */

namespace ruddy\Base\App\Routing;

class route
{
    /**
     * @var null
     */
    private $_path      = null;

    /**
     * @var array
     */
    private $_defaults  = array();

    /**
     * @var array
     */
    private $_methods   = array();

    /**
     * @var null
     */
    private $_title     = null;

    /**
     * @var null
     */
    private $_mainTitle     = null;

    /**
     * @var array
     */
    private $_params    = array();

    /**
     * Constructor
     *
     * @param $path
     * @param array $defaults
     * @param array $methods
     * @param null $title
     * @throws \Exception
     */
    public function __construct($path, array $defaults = array(), array $methods = array(), $title = null, $main_title = null)
    {
        $this->setPath($path);
        $this->setDefaults($defaults);
        $this->setMethods($methods);
        $this->setTitle($title);
        $this->setMainTitle($main_title);
    }

    /**
     * Set Route Path
     *
     * @param $path
     * @throws \Exception
     */
    public function setPath($path)
    {
        if(is_string($path) !== true)
        {
            throw new \Exception("Route path should be a string");
        }

        $this->_path = filter_var($path, FILTER_SANITIZE_URL);
    }

    /**
     * Get Route Path
     *
     * @return null
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * Set Route Defaults
     *
     * @param $defaults
     */
    public function setDefaults($defaults)
    {
        $this->_defaults = $defaults;
    }

    /**
     * Get Route Defaults
     *
     * @return array
     */
    public function getDefaults()
    {
        return $this->_defaults;
    }

    /**
     * Get Default Specified By Name
     *
     * @param $name
     * @return null
     */
    public function getDefault($name)
    {
        return isset($this->_defaults[$name]) ? $this->_defaults[$name] : null;
    }

    /**
     * Add Route Defaults
     *
     * @param array $defaults
     * @param bool $override
     * @throws \Exception
     */
    public function addDefaults(array $defaults, $override = true)
    {
        $curr = $this->_defaults;

        foreach($defaults as $name => $default)
        {
            if($override === false && array_key_exists($name, $curr))
            {
                throw new \Exception("Add Defaults is not allowed override is false.");
            }
            $curr[$name] = $default;
        }

        $this->_defaults = $curr;
    }

    /**
     * Set Route Methods
     *
     * @param $methods
     */
    public function setMethods($methods)
    {
        $this->_methods = array_map('strtoupper', $methods);
    }

    /**
     * Get Route Methods
     *
     * @return mixed
     */
    public function getMethods()
    {
        return $this->_methods;
    }

    /**
     * Set Route Title
     *
     * @param $title
     * @throws \Exception
     */
    public function setTitle($title)
    {
        if(!is_string($title))
        {
            if(!is_null($title)) {
                throw new \Exception("Route title should be a string");
            }
        }

        $this->_title = $title;
    }

    /**
     * Get Route Title
     *
     * @return null
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Set Route Title
     *
     * @param $title
     * @throws \Exception
     */
    public function setMainTitle($title)
    {
        if(!is_string($title))
        {
            if(!is_null($title)) {
                throw new \Exception("Main title should be a string");
            }
        }

        $this->_mainTitle = $title;
    }

    /**
     * Get Route Title
     *
     * @return null
     */
    public function getMainTitle()
    {
        return $this->_mainTitle;
    }

    /**
     * Set Route Param
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setParam($key, $value)
    {
        return $this->_params[$key] = $value;
    }

    /**
     * Get All Params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }
} 