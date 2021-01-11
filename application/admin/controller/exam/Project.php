<?php

namespace app\admin\controller\exam;

use app\common\controller\Backend;

/**
 * 考试管理
 *
 * @icon fa fa-circle-o
 */
class Project extends Backend
{
    
    /**
     * Project模型对象
     * @var \app\admin\model\exam\Project
     */
    protected $model = null;
    /**
     * 快捷搜索的字段
     * @var string
     */

    protected $searchFields = 'name';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\exam\Project;
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("allowMockList", $this->model->getAllowMockList());
        $this->view->assign("enableCommitmentList", $this->model->getEnableCommitmentList());
        $this->view->assign("allowPrintCommitmentList", $this->model->getAllowPrintCommitmentList());
        $this->view->assign("allowPrintCertificateList", $this->model->getAllowPrintCertificateList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            //获取状态选项卡值
            $filter = $this->request->get("filter", '');
            $filter = (array)json_decode($filter, true);
            $filter = $filter ? $filter : [];
            //如果是按考试状态查询
            $status=(isset($filter['status']) && $filter['status'])?$filter['status']:'';
            $statusWhere=[];
            if ($status) {
                $now=date('Y-m-d H:i:s');
                if ($status ==1) {
                    $statusWhere['start_date']=['>=',$now];
                } elseif ($status ==2) {
                    $statusWhere['start_date']=['<',$now];
                    $statusWhere['close_date']=['>',$now];
                } elseif ($status ==3) {
                    $statusWhere['close_date']=['<=',$now];
                }
            }
            //如果用来查询的字段不在数据库中，需要排除掉 $exceptionField
            $exceptionField=['status'];
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null,null,$exceptionField);

            $total = $this->model
                    ->with(['baseorg'])
                    ->where($where)
                    ->where($statusWhere)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['baseorg'])
                    ->where($where)
                    ->where($statusWhere)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','name','org_id','duration','start_date','close_date','allow_mock','total_times','pass_line']);
                $row->visible(['baseorg']);
                $row->getRelation('baseorg')->visible(['name']);
            }
            
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
}
