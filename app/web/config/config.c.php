<?php

use Phalcon\Config;

return new Config(
    [
        "database" => [
            "adapter" => "Mysql",
            "host" => get_env('app_mysql_host', '192.168.9.115'),
            "username" => get_env('app_mysql_username', 'root'),
            "password" => get_env('app_mysql_password', '123.369'),
            "dbname" => get_env('app_mysql_dbname', 'wugengji_demo'),
            "port" => get_env('app_mysql_port', '3307'),
        ],
        "cache_redis" => [
            "prefix" => get_env('app_cache_redis_prefix', 'cache'),
            "host" => get_env('app_cache_redis_host', '192.168.9.115'),
            "port" => get_env('app_cache_redis_port', 32769),
            "persistent" => get_env('app_cache_redis_persistent', true),
            "index" => get_env('app_cache_redis_index', 2),
            'auth' => get_env('app_cache_redis_auth', false),
        ],
        'app' => [
            'attachment_store' => get_env('app_attachment_store', '3')
        ],
        'APP_URL' => get_env('app_url', 'http://yikemeng_file.psd1412.com/'),


    ]# 结束
);
