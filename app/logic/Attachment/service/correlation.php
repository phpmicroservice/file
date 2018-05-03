<?php

namespace logic\Attachment\service;

use core\Sundry\Trace;
use logic\Attachment\model as thisModel;

/**
 * Class correlation
 * @package logic\Attachment\service
 */
class correlation extends \core\CoreService
{


    /**
     * 获取这个附件集的 附件id 列表
     * @param $array_id
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function get_attachment_user_id_list(int $array_id)
    {
        $list = thisModel\attachment_array_correlation::find([
            ' array_id = :arrayid: ',
            'bind' => [
                'arrayid' => $array_id
            ],
            'columns' => 'attachment_user_id'
        ]);


        return array_column($list->toArray(), 'attachment_user_id');
    }

    /**
     * 返回这个附件的使用集合列表
     * @param $au_id
     */
    public static function a_list4au_id($au_id)
    {
        $list = thisModel\attachment_array_correlation::query()
            ->where('attachment_user_id = :attachment_user_id:', ['attachment_user_id' => $au_id])
            ->columns('array_id')
            ->execute();
        return $list;
    }

    /**
     * 增加关系
     */
    public function add($array_id, $list)
    {
        foreach ($list as $value) {
            $data = [
                'array_id' => $array_id,
                'attachment_user_id' => $value
            ];
            $model = new thisModel\attachment_array_correlation();
            $model->setData($data);
            if ($model->save() === false) {
                pre($model->getMessage());
                Trace::add('error', $model->getMessage());
                return false;
            }
            # 增加完成,修改这个附件的状态
            $re = attachment_user::setStatus($value);
            if ($re === false) {
                return false;
            }

        }


        return true;
    }

    public function del($array_id, $list)
    {

        foreach ($list as $value) {
            if (empty($value)) {
                continue;
            }
            $data = [
                'array_id' => $array_id,
                'attachment_user_id' => $value
            ];
            $model = thisModel\attachment_array_correlation::findFirst([
                'attachment_user_id =:attachment_user_id: and array_id=:array_id:',
                'bind' => [
                    'attachment_user_id' => $value,
                    'array_id' => $array_id
                ]
            ]);

            if ($model->delete() === false) {
                return false;
            }
            # 增加完成,修改这个附件的状态
            $re = attachment_user::setStatusDel($value);
            if ($re === false) {
                return false;
            }

        }
        return true;
    }
}