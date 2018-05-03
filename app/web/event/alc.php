<?php

namespace app\web\event;

use app\Base;

/**
 *  alc
 * 权限控制 在控制器初始化之后执行 对已登录的权限进行验证
 * @author Dongasai
 */
class alc extends Base
{
    private $acl; #权限控制对象

    /**
     *
     * beforeDispatch 在调度之前
     * @param \Phalcon\Events\Event $Event
     * @param \Phalcon\Mvc\Dispatcher $Dispatcher
     * @return
     */
    public function beforeDispatch(\Phalcon\Events\Event $Event, \Phalcon\Mvc\Dispatcher $Dispatcher)
    {
        return true;
    }


}
