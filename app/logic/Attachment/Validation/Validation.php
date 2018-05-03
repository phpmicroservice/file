<?php

namespace logic\Attachment\Validation;

/**
 *
 * @author Dongasai
 */
class Validation extends \core\CoreValidation
{

    protected $store_config; //储存空间信息

    /**
     * 设置储存空间
     * @param type $config
     */

    public function setStore($config)
    {

        $this->store_config = $config;
    }

    /**
     *  设置验证规则
     * @param type $array
     */
    public function setRules($array)
    {
        /**
         *     "pathFormat" => $CONFIG['imagePathFormat'],
         * "maxSize"    => $CONFIG['imageMaxSize'],
         * "allowFiles" => $CONFIG['']
         */
        if (!$array['maxSize']) {
            return "出错了!";
        }
        $rule = [
            'file' => [
                'file2' => [
                    'maxSize' => $array['maxSize'],
                    'messageSize' => '大小超限!',
                ]
            ],
        ];
        # 类型
        if (isset($array['AllowFiles'])) {
            $rule['file']['file2']['allowedTypes'] = $this->type2allowed($array['AllowFiles']);
            $rule['file']['file2']['messageType'] = '类型不对';
        }
        # 宽高
        if (isset($array['maxResolution'])) {
            $rule['file']['file2']['maxResolution'] = $array['maxResolution'];
            $rule['file']['file2']['messageMaxResolution'] = '宽高超限!';
        }

        $this->analysisRule($rule);
    }

    /**
     * 类型转换
     */
    private function type2allowed($typestring)
    {
        $typeArr = explode(',', $typestring);

        $arr = [];
        foreach ($typeArr as $v) {
            if (strpos($v, '.') !== false) {
                $suffix = strtolower(substr($v, strpos($v, '.') + 1));
                $arr[] = \tool\File::suffix2mime($suffix);
            } else {
                $arr[] = \tool\File::suffix2mime($v);

            }
        }
        return $arr;
    }


    /**
     * 进行唯一性验证,true是唯一的 不唯一的返回旧的信息
     * @param \Phalcon\Http\Request\File $file
     * @return bool
     */
    public function validHash(\Phalcon\Http\Request\File $file)
    {
        $MD5 = md5_file($file->getTempName());
        $re = attachment::findFirst(
            ['conditions' => 'md5 = "' . $MD5 . '"' . ' and ' .
                'saveStore = ' . $this->store_config['id']]
        );
        if (!$re) {
            return true;
        } else {
            return $re;
        }
    }

    /**
     * 验证文件
     * @param type $file
     */
    public function check($file)
    {

    }

}
