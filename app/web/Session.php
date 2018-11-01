<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/1
 * Time: 20:37
 */

namespace app\web;


class Session
{

    public $user_id = 2;

    public function __get($name)
    {
        return null;
    }

    public function get_user_id()
    {
        return $this->user_id;
    }


}