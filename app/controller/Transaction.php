<?php

namespace app\controller;

/**
 * 事务控制器
 * Class Transaction
 * @package app\controller
 */
class Transaction extends \app\Controller
{
    public function create()
    {
        $name = $this->getData('name');
        $data = $this->getData('data');
        $xid = $this->getData('xid');
        $class_name = 'app\\task\\' . ucfirst($name) . 'Tx';
        if (!class_exists($class_name)) {
            return $this->send('class_not_exists');
        }
        $task_data = [
            'xid' => $xid,
            'name' => ucfirst($name) . 'Tx',
            'data' => $data
        ];
        $re = $this->swoole_server->task($task_data, -1);
        return $this->send(true);
    }

}