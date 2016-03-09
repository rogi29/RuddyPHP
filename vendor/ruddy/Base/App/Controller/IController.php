<?php
/**
 * Created by PhpStorm.
 * User: thebeast
 * Date: 3/9/2016
 * Time: 5:53 PM
 */

namespace ruddy\Base\App\Controller;


interface IController {
    public function run($id, $name);
    public function title($title);
    public function model($model);
    public function view($view);
} 