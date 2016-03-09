<?php

namespace ruddy\Base\Server;

class Path implements \ArrayAccess
{
    private $var = array();

    public function __construct($name = 'FILE', $file = __FILE__)
    {
        $this->var[$name]           = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, dirname($file));
        $this->var['SELF']          = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, dirname(__FILE__));
        $this->var['APPS']          = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, realpath('applications/'));
        $this->var['VENDOR']        = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, realpath(dirname(__FILE__).'/../../../'));
        $this->var['URI']           = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        $this->var['URL']           = filter_var('http://'.filter_input(INPUT_SERVER, 'HTTP_HOST').$this->var['URI'], FILTER_SANITIZE_URL, FILTER_FLAG_HOST_REQUIRED);
        $this->var['METHOD']        = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        $this->var['SCRIPT']        = filter_input(INPUT_SERVER, 'SCRIPT_NAME', FILTER_SANITIZE_URL);
        $this->var['FULL_SCRIPT']   = filter_input(INPUT_SERVER, 'SCRIPT_FILENAME');
        $this->var['RELATIVE_NAME'] = substr($this->var['FULL_SCRIPT'], strripos($this->var['FULL_SCRIPT'], $this->var['SCRIPT']));
        $this->var['RELATIVE_PATH'] = substr($this->var['RELATIVE_NAME'] , 0, strrpos($this->var['RELATIVE_NAME'], "/"));
        $this->var['RELATIVE_URI']  = preg_replace('/^'.preg_quote($this->var['RELATIVE_PATH'], '/').'/', '', $this->var['URI']);
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->var[] = $value;
        } else {
            $this->var[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->var[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->var[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->var[$offset]) ? $this->var[$offset] : null;
    }
}