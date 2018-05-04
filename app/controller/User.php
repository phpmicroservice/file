<?php

namespace app\controller;

use app\Controller;

/**
 * 用户相关
 * Class User
 * @package app\controller
 */
class User extends Controller
{
    /**
     * 我的文件
     *
     */
    public function my()
    {
        $data = $this->getData();
        $this->send($data);
    }

}