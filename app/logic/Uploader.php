<?php

namespace app\logic;

use app\Base;
use app\Validation\Upload;


/**
 * 文件上传
 * @author Dongasai 1514582970@qq.com
 *
 */
class Uploader extends Base
{

    private $attachment_store; //储存配置
    private $storeInfo = []; //储存结果
    private $file; //文件上传对象
    private $stateInfo; //上传状态信息,

    /**
     * 构造函数
     * @param string $fileField 表单名称
     * @param array $config 配置项
     * @param bool $base64 是否解析base64编码，可省略。若开启，则$fileField代表的是base64编码的字符串表单名
     */
    public function __construct()
    {
        //读取配置信息
        $data = \app\model\attachment_store::findFirst('id = ' . $this->dConfig->app->attachment_store);
        if ($data) {
            $this->attachment_store = $data->toArray();
        } else {
            throw new \Phalcon\Exception('attachment_store_config error');
        }

    }

    /**
     * 进行验证
     * @param $fileInfo
     * @return mixed
     */
    public function validation($type, $fileInfo)
    {

        $rules = \app\model\attachment_type::info4type($type);
        if (empty($rules)) {
            throw new \Phalcon\Exception('config error' . $type);
        }

        //上传之前进行文件验证
        $validation = new Upload();
        $validation->setRules($rules);

        $validation->setStore($this->attachment_store);

        $validation->validate(['file' => $fileInfo]);

        if ($validation->isError()) {
            return $validation->getMessage();
        }
        return true;

    }


    /**
     * 上传文件
     * @param $files
     * @param $uid
     * @param $type
     * @param array $auxiliary
     * @return bool|\app\model\attachment_user|string
     */
    public function upFile($files, $uid, $type, $auxiliary = [])
    {
        $re = $this->saveFile($files);
        $re76 = $this->user_save($re, $uid, $files, $type, $auxiliary = []);
        return $re76;

    }

    /**
     * 保存文件
     */
    protected function saveFile(\Phalcon\Http\Request\File $file)
    {

        # 先进行文件唯一验证　
        $re85 = $this->uq_check($file);
        if ($re85 !== true) {
            return $re85;
        }
        //进行文件储存
        $save_config = [
            'file_store_path' => $this->get_file_store_path($file),
            'store_path' => $this->getstore_name($file)
        ];

        //实例化文件储存驱动
        $driverName = ('app\\logic\\storeDriver\\' . ucfirst($this->attachment_store['driver']));
        $storeDriver = new $driverName($this->attachment_store);
        $reInfo = $storeDriver->add($file->getTempName(), $save_config['file_store_path'], $save_config['store_path']);

        if (is_string($reInfo)) {
            return $reInfo;
        }

        //文件储存完毕.数据储存到数据库
        $data = [
            'type' => $file->getExtension(),
            'size' => $file->getSize(),
            'create_time' => time(),
            'status' => 1,
            'savepath' => $reInfo['savePath'],
            'saveStore' => $this->attachment_store['id'],
            'md5' => $reInfo['md5']
        ];

        $attachmentModel = new \app\model\attachment();
        try {
            $re = $attachmentModel->save($data);
        } catch (\Exception $e) {

            //保存失败!
            $storeDriver->rm($reInfo['savePath']);
            return '_model error';
        }
        # 储存数据库成功
        return $attachmentModel;

    }

    /**
     * 进行文件唯一性验证
     * @param \Phalcon\Http\Request\File $file
     */
    private function uq_check(\Phalcon\Http\Request\File $file)
    {

        $MD5 = md5_file($file->getTempName());
        $re = \app\model\attachment::findFirst(
            ['conditions' => 'md5 = "' . $MD5 . '"' . ' and ' .
                'saveStore = ' . $this->attachment_store['id']]
        );
        if (!$re) {
            return true;
        } else {
            return $re;
        }

    }

    /**
     * 获取文件实际保存地址
     */
    public function get_file_store_path(\Phalcon\Http\Request\File $file): string
    {
        return date('Y') . '/' . date('m') . '/' . date('d');
    }

    /**
     * 获取文件保存 名字
     * @param \Phalcon\Http\Request\File $file
     */
    public function getstore_name(\Phalcon\Http\Request\File $file): string
    {
        return uniqid() . '.' . $file->getExtension();
    }


    /**
     * 用户保存 文件保存
     * @param \app\model\attachment $model_data
     * @param $uid
     * @param \Phalcon\Http\Request\File $file
     * @param $type
     * @param array $auxiliary
     * @return bool|\app\model\attachment_user|string
     */
    public function user_save(\app\model\attachment $model_data, $uid, \Phalcon\Http\Request\File $file, $type, $auxiliary = [])
    {
        # 验证是否已经存在
        $info = \app\model\attachment_user::query()
            ->where('user_id = :user_id:', ['user_id' => $uid])
            ->andWhere('attachment_id = :attachment_id:', ['attachment_id' => $model_data->id])
            ->andWhere('type = :type:', ['type' => $type])
            ->execute();

        if (!empty($info->toArray())) {
            # 存在信息,继续返回
            return $info->toArray()[0];
        }


        $data = [
            'attachment_id' => $model_data->id,
            'user_id' => $uid,
            'primitive_name' => $file->getName(),
            'type' => $type,
            'status' => 0,
            'create_time' => time(),
            'auxiliary' => $auxiliary
        ];
        $model_attachment_user = new \app\model\attachment_user();
        $model_attachment_user->setData($data);
        if ($model_attachment_user->save() === false) {
            return $model_attachment_user->getMessage();
        }
        return $model_attachment_user;

    }

    /**
     * 获取当前上传成功文件的各项信息
     * @return array
     */
    public function getFileInfo()
    {

        return array(
            "state" => $this->stateInfo,
            "savepath" => $this->storeInfo['savePath'],
            "saveStore" => $this->attachment_store->id,
            "title" => $this->fileName,
            "original" => $this->oriName,
            "type" => $this->fileType,
            "size" => $this->fileSize
        );
    }

}
