<?php

namespace ruddy\File\Drivers;

/**
 * Ruddy Framework File IDriver
 *
 * @author Nick Vlug <nick@ruddy.nl>
 * @author Gil Nimer <gil@ruddy.nl>
 */

interface IDriver
{
    public function isDir($dirname);
    public function isFile($filename);
    public function fopen($filename, $mode = 'a+');
    public function fclose($handle);
    public function fwrite($handle, $data);
    public function mkDir($dirname, $mode = 0777, $recursive = false);
    public function rmDir($dirname);
    public function delete($filename);
    public function copy($source, $dest);
    public function rename($oldname, $newname);
    public function fileMTime($filename);
    public function fileGetContents($filename);
    public function filePutContents($filename, $data);
}
