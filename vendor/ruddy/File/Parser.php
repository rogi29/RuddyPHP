<?php

namespace ruddy\File;

/**
 * Ruddy Framework File Parser
 *
 * @author Gil Nimer <gil@ruddy.nl>
 */


class Parser
{
    /**
     * Parser object
     *
     * @var parsers\Iparser();
     */
    protected $parser = null;

    /**
     * Parsers array
     *
     * @var array
     */
    protected $parsers = array('JSON', 'XML');

    /**
     * Parser name
     *
     * @var null
     */
    protected $parserName = null;

    /**
     * Constructor
     *
     * @param null $string
     * @param string $driver
     * @throws \Exception
     */
    public function __construct($string = null, $driver = 'Direct')
    {
        if(is_null($string)) return;

        if(in_array($string, $this->parsers)){
            $this->parserStr = $string;
            $parser = "\\ruddy\\File\\Parsers\\{$string}";
            $this->parser = new $parser($driver);
        } else {
            throw new \Exception("Parser does not exists", "500");
        }
    }

    /**
     * Set parser
     *
     * @param $string
     * @param string $driver
     * @throws \Exception
     */
    public function setParser($string, $driver = 'DIRECT')
    {
        if(in_array($string, $this->parsers)){
            $this->parserStr = $string;
            $parser = "\\ruddy\\File\\Parsers\\{$string}";
            $this->parser = new $parser($driver);
        } else {
            throw new \Exception("Parser does not exists", "500");
        }
    }

    /**
     * Get the driver in String format
     *
     * @return string
     */
    public function getParserName()
    {
        return $this->parserName;
    }

    /**
     * Encode an array to a file
     *
     * @param $filename
     * @param array $array
     * @return bool
     */
    public function encodeToFile($filename, array $array)
    {
        if(is_null($this->parser))
        {
            return false;
        }

        return $this->parser->encodeToFile($filename, $array);
    }

    /**
     * Decode string from a file
     *
     * @param $filename
     * @param bool $assoc
     * @param int $depth
     * @return bool
     */
    public function decodeFile($filename, $assoc = false, $depth = 512)
    {
        if(is_null($this->parser))
        {
            return false;
        }

        return $this->parser->decodeFile($filename, $assoc, $depth);
    }

    /**
     * Encode array
     *
     * @param array $array
     * @return bool
     */
    public function encode(array $array)
    {
        if(is_null($this->parser))
        {
            return false;
        }

        return $this->parser->encode($array);
    }

    /**
     * Decode string
     *
     * @param $data
     * @param bool $assoc
     * @param int $depth
     * @return bool
     */
    public function decode($data, $assoc = false, $depth = 512)
    {
        if(is_null($this->parser))
        {
            return false;
        }

        return $this->parser->decode($data, $assoc, $depth);
    }
}