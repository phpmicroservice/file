<?php

namespace app\web\event;

use app\Base;


/**
 * applicatio:before Handle Request 的行为
 *
 * @author Dongasai
 */
class beforeHandleRequest extends Base
{
    /**
     * 运行的内容
     */
    public function run()
    {
        $this->load_config();#加载配置想
    }


    /**
     * 加载配置项
     */
    public function load_config()
    {
        # 读取配置信息
//      measure_pay_first

    }


}
