<?php

namespace app\admin\controller\exam;

use app\common\controller\Backend;

/**
 * 题库管理
 *
 * @icon fa fa-circle-o
 */
class Library extends Backend
{
    
    /**
     * Library模型对象
     * @var \app\admin\model\exam\Library
     */
    protected $model = null;
    // 开关在点击的时候默认是只允许修改数据库的status字段的，如果我们开关不是status字段，我们需要在服务端对应的控制器中定义,多个字段以,进行分隔
    protected $multiFields="front_show";

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\exam\Library;
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("libraryTypeList", $this->model->getLibraryTypeList());
        $this->view->assign("frontShowList", $this->model->getFrontShowList());
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
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(['baseorg'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['baseorg'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as &$row) {
                $row->visible(['id','name','description','status','library_type','org_id','username','front_show','cover_image','weigh']);
                $row->visible(['baseorg']);
                $row->getRelation('baseorg')->visible(['name']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    /**
     * 搜索题库下拉列表
     */
    public function searchList()
    {
        $result = $this->model->field('id,name')->select();
        $data = ['searchlist' => $result];
        $this->success('', null, $data);
    }
}
