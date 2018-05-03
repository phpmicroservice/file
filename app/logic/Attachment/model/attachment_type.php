<?php

namespace logic\Attachment\model;


class attachment_type extends \core\CoreModel
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