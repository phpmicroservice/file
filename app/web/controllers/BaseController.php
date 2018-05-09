<?php
/**
 * Created by PhpStorm.
 * User: Dongasai
 * Date: 2018/5/3
 * Time: 14:25
 */

namespace app\web\controllers;

use app\web\ReturnMsg;

class BaseController extends \Phalcon\Mvc\Controller
{

    protected $user_id;

    /**
     * 初始化方法
     */
    public function initialize()
    {
        $this->user_id = $this->session->get('uder_id');
    }

    /**
     * 获取Params传送的值
     * @param null $name
     */
    public function getParam($name = null, $defind = null)
    {

        $data = $this->router->getParams();
        if (is_null($name)) {
            return $data;
        }
        return isset($data[$name]) ? $data[$name] : $defind;
    }

    /**
     * 获取数据
     * @param type $parameter
     * @return boolean
     */
    protected final function getData($parameter)
    {
        $data = [];
        $type = '';
        foreach ($parameter as $k => $v) {
            $type = $v[0];

            if (isset($v[4])) {

            } else {
                # 不存在必选验证
                $v[4] = false;
            }
            if ($type === 'post') {
                if ($v[4]) {
                    if (!$this->request->hasPost($v[1])) {
                        return $this->translate->t('request-post-isset-field', [
                            'field' => $this->translate->t($v[1])
                        ]);
                    }
                }
                $data[$k] = $this->request->getPost($v[1], $v[2], $v[3]);
            } elseif ($type === 'get') {
                if ($v[4]) {
                    if (!$this->request->has($v[1])) {
                        return $this->translate->t('request-get-isset-field', [
                            'field' => $this->translate->t($v[1])
                        ]);
                    }
                }

                $data[$k] = $this->request->get($v[1], $v[2], $v[3]);
            } else {
                return $v[1] . 'is isset';
            }
        }
        if ($type === 'post') {
            unset($_POST);
        } elseif ($type === 'get') {
            unset($_GET);
        }

        return $data;
    }

    /**
     * 进行restful 处理后return
     * @param $re
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    protected function restful_return($re)
    {
        if (is_string($re)) {
            return $this->restful_error($re);
        }

        if ($re instanceof ReturnMsg) {
            $this->restful($re);
        }
        return $this->restful_success($re);
    }

    /**
     * 快捷设置一个错误的restful 返回
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    protected function restful_error($msg = '_error', $data = [])
    {
        return $this->restful(ReturnMsg::create(400, $msg, $data));
    }

    /**
     * restful 风格的输出
     * @param $msg_lang
     * @param $code
     * @param $data
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    protected function restful($msg_lang, $code = 200, $data = [])
    {

        if ($msg_lang instanceof ReturnMsg) {
            $msg_lang2 = $msg_lang->status;
            $code = $msg_lang->code;
            $data = $msg_lang->data;
        } else {
            $msg_lang2 = $msg_lang;
        }


        $msg = $msg_lang2;
        $this->response->setStatusCode($code, ReturnMsg::code2msg($code));
        $json = [
            'status' => $msg,
            'time' => date("Y-m-d H:i:s"),
            'run_uniqid' => RUN_UNIQID,
            'user_id' => $this->session->get('user_id'),
            'data' => $data
        ];

        $this->response->setJsonContent($json);
        $this->view->disable();
        return $this->response;

    }

    /**
     * 快捷设置一个成功的restful 返回
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    protected function restful_success($data = [])
    {
        if ($data instanceof ReturnMsg) {
            return $this->restful($data);
        }

        return $this->restful(ReturnMsg::create(200, '_success', $data));
    }


}