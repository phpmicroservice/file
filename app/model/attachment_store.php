<?php

namespace app\model;

/**
 * 附件储存表
 * Class attachment_store
 * @package logic\Attachment\model
 */
class attachment_store extends \pms\Mvc\Model
{

    public $configuration; //配置信息
    public $driver; //驱动
    public $name; //名字
    public $id; //自增

    public function afterFetch()
    {

        return $this->configuration = unserialize($this->configuration);
    }

    public function setConfiguration()
    {
        dump(func_get_args());
    }
}