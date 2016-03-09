<?php
/**
 * Ruddy Framework view
 *
 * @author Gil Nimer <gil@ruddy.nl>
 */

namespace ruddy\Base\App\View;

use ruddy\File\File;


abstract class view
{
    /**
     * @var null
     */
    private $_file = null;

    /**
     * @var null
     */
    private $_directory = null;

    /**
     * @var null
     */
    private $_phptal = null;

    /**
     * @var null
     */
    private $_model = null;

    /**
     * PHPTAL
     *
     * @return \PHPTAL
     */
    public function PHPTAL($path = false)
    {
        return $this->_phptal = new \PHPTAL($path);
    }

    /**
     * Set Model
     *
     * @param $model
     * @throws \Exception
     */
    public function setModel($model)
    {
        if(!is_object($model)) {
            throw new \Exception('Invalid Model');
        }

        $this->_model = $model;
    }

    /**
     * Get Model
     *
     * @return mixed
     * @throws \Exception
     */
    public function getModel()
    {
        if(!is_object($this->_model)) {
            throw new \Exception('Invalid Model');
        }

        return $this->_model;
    }

    /**
     * Set Tal Data
     *
     * @param $key
     * @param string $value
     */
    public function setTalData($key, $value = "")
    {
        if(is_array($key)) {
            foreach($key as $k => $v) {
                $this->_phptal->set($k, $v);
            }
            return;
        }

        $this->_phptal->set($key, $value);
    }

    /**
     * Set Folder
     *
     * @param $directory
     * @return string
     * @throws \Exception
     */
    public function setFolder($directory)
    {
        $this->_file = new File('Direct');
        if(!$this->_file->isDir($directory)) {
            throw new \Exception("Folder '{$directory}' doesn't exists");
        }

        return $this->_directory = realpath($directory);
    }

    /**
     * Render Template
     *
     * @param $filename
     * @param bool $stripComments
     * @param int $outputMode
     * @throws \Exception
     * @throws \PHPTAL_ConfigurationException
     */
    public function render($filename, $stripComments = true, $outputMode = \PHPTAL::HTML5)
    {
        $filename = $this->_directory.DIRECTORY_SEPARATOR.$filename;
        if(!file_exists($filename)){
            throw new \Exception("Document '{$filename}' doesn't exists");
        }

        $this->_phptal->stripComments($stripComments);

        $this->_phptal->setOutputMode($outputMode);

        $content = $this->_file->fileGetContents($filename);

        $this->_phptal->setSource($content);

        $this->_phptal->echoExecute();
    }
}