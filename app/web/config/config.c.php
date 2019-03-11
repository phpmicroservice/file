<?php

use Phalcon\Config;

return new Config(
    [
        "cache_redis" => [
            "prefix" => \pms\get_env('app_cache_redis_prefix', 'cache'),
            "host" => \pms\get_env('app_cache_redis_host', '192.168.9.115'),
            "port" => \pms\get_env('app_cache_redis_port', 32769),
            "persistent" => \pms\get_env('app_cache_redis_persistent', true),
            "index" => \pms\get_env('app_cache_redis_index', 2),
            'auth' => \pms\get_env('app_cache_redis_auth', false),
        ],


    ]# 结束
);
