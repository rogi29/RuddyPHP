<?php
/**
 * Ruddy Framework model test
 *
 * @author Gil Nimer <gil@ruddy.nl>
 */

namespace applications\App1\models;


class test
{
    private $_data = array( );

    public function setTitle($title)
    {
        $this->_data['title'] = 'ruddy - '. $title;
    }

    public function setData($id, $name)
    {
        $this->_data['id'] = $id;
        $this->_data['name'] = $name;
    }

    public function data($key)
    {
        return $this->_data[$key];
    }
}