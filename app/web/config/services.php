<?php

/**
 * Services are globally registered in this file
 * 服务的全局注册卸载这里
 */

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager;


//注册自动加载
$loader = new \Phalcon\Loader();
$loader->registerNamespaces(
    [
        'app' => ROOT_DIR . '/app/',
        'tool' => ROOT_DIR . '/extends/tool/',
        'pms' => ROOT_DIR . '/vendor/phpmicroservice/pms-frame/src/'
    ]
);
$loader->register();

/**
 * The FactoryDefault Dependency Injector automatically registers the right
 * services to provide a full stack framework.
 */
$di = new Phalcon\DI\FactoryDefault();

include_once ROOT_DIR . '/app/FactoryDefault.php';



$di["router"] = function () {
    $router = new Router();
    $router->setDefaultNamespace("app\\web\\controllers");
    $router->setDefaultController("index");
    $router->setDefaultAction("index");
    $router->addOptions('/:params', [
        "controller" => 'index',
        "action" => 'options',
        'params' => 1
    ]);
    return $router;
};


// Registering the view component
$di->set('view', function () {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir(WEB_DIR . '/views/');

    return $view;
});

// Registering a dispatcher
$di->setShared('dispatcher', function () {

    $dispatcher = new Dispatcher();

    $dispatcher->setActionSuffix('');
    $eventManager = new Manager();
// 附上一个侦听者
    $eventManager->attach(
        "dispatch:beforeDispatchLoop", function ($event, $dispatcher) {
        $params = $dispatcher->getParams();
        $keyParams = [];
// 将每一个参数分解成key、值 对
        foreach ($params as $number => $value) {
            $parts = explode(":", $value);
            if (isset($parts[1])) {
                $keyParams[$parts[0]] = $parts[1];
            }
        }
// 重写参数
        $dispatcher->setParams($keyParams);
    }
    );

    $dispatcher->setEventsManager($eventManager);
    return $dispatcher;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di["url"] = function () {
    $url = new UrlResolver();

    $url->setBaseUri("/");

    return $url;
};


$di->set(
    "modelsManager", function () {
    return new \Phalcon\Mvc\Model\Manager();
});


$di->set('response', function () {
    $response = new \Phalcon\Http\Response();

    if (isset($_SERVER['HTTP_ORIGIN'])) {
//        if(in_array($_SERVER['HTTP_ORIGIN'] ,$HTTP_ORIGIN_array )){
        $response->setHeader("Access-Control-Allow-Origin", $_SERVER['HTTP_ORIGIN']);
        $response->setHeader("Access-Control-Allow-Methods", 'GET,PUT,POST');


//        }
    }
    $response->setHeader("Access-Control-Allow-Credentials", true);

    return $response;
});
$di->setShared('request', function () {
    return new \Phalcon\Http\Request();
});

$di->set('cookie', function () {
    $cookie = new \Phalcon\Http\Cookie();
});

$di->setShared('logger', function () {
    $logger = new Phalcon\Logger\Adapter\File(RUNTIME_DIR . 'log/' . date('YmdH') . '.txt');
    return $logger;
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () use ($di) {
    session_start();
    $csid = $_COOKIE["PHPSESSID"] ?? '';
    $auth = empty($csid) ? $di['request']->getHeader('sid') : $csid;
    $sid = empty($auth) ? $di['request']->get('sid') : $auth;
    $sid = empty($sid) ? md5(uniqid() . time() . uniqid() . \funch\Str::rand(5)) : $sid;
    setcookie('PHPSESSID', $sid);
    $session = new \app\web\Session($sid);
    $_SESSION = $session;
    return $session;
});



