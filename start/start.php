<?php
//include './logo.php';
echo "开始主程序! \n";
define("APP_SERVICE_NAME", "file");# 设置服务名字
define('ROOT_DIR', dirname(__DIR__));
require ROOT_DIR . '/vendor/autoload.php';
# 进行一些项目配置
define('APP_SECRET_KEY', \pms\get_env("APP_SECRET_KEY"));

//注册自动加载
$loader = new \Phalcon\Loader();
$loader->registerNamespaces(
    [
        'app' => ROOT_DIR . '/app/',
    ]
);
$loader->register();

$server = new \pms\Server('0.0.0.0', 9502, SWOOLE_BASE, SWOOLE_SOCK_TCP, [
    'daemonize' => false,
    'reactor_num_mulriple' => 1,
    'worker_num_mulriple' => 1,
    'task_worker_num_mulriple' => 1
]);
$guidance = new \app\Guidance();
$server->onBind('onWorkerStart', $guidance);
$server->onBind('beforeStart', $guidance);
$server->start();
