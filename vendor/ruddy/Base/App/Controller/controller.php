<?php
/**
 * Ruddy Framework controller
 *
 * @author Gil Nimer <gil@ruddy.nl>
 */

namespace ruddy\Base\App\Controller;


abstract class controller
{
    /**
     * Title
     *
     * @var null
     */
    public $title = null;

    /**
     * Set title
     *
     * @param $title
     * @return string
     */
    public function title($title)
    {
        $this->title = (string)$title;
    }

    /**
     * Set/Get model
     *
     * @param $model
     * @return mixed
     * @throws \Exception
     */
    public function model($model)
    {
        if($model == null || !is_object($model)) {
            throw new \Exception('Invalid Model');
        }

        return $model;
    }

    /**
     * Set/Get view
     *
     * @param $view
     * @return mixed
     * @throws \Exception
     */
    public function view($view)
    {
        if($view == null || !is_object($view)) {
            throw new \Exception('Invalid View');
        }

        return $view;
    }
}