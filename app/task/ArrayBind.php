<?php

namespace app\task;

use pms\Task\TaskInterface;
use app\logic\attachmentArray;

/**
 * 附件集合创建并绑定
 * Class ArrayBind
 * @package app\task
 */
class ArrayBind extends \pms\Task\TxTask implements TaskInterface
{
    public function end()
    {

    }

    /**
     * 在依赖处理之前执行,没有返回值
     */
    protected function b_dependenc()
    {
        $data = $this->getData();

    }

    /**
     * 事务逻辑内容,返回逻辑执行结果,
     * @return bool false失败,将不会再继续进行;true成功,事务继续进行
     */
    protected function logic()
    {
        $data = $this->getData();
        $attachmentArray = new attachmentArray();
        $server_name = $data['server_name'];
        $index = $attachmentArray->create_array($data['user_id'], $data['remark'] ?? '', $data['only'] ?? 1, $server_name);
        # service\ArrayService::create_array($user_id, $remark, 0,$server_name);
        if (is_int($index)) {
            # 成功
        }else{
            return $index;
        }
        $attachmentArray = new attachmentArray();
        $bl = $attachmentArray->array_status01($index, $server_name);
        return $bl;
    }
}