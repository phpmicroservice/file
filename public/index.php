<?php
use Phalcon\Mvc\Application;
require '../vendor/phpmicroservice/pms-frame/src/function.php';


define("SERVICE_NAME", "file");# 设置服务名字
define('ROOT_DIR', dirname(__DIR__));

define('WEB_DIR', ROOT_DIR . '/app/web/');
define('INDEX_DIR', __DIR__);
define('LANG_DIR', ROOT_DIR . '/language/');
define('EXTENDS_DIR', ROOT_DIR . '/extends/');
define('RUNTIME_DIR', ROOT_DIR . '/runtime/');
define('CACHE_DIR', ROOT_DIR . '/runtime/cache/');
define('APP_DEBUG', boolval(get_env("APP_DEBUG", 1)));
define('RUN_UNIQID', uniqid());
define('APP_SECRET_KEY', get_env("APP_SECRET_KEY", '77ZqeAppoLvZ1Fsc'));
define('RUN_TIME', time());
define('RUN_MICROTIME', microtime(true));
define('NO_OUTPUT', 0);

# 加载扩展
require WEB_DIR . '/vendor/autoload.php';
# 加载函数库
require ROOT_DIR . '/tool/function.php';
error_reporting(E_ALL);


Header('run_uniqid:' . RUN_UNIQID);

Header('Access-Control-Allow-Origin:*');
Header('Access-Control-Allow-Credentials:true');
Header("Access-Control-Allow-Headers:Origin,No-Cache,X-Requested-With,If-Modified-Since,Pragma,Last-Modified,Cache-Control,Expires,Content-Type,Authorized");


try {
    /**
     * Include services
     */
    require WEB_DIR . "/config/services.php";
    /**
     * Handle the request
     */
    $application = new Application();
    $eventsManager = new \Phalcon\Events\Manager();
    /**
     * Assign the DI
     */
    $application->setDI($di);


    # 设置 Boot 事件
    $BootEvents = new \app\web\BootEvents();
    $eventsManager->attach('application:boot', $BootEvents);
    $applicationE = new \app\web\event\application();
    $eventsManager->attach('application:beforeSendResponse', $applicationE);
    $application->setEventsManager($eventsManager);
    # 进行调度处理
    $response = $application->handle();
    echo $response->getContent();
} catch (Exception $e) {
    if (APP_DEBUG) {
        echo $e->getMessage();
        echo "<br/>";
        echo $e->getFile() . ' :  ' . $e->getLine();
        echo "<br/>";
    } else {
        echo "系统错误!" . RUN_UNIQID;
    }


}
