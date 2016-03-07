<?php

namespace ruddy\File\Parsers;

/**
 * Ruddy Framework File Parser
 *
 * @author Nick Vlug <nick@ruddy.nl>
 * @author Gil Nimer <gil@ruddy.nl>
 */

interface IParser
{
    public function encodeToFile($filename, array $array);
    public function decodeFile($filename, $assoc, $depth);
    public function encode(array $array);
    public function decode($data, $assoc, $depth);
}
