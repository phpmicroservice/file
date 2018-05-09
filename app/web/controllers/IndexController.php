<?php
/**
 * Created by PhpStorm.
 * User: Dongasai
 * Date: 2018/5/3
 * Time: 11:21
 */

namespace app\web\controllers;


use app\Base;

class IndexController extends Base
{
    public function index()
    {
        var_dump($this->session->get('user_id'));
    }

    public function options()
    {

    }

}