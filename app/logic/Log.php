<?php

namespace logic\logic;


use app\Base;
use Phalcon\Di;

class Log extends Base
{
    protected static $LogModel;

    /**
     * 创建日志
     * @param $RUN_TIME
     * @param $RUN_UNIQID
     * @param $data
     */
    public static function create($RUN_TIME, $RUN_UNIQID, $data)
    {
        $log = new model\Log();
        $log->setData($data);
        $log->run_uniqid = $RUN_UNIQID;
        $log->create_time = $RUN_TIME;
        $re = $log->save();
        if (!$re) {
            \core\Sundry\Trace::add('error', $log->getMessage());
            pre($log->getMessage());
        }
        self::$LogModel = $log;

    }

    public static function save($RUN_TIME, $RUN_UNIQID, $data)
    {
        if (!is_object(self::$LogModel)) {
            return false;
        }
        self::$LogModel->setData($data);
        if (1) {
            $modelsManager = Di::getDefault()->getShared('modelsManager');
            if (mt_rand(10, 99) == 20) {
                if ($modelsManager instanceof \Phalcon\Mvc\Model\Manager) {

                }
            }
        }
        try {
            $re = self::$LogModel->save();
            if ($re !== true) {
                pre(self::$LogModel->getMessage());
            }
        } catch (\Exception $exception) {

        }

    }

    public function log_info($id)
    {
        return \logic\Common\model\Log::findFirstById($id);
    }

    /**
     * 日志列表
     * @param $where
     * @param $page
     * @return \stdClass
     */
    public function log_list($where, $page, $row)
    {
        \core\Sundry\Trace::add('func', func_get_args());
        $builder = $this->modelsManager->createBuilder()
            ->from(\logic\Common\model\Log::class)
            ->columns('session_id,ip,url,get,id,run_uniqid,create_time,error,url_m');

        if (!empty($where['session_id'])) {
            $builder->andWhere('session_id=:session_id:', [
                'session_id' => $where['session_id']
            ]);
        }
        if (!empty($where['url_m'])) {
            $builder->andWhere('url_m=:url_m:', [
                'url_m' => $where['url_m']
            ]);
        }


        if ($where['error'] > -1 && $where['error'] !== '') {
            $builder->andWhere('error=:error:', [
                'error' => (int)$where['error']
            ]);
        }

        if (!empty($where['ip'])) {
            $builder->andWhere('ip=:ip:', [
                'ip' => $where['ip']
            ]);
        }

        $builder->orderBy("id DESC");
        $paginator = new \core\Paginator\Adapter\QueryBuilder(
            [
                "builder" => $builder,
                "limit" => $row,
                "page" => $page,
            ]
        );
        \core\Sundry\Trace::add('time', 107);
        return $paginator->getPaginate();
    }
}