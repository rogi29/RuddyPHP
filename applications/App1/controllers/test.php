<?php
/**
 * Ruddy Framework controller test
 *
 * @author Gil Nimer <gil@ruddy.nl>
 */

namespace applications\App1\controllers;

use ruddy\Base\App\Controller\controller;
use ruddy\Base\App\Controller\IController;


class test extends controller implements IController
{
    private $_model = null;
    private $_view  = null;

    /**
     * @param $id
     * @param $name
     * @throws \Exception
     */
    public function run($id, $name)
    {
        $this->_model = $this->model(new \applications\App1\models\test());
        $this->_view = $this->view(new \applications\App1\views\test());

        $this->setData($id, $name);
        $this->viewOutput();
    }

    private function setData($id, $name)
    {
        $this->_model->setTitle($this->title);
        $this->_model->setData($id, $name);
    }

    private function viewOutput()
    {
        $this->_view->setModel($this->_model);
        $this->_view->output();
    }
} 