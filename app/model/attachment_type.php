<?php

namespace app\model;


class attachment_type extends \pms\Mvc\Model
{

    public static function info4type($type)
    {
        $info = self::findFirstByname($type);
        if ($info) {
            return $info->toArray();
        }
        return [];

    }
}