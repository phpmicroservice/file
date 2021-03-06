<?php

namespace app\controller;

use app\logic\attachmentArray;

/**
 * 用户相关
 * Class User
 * @package app\controller
 */
class Server extends \app\Controller
{

    /**
     * 创建一个集合
     */
    public function create_array()
    {
        $data = $this->getData();
        $attachmentArray = new attachmentArray();
        $server_name = $this->connect->f;
        $index = $attachmentArray->create_array($data['user_id'], $data['remark'] ?? '', $data['only'] ?? 1, $server_name);
        # service\ArrayService::create_array($user_id, $remark, 0,$server_name);
        if (is_int($index)) {
            $this->connect->send_succee($index);
        } else {
            # 出错了！
            $this->connect->send_error($index);
        }
    }

    /**
     * 设置状态为使用中
     */
    public function array_status01()
    {
        $index = $this->getData('index');
        $server_name = $this->connect->f;
        $attachmentArray = new attachmentArray();
        $bl = $attachmentArray->array_status01($index, $server_name);
        # service\ArrayService::create_array($user_id, $remark, 0,$server_name);
        $this->connect->send_succee($bl);
    }

    /**
     * 附件集进行关联, 多附件
     */
    public function array_correlation()
    {
        $index = $this->getData('index');
        $array_file_list = $this->getData('file_list');
        $additional = $this->getData('additional');
        $server_name = $this->connect->f;
        $attachmentArray = new attachmentArray();
        $bl = $attachmentArray->array_correlation($server_name, $index, $array_file_list, $additional);
        # service\ArrayService::create_array($user_id, $remark, 0,$server_name);
        $this->connect->send_succee($bl);
    }


    /**
     *
     * 检查集合中是否存在这个附件
     */
    public function ex_array()
    {
        $index = $this->getData('array_id');
        $type = $this->getData('type');
        $file_id = $this->getData('file_id');
        $UserServer = new \app\logic\attachmentArray();
        $bl = $UserServer->ex_array($index, $type, $file_id);
        $this->connect->send_succee($bl);
    }


    /**
     * 集合的附件列表
     */
    public function arrayfilelist()
    {
        $index = $this->getData('array_id');
        $Server = new \app\logic\attachmentArray();
        $re = $Server->arraylist(0, $index);
        $this->connect->send_succee($re);
    }
}

