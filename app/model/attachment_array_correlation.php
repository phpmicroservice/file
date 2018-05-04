<?php


namespace app\model;

/**
 * 附件与附件集合 关系
 * Class attachment_array_correlation
 * @package logic\Attachment\model
 */
class attachment_array_correlation extends \pms\Mvc\Model
{

    /**
     * 获取 附件列表 索引的
     * @param $array_id
     * @return array
     */
    public static function get_list_index($array_id)
    {
        $list = self::query()->where('array_id = :array_id:', ['array_id' => $array_id])->execute();
        return array_column($list, 'attachment_user_id');;
    }
}