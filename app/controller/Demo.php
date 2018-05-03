<?php

namespace app\controller;

use app\Controller;

/**
 *  测试控制器
 * Class Demo
 * @package app\controllers
 */
class Demo extends Controller
{
    public function index()
    {
        $this->send([
            "msg" => '我是文件测试!'
        ]);
    }

}