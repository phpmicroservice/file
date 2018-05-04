<?php

namespace app\model;

/**
 * 附件表
 * Class attachment
 * @package logic\Attachment\model
 */
class attachment extends \pms\Mvc\Model
{

    /**
     * 初始化
     */
    public function initialize()
    {
        $this->hasOne('saveStore', '\apps\main\upload\attachment_store', 'id', [
            'alias' => 'attachment_store']);
    }

    public function findFirst4id($id): attachment
    {
        return self::findFirst([
            'id=:id:', 'bind' => [
                'id' => $id
            ]
        ]);

    }
}