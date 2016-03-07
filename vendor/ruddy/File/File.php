<?php

namespace ruddy\File;

/**
 * Ruddy Framework File
 * 
 * @author Nick Vlug <nick@ruddy.nl>
 * @author Gil Nimer <gil@ruddy.nl>
 */

class File
{
    /**
     * driver String
     *
     * @var string
     */
    protected $driverStr = "";
    
    /**
     * driver Interface
     * @var Drivers\Idriver();
     */
    protected $driver = null;

    /**
     * Drivers array
     * @var array
     */
    protected $drivers = array('Direct', 'FTP');

    /**
     * Constructor
     */
    public function __construct($string = null)
    {
        if(is_null($string)) return;

        if(in_array($string, $this->drivers)){
            $this->driverStr = $string;
            $driver = "\\ruddy\\File\\Drivers\\{$string}";
            $this->driver = new $driver();
        } else {
            throw new \Exception("File driver does not exists", "500");
        }
    }

    /**
     * Set driver to use in the File
     *
     * @param $string
     * @throws \Exception
     */
    public function setDriver($string)
    {
        if(in_array($string, $this->drivers)){
            $this->driverStr = $string;
            $driver = "\\ruddy\\File\\Drivers\\{$string}";
            $this->driver = new $driver();
        } else {
            throw new \Exception("File driver does not exists", "500");
        }
    }

    /**
     * Get the driver in String format
     *
     * @return string
     */
    public function getDriverName()
    {
        return $this->driverStr;
    }

    /**
     * Connect FTP
     *
     * @param $server
     * @param $username
     * @param $password
     * @return mixed
     * @throws \Exception
     */
    public function connFTP($server, $username, $password, $tmpDir)
    {
        if(is_null($this->driver) || $this->driverStr != "FTP")
        {
            throw new \Exception("File driver must be FTP", "500");
        }
        return $this->driver->connFTP((string)$server, (string)$username, (string)$password, (string)$tmpDir);
    }

    /**
     * Close FTP connection
     *
     * @return mixed
     * @throws \Exception
     */
    public function closeFTP()
    {
        if(is_null($this->driver) || $this->driverStr != "FTP")
        {
            throw new \Exception("File driver must be FTP", "500");
        }
        return $this->driver->closeFTP();
    }

    /**
     * Uploads a file to the FTP server /from an open file
     *
     * @param $remote_file
     * @param $local_file
     * @param bool $fopen
     * @param null $mode
     * @param null $ftp_stream
     * @return bool
     */
    public function putFTP($remote_file, $local_file, $mode = null, $fopen = false, $ftp_stream = null)
    {
        if(is_null($this->driver) || $this->driverStr != "FTP")
        {
            return false;
        }
        return $this->driver->putFTP($this->path($remote_file), $this->path($local_file), $mode, $fopen, $ftp_stream);
    }

    /**
     * Downloads a file from the FTP server /to an open file
     *
     * @param $local_file
     * @param $remote_file
     * @param bool $fopen
     * @param null $mode
     * @param null $ftp_stream
     * @return bool
     */
    public function getFTP($local_file, $remote_file, $mode = null, $fopen = false, $ftp_stream = null)
    {
        if(is_null($this->driver) || $this->driverStr != "FTP")
        {
            return false;
        }
        return $this->driver->getFTP($this->path($local_file), $this->path($remote_file), $mode, $fopen, $ftp_stream);
    }

    /**
     * Tells whether the filename is a directory
     *
     * @param $dirname
     * @return bool
     */
    public function isDir($dirname)
    {
        if(is_null($this->driver))
        {
            return false;
        }
        return $this->driver->isDir($this->path($dirname));
    }

    /**
     * Tells whether the filename is a regular file
     *
     * @param $filename
     * @return bool
     */
    public function isFile($filename)
    {
        if(is_null($this->driver))
        {
            return false;
        }
        return $this->driver->isFile($this->path($filename));
    }

    /**
     * Open File
     *
     * @param $filename
     * @param string $mode
     * @return null|resource
     */
    public function fopen($filename, $mode = 'a+')
    {
        if(is_null($this->driver))
        {
            return false;
        }
        return $this->driver->fwrite($this->path($filename), (string)$mode);
    }

    /**
     * Close file
     *
     * @param bool $handle
     * @return bool
     */
    public function fclose($handle = false)
    {
        if(is_null($this->driver))
        {
            return false;
        }
        return $this->driver->fclose($handle);
    }

    /**
     * Write to file
     *
     * @param bool $handle
     * @param $string
     * @return bool
     */
    public function fwrite($handle = false, $string)
    {
        if(is_null($this->driver))
        {
            return false;
        }
        return $this->driver->fwrite($handle, (string)$string);
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
        if(is_null($this->driver))
        {
            return false;
        }
        return $this->driver->mkdir($this->path($dirname), (int)$mode, $recursive);
    }

    /**
     * Removes directory
     *
     * @param $dirname
     * @return bool
     */
    public function rmDir($dirname) 
    {
        if(is_null($this->driver))
        {
            return false;
        }
        return $this->driver->rmdir($this->path($dirname));
    }

    /**
     * Deletes a file
     *
     * @param $filename
     * @return bool
     */
    public function delete($filename)
    {
        if(is_null($this->driver))
        {
            return false;
        }
        return $this->driver->delete($this->path($filename));
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
        if(is_null($this->driver))
        {
            return false;
        }
        return $this->driver->copy($this->path($source), $this->path($dest));
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
        if(is_null($this->driver))
        {
            return false;
        }
        return $this->driver->rename($this->path($oldname), $this->path($newname));
    }

    /**
     * Gets file modification time
     *
     * @param $filename
     * @return bool
     */
    public function fileMTime($filename)
    {
        if(is_null($this->driver))
        {
            return false;
        }
        return $this->driver->filemtime($this->path($filename));
    }

    /**
     * Reads entire file into a string
     *
     * @param $filename
     * @return bool
     */
    public function fileGetContents($filename)
    {
        if(is_null($this->driver))
        {
            return false;
        }
        return $this->driver->fileGetContents($this->path($filename));
    }

    /**
     * Write a string to a file
     *
     * @param $filename
     * @param $data
     * @return bool
     */
    public function filePutContents($filename, $data)
    {
        if(is_null($this->driver))
        {
            return false;
        }
        return $this->driver->filePutContents($this->path($filename), (string)$data);
    }

    public function path($path)
    {
        if(is_null($path)){
            return false;
        }
        return str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, (string)$path);
    }
}