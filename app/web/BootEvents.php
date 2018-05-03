<?php

namespace app\web;

/**
 * Description of Boot
 *
 * @author Dongasai
 */
class BootEvents
{
    /**
     * boot事件
     */
    public function boot(\Phalcon\Events\Event $event, \Phalcon\Mvc\Application $application)
    {
        $EventsManager = $application->getEventsManager();
        $Commonapplication = new \app\web\event\application();
        $EventsManager->attach('application:afterStartModule', $Commonapplication);
        $EventsManager->attach('application:beforeHandleRequest', $Commonapplication);
        $application->setEventsManager($EventsManager);

    }

}
