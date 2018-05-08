<?php

namespace app\logic\service;

use app\model as thisModel;

class attachment
{

    /**
     *
     * @param $list_id
     * @return array
     */
    public static function list4lid($list_id)
    {
        $list = thisModel\attachment::query()
            ->inWhere('id', $list_id)
            ->execute();
        return array_column($list->toArray(), ['id', 'size', 'type', 'md5'], 'id');
    }
}