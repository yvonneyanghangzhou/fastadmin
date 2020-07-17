<?php

namespace app\admin\controller\exam;

use app\common\controller\Backend;
use think\Db;

/**
 * 考生管理
 *
 * @icon fa fa-circle-o
 */
class ExamUser extends Backend
{
    use \app\admin\library\traits\ExamProperty;
    /**
     * ExamUser模型对象
     * @var \app\admin\model\exam\ExamUser
     */
    protected $model = null;
    /**
     * 快捷搜索的字段
     * @var string
     */

    protected $searchFields = 'username,name';
    /**
     * 无需鉴权的方法(需登录)
     * @var array
     */

    protected $noNeedRight = ['linkSelect'];
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\exam\ExamUser;
        $this->view->assign("examProject", $this->getExamProject());

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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->alias('stu')
                    ->field('stu.*,org.name as org_name,onl.online_time,link.exam_id')
                    ->join('base_org org', 'stu.org_id=org.id', 'left')
                    ->join('exam_project_user link', 'stu.username=link.username', 'left')
                    ->join('exam_online_time onl', 'stu.username=onl.username', 'left')
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->alias('stu')
                    ->field('stu.*,org.name as org_name,onl.online_time,link.exam_id')
                    ->join('base_org org', 'stu.org_id=org.id','left')
                    ->join('exam_project_user link', 'stu.username=link.username', 'left')
                    ->join('exam_online_time onl', 'stu.username=onl.username','left')
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $k=>$v) {
                // $onlineTime=Db::table('exam_online_time')->where('username', $v['username'])->value('use_time');
                // $v['online_time']=$onlineTime;
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        return $this->view->fetch();
    }

    /**
     * 获取考生类型下拉列表
     */
    public function getExamUserType()
    {
        return parent::selectpage($is_group='type');
    }

    /**
     * 获取年级下拉列表
     */
    public function getExamUserGrade()
    {
        return parent::selectpage($is_group='grade');
    }
    /**
     * 院系，专业，班级 三级联动
     */
    public function linkSelect()
    {
        $org_id = $this->request->get('org_id');
        $major = $this->request->get('major');
        $where=[];
        if ($org_id !== null) {
            //选中某院系
            if ($org_id) {
                $where['org_id']=$org_id;
            }
            $list = $this->model->where($where)->where('major', '<>', '')->distinct(true)->field('major as value,major as name')->select();

            //如果选择了专业，显示对应的班级
            if ($major) {
                $where['major']=$major;
                $list = $this->model->where($where)->distinct(true)->field('class_name as value,class_name as name')->select();
            }
            //专业选择了‘请选择’，未生效的时候是null，所以不能用else
            if ($major ==='') {
                $list = $this->model->where($where)->distinct(true)->field('class_name as value,class_name as name')->select();
            }
        } else {
            // 初始化显示所有院系列表
            $list = \app\admin\model\BaseOrg::where('pid', 1)->field('id as value,name')->select();
        }
        $this->success('', null, $list);
    }
}
