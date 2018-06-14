<?php

/**
 * Services are globally registered in this file
 * 服务的全局注册都这里,依赖注入
 */

use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Events\Manager;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;


//注册自动加载
$loader = new \Phalcon\Loader();
$loader->registerNamespaces(
    [
        'app' => ROOT_DIR . '/app/'
    ]
);
$loader->register();


/**
 * The FactoryDefault Dependency Injector automatically registers the right
 * services to provide a full stack framework.
 */
$di = new Phalcon\DI\FactoryDefault();


$di->setShared('config', function () {
    #Read configuration
    $config = new Phalcon\Config([]);
    return $config;
});

include_once ROOT_DIR . '/app/FactoryDefault.php';



$di["router"] = function () {
    $router = new \Phalcon\Mvc\Router();
    $router->setDefaultNamespace('app\\controller');
    $router->setDefaultController('index');
    $router->setDefaultAction('index');
    $router->add(
        "/:controller/:action/:params", [
            "controller" => 1,
            "action" => 2,
            'params' => 3
        ]
    );

    return $router;
};

//事件管理器
$di->setShared('eventsManager', function () {
    $eventsManager = new \Phalcon\Events\Manager();
    return $eventsManager;
});




$di->set(
    "modelsManager", function () {
    return new \Phalcon\Mvc\Model\Manager();
});


$di->setShared('logger', function () {
    $logger = new \pms\Logger\Adapter\MysqlLog('log');
    return $logger;
});



