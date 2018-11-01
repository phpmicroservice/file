<?php

namespace app\web\event;

use app\Base;

/**
 * application 的行为集合
 * @author Dongasai
 */
class application extends Base
{
    /**
     * 在模块初始化之后
     * @param \Phalcon\Events\Event $Event
     * @param $Application
     */
    public function afterStartModule(\Phalcon\Events\Event $Event, \Phalcon\Mvc\Application $application)
    {
        if ($this->router->getModuleName() != 'file') {
            // home分组是展示型分组
            // $application->useImplicitView(false);
        }

        # 创建事件管理器
        $eventsManager = new \Phalcon\Events\Manager();
        $eventsManager->attach(
            "db:beforeQuery",
            function ($event, $connection) {
                $sql = $connection->getSQLStatement();
                Trace::add('sql', $sql);
            }
        );
        // 设置事件管理器
        $this->db->setEventsManager($eventsManager);
    }

    /**
     * 在执行控制器之前
     */
    public function beforeHandleRequest(\Phalcon\Events\Event $Event, $Application)
    {
        $this->create_log();
        $this->view->setVar('RUN_UNIQID', RUN_UNIQID);
        # 在调度之前 进行权限验证
        $EventsManager = $this->dispatcher->getEventsManager();
        $alc = new alc();
        $EventsManager->attach('dispatch:beforeDispatch', $alc);
        $this->dispatcher->setEventsManager($EventsManager);
        $Obj = new beforeHandleRequest();
        $Obj->run();

    }

    public function create_log()
    {
        $url_m = $this->dispatcher->getModuleName() . '/' . $this->dispatcher->getControllerName() . '/' . $this->dispatcher->getActionName();
        $data = [
            'ip' => $_SERVER['REMOTE_ADDR'],
            'url' => $_SERVER['REQUEST_URI'],
            'header' => serialize('1'),
            'get' => serialize($_GET),
            'post' => serialize($_POST),
            'server' => serialize($_SERVER),
            'url_m' => $url_m,
            'error' => 0

        ];

    }

    /**
     * 在
     * @param \Phalcon\Events\Event $Event
     * @param $Application
     * @param \Phalcon\Http\Response $re
     */
    public function beforeSendResponse(\Phalcon\Events\Event $Event, $Application, \Phalcon\Http\Response $re)
    {

        $data = [
            'response_header' => ' ',
            'response' => $re->getContent(),
            'cookie' => $re->getCookies()
        ];

        //\logic\Common\Log::save(RUN_TIME, RUN_UNIQID, $data);
    }

}
