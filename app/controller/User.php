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
        $server = new \app\logic\User();
        $page = $this->getData('p', 1);
        $data = $server->my($this->user_id, $page);
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


    /**
     * 集合改变 , 增加或删除
     */
    public function array_change()
    {
        $index = $this->getData('index');
        $array_file_list = $this->getData('file_list');
        $userid = $this->user_id;
        $UserServer = new \app\logic\User();
        $bl = $UserServer->array_change($userid, $index, $array_file_list);
        # service\ArrayService::create_array($user_id, $remark, 0,$server_name);
        $this->connect->send_succee($bl);
    }


    /**
     * 集合的附件列表
     */
    public function arraylist()
    {
        $index = $this->getData('array_id');
        $userid = $this->user_id;
        $Server = new \app\logic\attachmentArray();
        $re = $Server->arraylist($userid, $index);
        $this->connect->send_succee($re);
    }


}