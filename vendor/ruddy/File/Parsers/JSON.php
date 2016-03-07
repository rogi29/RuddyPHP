<?php

namespace ruddy\File\Parsers;
use ruddy\File\File;

class JSON implements IParser
{
    private $_file = false;

    /**
     * Constructor
     *
     * @param $driver
     */
    public function __construct($driver)
    {
        $this->_file = new File($driver);
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
        $data = $this->encode($array);
        return $this->_file->filePutContents($filename, $data);
    }

    /**
     * Decode string from a file
     *
     * @param $filename
     * @param $assoc
     * @param $depth
     * @return array
     */
    public function decodeFile($filename, $assoc, $depth)
    {
        $data = $this->_file->fileGetContents($filename);
        return (array)$this->decode($data, $assoc, $depth);
    }

    /**
     * Encode array
     *
     * @param array $array
     * @return string
     */
    public function encode(array $array)
    {
        return json_encode($array);
    }

    /**
     * Decode string
     *
     * @param $data
     * @param $assoc
     * @param $depth
     * @return array
     */
    public function decode($data, $assoc, $depth)
    {
        return (array)json_decode((string) $data, $assoc, $depth);
    }
} 