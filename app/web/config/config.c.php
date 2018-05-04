<?php

use Phalcon\Config;

return new Config(
    [
        "cache_redis" => [
            "prefix" => get_env('app_cache_redis_prefix', 'cache'),
            "host" => get_env('app_cache_redis_host', '192.168.9.115'),
            "port" => get_env('app_cache_redis_port', 32769),
            "persistent" => get_env('app_cache_redis_persistent', true),
            "index" => get_env('app_cache_redis_index', 2),
            'auth' => get_env('app_cache_redis_auth', false),
        ],


    ]# 结束
);
