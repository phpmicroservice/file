<?php
/**
 * Created by PhpStorm.
 * User: Dongasai
 * Date: 2017/11/11
 * Time: 14:07
 */

namespace logic\Common;


class Trace extends \core\CoreService
{
    public function listts($where, $page)
    {
        $builder = $this->modelsManager->createBuilder()
            ->from(\logic\Common\model\trace::class);
        if (isset($where['module']) && !empty($where['module'])) {
            $builder->andWhere('module = :module:', [
                'module' => $where['module']
            ]);
        }
        if (isset($where['controller']) && !empty($where['controller'])) {
            $builder->andWhere('controller = :controller:', [
                'controller' => $where['controller']
            ]);
        }
        if (isset($where['action']) && !empty($where['action'])) {
            $builder->andWhere('action = :action:', [
                'action' => $where['action']
            ]);
        }
        $paginator = new \Phalcon\Paginator\Adapter\QueryBuilder(
            [
                "builder" => $builder,
                "limit" => 20,
                "page" => $page,
            ]
        );
        return $paginator->getPaginate();
    }

}