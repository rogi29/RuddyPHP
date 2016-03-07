<?php

namespace ruddy\File\Drivers;

/**
 * Ruddy Framework File
 *
 * @author Nick Vlug <nick@ruddy.nl>
 * @author Gil Nimer <gil@ruddy.nl>
 */

class FTP implements IDriver 
{
    /**
     * @var null
     */
    private $_server    = null;

    /**
     * @var null
     */
    private $_username  = null;

    /***
     * @var null
     */
    private $_password  = null;

    /**
     * @var bool
     */
    private $_stream = false;

    /**
     * @var bool
     */
    private $_login = false;

    /**
     * Store file handler
     *
     * @var null
     */
    private $_handle = null;

    /**
     * Store path
     *
     * @var null
     */
    private $_path = null;

    /**
     * @var null
     */
    private $_tmpDir = null;


    /**
     * Connect FTP
     *
     * @param $server
     * @param $username
     * @param $password
     * @return bool
     */
    public function connFTP($server, $username, $password, $tmpDir)
    {
        $this->_server = $server;
        $this->_username = $username;
        $this->_password = $password;
        $this->_tmpDir = $tmpDir;

        $this->_stream = ftp_connect($this->_server);
        return $this->_login = ftp_login($this->_stream, $this->_username, $this->_password);
    }

    /**
     * Close FTP connection
     *
     * @return bool
     */
    public function closeFTP()
    {
        return ftp_close($this->_stream);
    }

    /**
     * Uploads a file to the FTP server /from an open file
     *
     * @param $remote_file
     * @param $local_file
     * @param null $mode
     * @param bool $fopen
     * @param null $ftp_stream
     * @return bool
     */
    public function putFTP($remote_file, $local_file, $mode = null, $fopen = false, $ftp_stream = null)
    {
        if(is_null($ftp_stream) && $this->_stream === false) {
            return false;
        }

        $ftp_stream = is_null($ftp_stream) ? $this->_stream : $ftp_stream;
        $mode = is_null($mode) ? $mode : 'w';

        if($fopen === true){
            if (!flock($local_file, LOCK_EX)) {
                return false;
            }
            return ftp_fput($ftp_stream, $remote_file, $local_file, $mode);
        }

        return ftp_get($ftp_stream, $remote_file, $local_file, $mode);
    }

    /**
     * Downloads a file from the FTP server /to an open file
     *
     * @param $local_file
     * @param $remote_file
     * @param null $mode
     * @param bool $fopen
     * @param null $ftp_stream
     * @return bool
     */
    public function getFTP($local_file, $remote_file, $mode = null, $fopen = false, $ftp_stream = null)
    {
        if(is_null($ftp_stream) && $this->_stream === false) {
            return false;
        }

        $ftp_stream = is_null($ftp_stream) ? $this->_stream : $ftp_stream;
        $mode = is_null($mode) ? $mode : 'w';

        if($fopen === true){
            if (!flock($local_file, LOCK_EX)) {
                return false;
            }
            return ftp_fget($ftp_stream, $local_file, $remote_file, $mode);
        }

        return ftp_get($ftp_stream, $local_file, $remote_file, $mode);

    }

    /**
     * Tells whether the filename is a directory
     *
     * @param $dirname
     * @return bool
     */
    public function isDir($dirname)
    {
        if($this->_stream === false){
            return false;
        }
        return is_dir("ftp://{$this->_username}:{$this->_password}@{$this->_server}/{$dirname}");
    }

    /**
     * Tells whether the filename is a regular file
     *
     * @param $filename
     * @return bool
     */
    public function isFile($filename)
    {
        if($this->_stream === false){
            return false;
        }

        return is_file("ftp://{$this->_username}:{$this->_password}@{$this->_server}/{$filename}");
    }

    /**
     * Open File
     *
     * @param $filename
     * @param string $mode
     * @return bool
     */
    public function fopen($filename = null, $mode = 'a+')
    {
        if($this->_stream === false){
            return false;
        }

        $this->_path = $filename;
        $this->_handle = fopen("ftp://{$this->_username}:{$this->_password}@{$this->_server}/{$filename}", $mode);
        flock($this->_handle, LOCK_UN);
        return $this->_handle;
    }

    /**
     * Close file
     *
     * @param bool $handle
     * @return bool
     */
    public function fclose($handle = false)
    {
        $handle = !$handle ? $this->_handle : $handle;
        if (!flock($handle, LOCK_EX)) {
            return false;
        }

        return fclose($handle);
    }

    /**
     * Write to file
     *
     * @param bool $handle
     * @param $string
     * @return bool|int
     */
    public function fwrite($handle = false, $string)
    {
        $handle = !$handle ? $this->_handle : $handle;
        if (!flock($handle, LOCK_EX)) {
            return false;
        }

        $bytes = fwrite($handle, $string);
        flock($handle, LOCK_UN);
        return $bytes;
    }

    /**
     * Makes directory
     *
     * @param $dirname
     * @param int $mode
     * @param bool $recursive
     * @return bool
     */
    public function mkDir($dirname, $mode = 0777, $recursive = false)
    {
        if($this->_stream === false){
            return false;
        }
        return ftp_mkdir($this->_stream, $dirname);
    }

    /**
     * Removes directory
     *
     * @param $dirname
     * @return bool
     */
    public function rmDir($dirname) 
    {
        if($this->_stream === false){
            return false;
        }
        return ftp_rmdir($this->_stream, $dirname);
    }

    /**
     * Deletes a file
     *
     * @param $filename
     * @return bool
     */
    public function delete($filename)
    {
        if($this->_stream === false){
            return false;
        }
        return ftp_delete($this->_stream, $filename);
    }

    /**
     * Copies file
     *
     * @param $source
     * @param $dest
     * @return bool
     */
    public function copy($source, $dest)
    {
        if($this->_stream === false){
            return false;
        }

        $id = uniqid();
        $filename = end(explode("/", str_replace('\\', '/', $source)));
        $localFile = $this->_tmpDir."/{$id}_{$filename}";

        if(ftp_get($this->_stream, $localFile, $source, FTP_BINARY)) {
            if (ftp_put($this->_stream, $dest, $localFile, FTP_BINARY)) {
                unlink($localFile);
                return $dest;
            }
            return false;
        }
        return false;
    }

    /**
     * Renames a file or directory
     *
     * @param $oldname
     * @param $newname
     * @return bool
     */
    public function rename($oldname, $newname)
    {
        if($this->_stream === false){
            return false;
        }
        return ftp_rename($this->_stream, $oldname , $newname);
    }

    /**
     * Gets file modification time
     *
     * @param $filename
     * @return bool|int
     */
    public function fileMTime($filename)
    {
        if($this->_stream === false){
            return false;
        }
        return ftp_mdtm($this->_stream, $filename);
    }

    /**
     * Reads entire file into a string
     *
     * @param $filename
     * @return bool|string
     */
    public function fileGetContents($filename)
    {
        if($this->_stream === false){
            return false;
        }
        return file_get_contents("ftp://{$this->_username}:{$this->_password}@{$this->_server}/{$filename}");
    }

    /**
     * Write a string to a file
     *
     * @param $filename
     * @param $data
     * @return bool|int
     */
    public function filePutContents($filename, $data)
    {
        if($this->_stream === false){
            return false;
        }
        $stream = stream_context_create(array('ftp' => array('overwrite' => true)));
        return file_put_contents("ftp://{$this->_username}:{$this->_password}@{$this->_server}/{$filename}", $data, 0, $stream);
    }
}
