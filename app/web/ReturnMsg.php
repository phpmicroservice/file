<?php

namespace app\web;


class ReturnMsg
{


    static protected $ReturnMsgClass;
    static protected $counter = 0;
    public $code = 200;
    public $status = 'ok';
    public $data = '';

    /**
     * 私有的初始化,只能在内部初始化
     * ReturnMsg constructor.
     */
    private function __construct()
    {
    }

    /**
     * 创建一个消息
     * @param $code 返回码
     * @param $status 状态信息
     * @param $data 数据
     */
    public static function create($code = 200, $status = false, $data = [])
    {
        self::$counter++;
        $obj = self::getObj();
        $obj->code = $code;
        if ($status) {
            $obj->status = $status;
        } else {
            $obj->status = self::code2msg($code);
        }

        $obj->data = $data;
        return $obj;
    }

    private static function getObj(): ReturnMsg
    {
        if (is_null(self::$ReturnMsgClass)) {
            self::$ReturnMsgClass = new ReturnMsg();
        }
        return self::$ReturnMsgClass;
    }

    public static function code2msg($code)
    {
        $c4s = [
            '200' => '_success',
            '403' => '_forbidden',
            '404' => '_not-found',
        ];

        if (isset($c4s[$code])) {
            return $c4s[$code];
        }
        return $code;
    }

}