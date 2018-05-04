<?php

# web和cli通用的依赖加载
$di->setShared('dConfig', function () {
    #Read configuration
    $config = new Phalcon\Config(require ROOT_DIR . '/config/config.php');
    return $config;
});


/**
 * 本地缓存
 */
$di->setShared('cache', function () {
    // Create an Output frontend. Cache the files for 2 days
    $frontCache = new \Phalcon\Cache\Frontend\Data(
        [
            "lifetime" => 172800,
        ]
    );

    $cache = new \Phalcon\Cache\Backend\File(
        $frontCache, [
            "cacheDir" => CACHE_DIR,
        ]
    );
    return $cache;
});


/**
 * 全局缓存
 */
$di->setShared('gCache', function () use ($di) {
    // Create an Output frontend. Cache the files for 2 days
    $frontCache = new \Phalcon\Cache\Frontend\Data(
        [
            "lifetime" => 172800,
        ]
    );
    $op = [
        "host" => getenv('GCACHE_HOST'),
        "port" => getenv('GCACHE_PORT'),
        "auth" => getenv('GCACHE_AUTH'),
        "persistent" => getenv('GCACHE_PERSISTENT'),
        'prefix' => getenv('GCACHE_PREFIX'),
        "index" => getenv('GCACHE_INDEX')
    ];
    if (empty($op['auth'])) {
        unset($op['auth']);
    }
    $cache = new \pms\Cache\Backend\Redis($frontCache, $op);
    return $cache;
});


/**
 * session缓存
 */
$di->setShared('sessionCache', function () use ($di) {
    // Create an Output frontend. Cache the files for 2 days
    $frontCache = new \Phalcon\Cache\Frontend\Data(
        [
            "lifetime" => 172800,
        ]
    );
    # output($di['config']->cache, 'gCache');
    $op = [
        "host" => getenv('SESSION_CACHE_HOST'),
        "port" => getenv('SESSION_CACHE_PORT'),
        "auth" => getenv('SESSION_CACHE_AUTH'),
        "persistent" => getenv('SESSION_CACHE_PERSISTENT'),
        'prefix' => getenv('SESSION_CACHE_PREFIX'),
        "index" => getenv('SESSION_CACHE_INDEX')
    ];
    if (empty($op['auth'])) {
        unset($op['auth']);
    }
    $cache = new \Phalcon\Cache\Backend\Redis(
        $frontCache, $op);
    return $cache;
});


//注册过滤器,添加了几个自定义过滤方法
$di->setShared('filter', function () {
    $filter = new \Phalcon\Filter();
//    $filter->add('json', new \core\Filter\JsonFilter());
    return $filter;
});


/**
 * Database connection is created based in the parameters defined in the
 * configuration file
 */
$di["db"] = function () use ($di) {
    return new \Phalcon\Db\Adapter\Pdo\Mysql(
        [
            "host" => getenv('MYSQL_HOST'),
            "port" => getenv('MYSQL_PORT'),
            "username" => getenv('MYSQL_USERNAME'),
            "password" => getenv('MYSQL_PASSWORD'),
            "dbname" => getenv('MYSQL_DBNAME'),
            "options" => [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                \PDO::ATTR_CASE => \PDO::CASE_LOWER,
            ],
        ]
    );
};


$di->set(
    "proxyCS", function () {
    $client = new \pms\bear\ClientSync(get_env('PROXY_HOST'), get_env('PROXY_PROT'), 10);
    return $client;

});