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
        $this->view->assign("typeList", $this->model->getTypeList());
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
            //自定义搜索栏中的所有字段值
            $filter = $this->request->get("filter", '');
            $online_time_op = $this->request->get("op");
            $online_time_op = (array)json_decode($online_time_op, true);
            $filter = (array)json_decode($filter, true);
            $filter = $filter ? $filter : [];
            //关联考试项目查询
            $link_search_exam=(isset($filter['link.exam_id']) && $filter['link.exam_id'])?$filter['link.exam_id']:'';

            // //关联、取消关联操作的考试id
            // $link_exam_id=(isset($filter['link_exam_id']) && $filter['link_exam_id'])?$filter['link_exam_id']:'';
            // $do_action=(isset($filter['do_action']) && $filter['do_action'])?$filter['do_action']:'';

            // //如果有关联操作
            // if ($link_exam_id && $do_action) {
            //     $this->$do_action($filter);
            // } else {
            //     unset($filter['link_exam_id']);
            //     unset($filter['do_action']);
            // }
            //如果用来查询的字段不在数据库中，需要排除掉 $exceptionField
            $exceptionField=['do_action','link_exam_id','onl.online_time'];
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, $exceptionField);
            $map=[];
            //如果有在线时长的检索，单独处理，为了获取大于或者小于等符号和数值
            if (isset($filter['onl.online_time']) && $filter['onl.online_time']) {
                //<小于符号接收值时为空，=和>正常
                if(!$online_time_op){
                    $map['onl.online_time']=[['<',$filter['onl.online_time']],['null',''],'or'];
                }else{
                    $map['onl.online_time']=[$online_time_op['onl.online_time'],intval($filter['onl.online_time'])];
                }
            }
            //如果有关联考试查询
            if ($link_search_exam) {
                $total = $this->model
                ->alias('stu')
                ->field('stu.*,org.name as org_name,onl.online_time,link.exam_id')
                ->join('base_org org', 'stu.org_id=org.id', 'left')
                ->join('exam_project_user link', 'stu.username=link.username', 'left')
                ->join('exam_online_time onl', 'stu.username=onl.username', 'left')
                ->where($where)
                ->where($map)
                ->order($sort, $order)
                ->count();

                $list = $this->model
                ->alias('stu')
                ->field('stu.*,org.name as org_name,onl.online_time,link.exam_id')
                ->join('base_org org', 'stu.org_id=org.id', 'left')
                ->join('exam_project_user link', 'stu.username=link.username', 'left')
                ->join('exam_online_time onl', 'stu.username=onl.username', 'left')
                ->where($where)
                ->where($map)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            } else {
                $total = $this->model
                ->alias('stu')
                ->field('stu.*,org.name as org_name,onl.online_time')
                ->join('base_org org', 'stu.org_id=org.id', 'left')
                ->join('exam_online_time onl', 'stu.username=onl.username', 'left')
                ->where($where)
                ->where($map)
                ->order($sort, $order)
                ->count();

                $list = $this->model
                ->alias('stu')
                ->field('stu.*,org.name as org_name,onl.online_time')
                ->join('base_org org', 'stu.org_id=org.id', 'left')
                ->join('exam_online_time onl', 'stu.username=onl.username', 'left')
                ->where($where)
                ->where($map)
                ->order($sort, $order)
                // ->fetchSql(true)
                ->limit($offset, $limit)
                ->select();
            }

            foreach ($list as $row) {
                //  $row->visible(['id','avatar','username','name','org_id','major','grade','class_name','type']);
               // $row->visible(['baseorg']);
              //  $row->getRelation('baseorg')->visible(['name']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    /**
     * 关联或者取消关联的弹层 select 下拉考试项目选址页面
     */
    public function linkExamSelect()
    {
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        //index页面自定义查询提交的字段值
        $params=$this->request->post();
        $this->success("", null, ['searchField' => $params]);
    }
    /**
     * 关联考试
     */
    public function linkExam()
    {
        //关联layer层接收参数值
        $formdata=$this->request->get('formdata');
        //在线时长select 选择= < > 的符号
        $online_time_op=$this->request->get('online_time_op');
        parse_str($formdata, $field);
        $filter = $field ? $field : [];
        $where=[];
        $searchField=['org_id','major','class_name','grade','type'];
        $where = array_filter(array_intersect_key($filter, array_flip($searchField)));

        if ($filter['search_exam_id']) {
            $where['link.exam_id']=$filter['search_exam_id'];
        }

        if ($filter['online_time']) {
            if($online_time_op === '<'){
                $where['onl.online_time']=[[$online_time_op,$filter['online_time']],['null',''],'or'];
            }else{
                $where['onl.online_time']=[$online_time_op,intval($filter['online_time'])];
            }
        }
        if ($filter['student_type']) {
            $where['type']=$filter['student_type'];
        }
        // dd($where);
        //关联考试项目id
        $link_exam_id=(isset($filter['link_exam_id']) && $filter['link_exam_id'])?$filter['link_exam_id']:'';

        if (!$link_exam_id) {
            $this->error('必须关联绑定考试项目');
        }
        
        $list = $this->model
                    ->alias('stu')
                    ->field('stu.*,onl.online_time,link.exam_id')
                    ->join('exam_project_user link', 'stu.username=link.username', 'left')
                    ->join('exam_online_time onl', 'stu.username=onl.username', 'left')
                    ->where($where)
                    ->select();

        $list = collection($list)->toArray();
        $total=count($list);
        //将查询出来的学号插入到关联表中
        $link_num=0;
        foreach ($list as $v) {
            $data['username']=$v['username'];
            $data['exam_id']=$link_exam_id;
            $data['create_time']=date('Y-m-d H:i:s', time());
            DB::table('exam_project_user')->data($data)->insert();
            $link_num++;
        }
        if(!$total){
            $this->error('当前未关联任何数据 ');
        }else{
            $this->success('关联考试成功，共计 '.$link_num.' 人 ');
        }
    }
    /**
     * 取消关联考试
     */
    public function unlinkExam()
    {
        //取消关联layer层接收参数值
        $formdata=$this->request->get('formdata');
        parse_str($formdata, $field);
        $filter = $field ? $field : [];
        $where=[];
        $searchField=['org_id','major','class_name','grade','type'];
        $where = array_filter(array_intersect_key($filter, array_flip($searchField)));

        if ($filter['search_exam_id']) {
            $where['link.exam_id']=$filter['search_exam_id'];
        }
        if ($filter['online_time']) {
            $where['onl.online_time']=$filter['online_time'];
        }
        if ($filter['student_type']) {
            $where['type']=$filter['student_type'];
        }
        
        //取消关联考试项目id
        $link_exam_id=(isset($filter['link_exam_id']) && $filter['link_exam_id'])?$filter['link_exam_id']:'';

        if (!$link_exam_id) {
            $this->error('必须选中考试项目');
        }
        $list = $this->model
                    ->alias('stu')
                    ->field('stu.*,onl.online_time,link.exam_id')
                    ->join('exam_project_user link', 'stu.username=link.username', 'left')
                    ->join('exam_online_time onl', 'stu.username=onl.username', 'left')
                    ->where($where)
                    ->select();

        $list = collection($list)->toArray();
        $total=count($list);
        //将查询出来的学号从关联表中删除
        $unlink_num=0;
        foreach ($list as $v) {
            $data['username']=$v['username'];
            $data['exam_id']=$link_exam_id;
            $id=DB::table('exam_project_user')->where($data)->value('id');
            if ($id) {
                DB::table('exam_project_user')->delete($id);
                $unlink_num++;
            } else {
                continue;
            }
        }
        if(!$total){
            $this->error('当前未取消关联任何数据 ');
        }else{
            $this->success('取消关联成功，共计 '.$unlink_num.' 人');
        }
    }

    /**
     * 获取年级下拉列表
     */
    public function getExamUserGrade()
    {
        return $this->selectpage($group='grade');
    }
    /**
     * 院系，专业，班级 三级联动
     */
    public function linkSelect()
    {
        $org_id = $this->request->get('org_id');
        $major = $this->request->get('major');
        $where=[];
        $list = null;
        if ($org_id !== null) {
            //选中院系
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

    /**
     * Selectpage的实现方法
     *
     * 当前方法只是一个比较通用的搜索匹配,请按需重载此方法来编写自己的搜索逻辑,$where按自己的需求写即可
     * 这里示例了所有的参数，所以比较复杂，实现上自己实现只需简单的几行即可
     * $group 是否需要分组，去重
     */
    public function selectpage($group ='')
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'htmlspecialchars']);

        //搜索关键词,客户端输入以空格分开,这里接收为数组
        $word = (array)$this->request->request("q_word/a");
        //当前页
        $page = $this->request->request("pageNumber");
        //分页大小
        $pagesize = $this->request->request("pageSize");
        //搜索条件
        $andor = $this->request->request("andOr", "and", "strtoupper");
        //排序方式
        $orderby = (array)$this->request->request("orderBy/a");
        //显示的字段
        $field = $this->request->request("showField");
        //主键
        $primarykey = $this->request->request("keyField");
        //主键值
        $primaryvalue = $this->request->request("keyValue");
        //搜索字段
        $searchfield = (array)$this->request->request("searchField/a");
        //自定义搜索条件
        $custom = (array)$this->request->request("custom/a");
        //是否返回树形结构
        $istree = $this->request->request("isTree", 0);
        $ishtml = $this->request->request("isHtml", 0);
        if ($istree) {
            $word = [];
            $pagesize = 99999;
        }
        $order = [];
        foreach ($orderby as $k => $v) {
            $order[$v[0]] = $v[1];
        }
        $field = $field ? $field : 'name';

        //如果有primaryvalue,说明当前是初始化传值
        if ($primaryvalue !== null) {
            $where = [$primarykey => ['in', $primaryvalue]];
            $pagesize = 99999;
        } else {
            $where = function ($query) use ($word, $andor, $field, $searchfield, $custom) {
                $logic = $andor == 'AND' ? '&' : '|';
                $searchfield = is_array($searchfield) ? implode($logic, $searchfield) : $searchfield;
                foreach ($word as $k => $v) {
                    $query->where(str_replace(',', $logic, $searchfield), "like", "%{$v}%");
                }
                if ($custom && is_array($custom)) {
                    foreach ($custom as $k => $v) {
                        if (is_array($v) && 2 == count($v)) {
                            $query->where($k, trim($v[0]), $v[1]);
                        } else {
                            $query->where($k, '=', $v);
                        }
                    }
                }
            };
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
        }
        $list = [];
        if ($group) {
            $total = $this->model->where($where)->group($group)->count();
        } else {
            $total = $this->model->where($where)->count();
        }
        if ($total > 0) {
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $datalist = $this->model->where($where)
                ->order($order)
                ->page($page, $pagesize)
                ->field($this->selectpageFields)
                ->select();
            if ($group) {
                $datalist = $this->model->where($where)
                ->order($order)
                ->page($page, $pagesize)
                ->field($this->selectpageFields)
                ->group($group)
                ->select();
            } else {
                $datalist = $this->model->where($where)
                ->order($order)
                ->page($page, $pagesize)
                ->field($this->selectpageFields)
                ->select();
            }
            foreach ($datalist as $index => $item) {
                unset($item['password'], $item['salt']);
                $list[] = [
                    $primarykey => isset($item[$primarykey]) ? $item[$primarykey] : '',
                    $field      => isset($item[$field]) ? $item[$field] : '',
                    'pid'       => isset($item['pid']) ? $item['pid'] : 0
                ];
            }
            if ($istree && !$primaryvalue) {
                $tree = Tree::instance();
                $tree->init(collection($list)->toArray(), 'pid');
                $list = $tree->getTreeList($tree->getTreeArray(0), $field);
                if (!$ishtml) {
                    foreach ($list as &$item) {
                        $item = str_replace('&nbsp;', ' ', $item);
                    }
                    unset($item);
                }
            }
        }
        //这里一定要返回有list这个字段,total是可选的,如果total<=list的数量,则会隐藏分页按钮
        return json(['list' => $list, 'total' => $total]);
    }
}
