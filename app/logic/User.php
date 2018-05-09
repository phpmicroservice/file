<?php

namespace app\logic;

use app\Base;
use app\model\attachment_array;

/**
 * 用户相关
 * Class User
 * @package app\logic
 */
class User extends Base
{
    /**
     *
     * @param $userid
     * @param $index
     * @param $array_file_list
     */
    public function array_additional($userid, $index, $array_file_list)
    {

        # 验证是否可以进行关联
        $model = attachment_array::findFirst([
            'id =:id: and user_id =:user_id: and only = 0 ',
            'bind' => [
                'id' => $index,
                'user_id' => $userid
            ]
        ]);
        if (!($model instanceof attachment_array)) {
            return false;
        }
        # 建立附件及其附件集的关系
        $re = service\ArrayService::correlation($index, $array_file_list, true);
        if ($re === false) {
            return false;
        }
        return true;
    }

}