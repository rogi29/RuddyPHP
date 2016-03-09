<?php
/**
 * Ruddy Framework view test
 *
 * @author Gil Nimer <gil@ruddy.nl>
 */

namespace applications\App1\views;

use ruddy\Base\App\View\view;


class test extends view
{
    /**
     * Output view
     *
     * @throws \Exception
     */
    public function output()
    {
        $this->PHPTAL();
        $this->setTalData('title',  $this->getModel()->data('title'));
        $this->setTalData('id',     $this->getModel()->data('id'));
        $this->setTalData('name',   $this->getModel()->data('name'));
        $this->setFolder($GLOBALS['_PATH']['CURR_APP'] .'\\resources\\documents\\xhtml');
        $this->render("test.xhtml");
    }
}