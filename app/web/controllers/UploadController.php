<?php

namespace app\web\controllers;

use apps\web\controllers\BaseController;
use app\web\ReturnMsg;

/**
 * 附件上传 upload
 * Class UploadController
 * @package apps\home\controllers
 */
class UploadController extends BaseController
{

    protected $attachment_store = [];


    /**
     * 上传文件
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function index()
    {
        $type = $this->request->getQuery('type');
        if (empty($type)) {
            $type = $this->request->getPost('type', 'string', 'pic1');
        }
        $auxiliary = $this->request->getPost('auxiliary', null, []);
        if ($this->request->isPost()) {
            $upload = new \logic\Attachment\Uploader();
            $fileInfos = $this->request->getUploadedFiles('file');
            if (!empty($fileInfos)) {
                $fileInfo = $fileInfos[0];
            } else {
                return $this->restful_return('empty-info');
            }
            $re = $upload->validation($type, $fileInfo);
            if ($re === false) {
                return $this->restful_error($re);
            }
            //进行上传
            $re38 = $upload->upFile($fileInfo, $this->user_id, $type, $auxiliary);
            return $this->restful_return($re38);
        } else {
            return $this->restful_return('_empty-info');
        }
    }

    /**
     * 上传文件 put 流上传
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function putup()
    {

        $type = $this->request->getQuery('type');
        if (empty($type)) {
            $type = $this->request->getPost('type', 'string', 'pic1');
        }
        if ($this->request->isPut()) {
            $upload = new \logic\Attachment\Uploader();
            $fileInfos = file_get_contents('php://input');
            var_dump($fileInfos);
            die();
            if (!empty($fileInfos)) {
                $fileInfo = $fileInfos[0];
            } else {
                return $this->restful_return('empty-info');
            }
            $re = $upload->validation($type, $fileInfo);
            if ($re === false) {
                return $this->restful_error($re);
            }
            //进行上传
            $re38 = $upload->upFile($fileInfo, $this->user_id, $type);
            return $this->restful_return($re38);
        } else {
            return $this->restful_return('_empty-info');
        }
    }


}
