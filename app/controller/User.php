<?php

namespace app\controller;

/**
 * 用户相关
 * Class User
 * @package app\controller
 */
class User extends \app\Controller
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

    /**
     * 附件集关联,追加
     */
    public function array_additional()
    {
        $index = $this->getData('index');
        $array_file_list = $this->getData('file_list');
        $userid = $this->user_id;
        $UserServer = new \app\logic\User();
        $bl = $UserServer->array_additional($userid, $index, $array_file_list);
        # service\ArrayService::create_array($user_id, $remark, 0,$server_name);
        $this->connect->send_succee($bl);
    }

}