<?php


namespace logic\Attachment\service;

use core\Sundry\Trace;
use logic\Attachment\model as thisModel;

/**
 * 附件用户关系
 * Class attachment_user
 * @package logic\Attachment\service
 */
class attachment_user extends \core\CoreService
{


    /**
     * 讲这个附件设置为已使用
     * @param $au_id
     */
    public static function setStatus($au_id)
    {
        $model = thisModel\attachment_user::findFirst([
                'id =:id:', 'bind' => [
                    'id' => $au_id
                ]
            ]
        );
        if (!$model) {
            Trace::add('error', ['用户无权操作这个附件', $au_id]);
            return false;
        }
        $model->status = 1;
        if ($model->save() === false) {
            Trace::add('error', $model->getMessage());
            return false;
        }
        return true;
    }

    /**
     * 将这个附件设置为删除
     * @param $au_id
     * @return bool
     */
    public static function setStatusDel($au_id): bool
    {
        # 查看附件有无其他的使用者
        $list = correlation::a_list4au_id($au_id);
        if (empty($list)) {
            # 没有其他的使用者
            $model = thisModel\attachment_user::findFirst([
                    'id =:id:', 'bind' => [
                        'id' => $au_id
                    ]
                ]
            );
            if (!$model) {
                return false;
            }
            $model->status = -1;
            if ($model->save() === false) {
                return false;
            }
            return true;
        }
        return true;
    }

    public static function list4lid($list_id, $info = true)
    {
        $list = thisModel\attachment_user::query()
            ->inWhere('id', $list_id)
            ->execute();

        if ($info) {
            $lisss = array_column($list->toArray(), null, 'id');
        } else {
            $lisss = array_column($list->toArray(), 'id', 'id');
        }

        return $lisss;
    }


}