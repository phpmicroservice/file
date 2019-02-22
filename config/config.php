<?php
/**
 * Created by PhpStorm.
 * User: Dongasai
 * Date: 2018/3/31
 * Time: 22:26
 */
return [
    'config_init' => false,
    'server_reg' => true,
    'session' => true,
    'ready' => false,
    'codeUpdata' => [
        '/app/',
        '/tool/',
        '/start/',
        '/config/',
        '/vendor/phpmicroservice/pms-frame/src/',
    ],
    'app' => [
        'attachment_store' => \pms\get_env('app_attachment_store', '1')
    ],
    'APP_URL' => \pms\get_env('APP_URL', \pms\get_env('VIRTUAL_HOST', 'http://yikemeng_file.psd1412.com/')),
];