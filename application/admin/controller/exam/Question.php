<?php

namespace app\admin\controller\exam;

use app\common\controller\Backend;
use think\Config;
use think\Db;
// 导入excel

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

/**
 * 试题管理
 *
 * @icon fa fa-circle-o
 */
class Question extends Backend
{
    /**
     * Question模型对象
     * @var \app\admin\model\exam\Question
     */
    protected $model = null;
    protected $question_type=[];
    // protected $importHeadType = 'name';//以字段名为EXCEL表格首行
    protected $searchFields = 'title_content';    // 快捷搜索的字段


    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\exam\Question;
        $this->view->assign("typeList", $this->model->getTypeList());
        //模板配置项
        $this->assignconfig("typeList", $this->model->getTypeList());
        $this->view->assign("levelList", $this->model->getLevelList());
        $this->view->assign("trueOrFalseList", $this->model->getTrueOrFalseList());
        $this->question_type=Config::get('exam.question_type');
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
                    ->with(['examlibrary','examquestiontag'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['examlibrary','examquestiontag'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as &$row) {
                $row->visible(['id','title_content','option_content','answer_content','type','level','blanks_num','creator','createtime']);
                $row->visible(['examlibrary']);
                $row->getRelation('examlibrary')->visible(['name']);
                $row->visible(['examquestiontag']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $result = false;
                
                //选题题标准答案转换，将人阅读格式的答案转换为数据库格式（必须是大写格式）如，将ABC转换为111，将ACD转换为1011
                if ($params['type'] ==  $this->question_type['true_false_question']) {
                    $params['answer_content'] = $params['true_false_answer'];
                } elseif ($params['type'] == $this->question_type['single_choice'] || $params['type'] == $this->question_type['multiple_choice']) {
                    $params['answer_content'] = answer_abc2bin(strtoupper(trim(strip_tags($params['input_answer']))));
                } else {
                    $params['answer_content'] = $params['input_answer'];
                }
                
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }
    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        //将标准答案answer_content字段数据库格式的试题答案转换为人阅读格式的答案，如将0010转换为C 
        // if ($params['type'] ==  $this->question_type['true_false_question']) {
        //     $params['answer_content'] = $params['true_false_answer'];
        // } elseif ($params['type'] == $this->question_type['single_choice'] || $params['type'] == $this->question_type['multiple_choice']) {
        //     $params['answer_content'] = answer_abc2bin(strtoupper(trim(strip_tags($params['input_answer']))));
        // } else {
        //     $params['answer_content'] = $params['input_answer'];
        // }

        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = false;
                //选题题标准答案转换，将人阅读格式的答案转换为数据库格式（必须是大写格式）如，将ABC转换为111，将ACD转换为1011
                if ($params['type'] ==  $this->question_type['true_false_question']) {
                    $params['answer_content'] = $params['true_false_answer'];
                } elseif ($params['type'] == $this->question_type['single_choice'] || $params['type'] == $this->question_type['multiple_choice']) {
                    $params['answer_content'] = answer_abc2bin(strtoupper(trim(strip_tags($params['input_answer']))));
                } else {
                    $params['answer_content'] = $params['input_answer'];
                }
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
    /**
     * 导入试题
     * 导入分两个大的步骤
     *  一、通过 ajax/upload 上传文件，这部分会读取到extra/upload.php 里面的相关配置
     *  二、导入数据库，这一项分为以下几个部分：
     *   1、第一部分：实例化reader,也就是根据文件后缀来判断该用哪个类去读取该文件，其中csv的文件涉及编码问题，需要转换为utf8，所有上面有一大段的处理代码。
     *   2、第二部分：建立文件首行标题与数据库字段对应关系数组
     *   3、第三部分：加载文件，读取数据，组装数据
     */
    public function import(){
        // parent::import();
        $file = $this->request->request('file');
        if (!$file) {
            $this->error(__('Parameter %s can not be empty', 'file'));
        }
        $filePath = ROOT_PATH . DS . 'public' . DS . $file;
        if (!is_file($filePath)) {
            $this->error(__('No results were found'));
        }
        //实例化reader
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
            $this->error(__('Unknown data format'));
        }
        if ($ext === 'csv') {
            $file = fopen($filePath, 'r');
            $filePath = tempnam(sys_get_temp_dir(), 'import_csv');
            $fp = fopen($filePath, "w");
            $n = 0;
            while ($line = fgets($file)) {
                $line = rtrim($line, "\n\r\0");
                $encoding = mb_detect_encoding($line, ['utf-8', 'gbk', 'latin1', 'big5']);
                if ($encoding != 'utf-8') {
                    $line = mb_convert_encoding($line, 'utf-8', $encoding);
                }
                if ($n == 0 || preg_match('/^".*"$/', $line)) {
                    fwrite($fp, $line . "\n");
                } else {
                    fwrite($fp, '"' . str_replace(['"', ','], ['""', '","'], $line) . "\"\n");
                }
                $n++;
            }
            fclose($file) || fclose($fp);
            // 第一部分：实例化reader
            $reader = new Csv();
        } elseif ($ext === 'xls') {
            $reader = new Xls();
        } else {
            $reader = new Xlsx();
        }
         //第二部分：建立文件首行标题与数据库字段对应关系数组;导入文件首行类型,默认是注释,如果需要使用字段名称请使用name
        $fields = ['type','title_content','option_content','answer_content','level'];

        //加载文件
        $insert = [];
        try {
            if (!$PHPExcel = $reader->load($filePath)) {
                $this->error(__('Unknown data format'));
            }
            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            $allColumn = $currentSheet->getHighestDataColumn(); //取得有内容部分的最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得有内容部分一共有多少行
            $maxColumnNumber = Coordinate::columnIndexFromString($allColumn);//有内容列对应的数字比如，D列对应4

            //从第二行开始,获取导入内容部分，$values
            for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                $values = [];
                for ($currentColumn = 1; $currentColumn <= $maxColumnNumber; $currentColumn++) {
                    $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    $values[] = is_null($val) ? '' : trim($val);
                }
                //标题=>值；[type] => 判断题
                $temp = array_combine($fields, $values);
                //转成数据库导入对应字段=》值；[type] => 1
                $row = [];
                $type=$this->model->getTypeList();
                $level=$this->model->getLevelList();
                $question_type=array_flip($type);
                $question_level=array_flip($level);
                $errorNum=0;
                foreach ($temp as $k => $v) {
                    if (isset($fieldArr[$k]) && $k !== '') {
                        $type = is_numeric($question_type[$temp['题目类型']]) ? $question_type[$temp['题目类型']]:'';
                        //题目类型:保存题型的代码
                        if($k=='type'){
                            if($type){
                                $row[$fieldArr[$k]]=$type;
                            }
                            //答案：转码成二进制保存
                        }elseif ($k=='answer_content') {
                            //[判断题]
                            if($type==$this->question_type['true_false_question']){
                                if($v=='正确'){
                                    $row[$fieldArr[$k]]=1;
                                }else{
                                    $row[$fieldArr[$k]]=0;
                                }
                            //[选择题]
                            }elseif($type==$this->question_type['single_choice'] || $type == $this->question_type['multiple_choice']){
                                $row[$fieldArr[$k]]= answer_abc2bin(strtoupper(trim(strip_tags($v))));
                            }
                        //[难易程度]
                        }elseif($k=='level'){
                            $levelTeml=is_numeric($question_level[$v])?$question_level[$v]:'';
                            $row[$fieldArr[$k]] =$levelTeml;
                        }else{
                            $row[$fieldArr[$k]] = $v;   
                        }
                    }

                }
                
                //组装成数据库导入数组
                if ($row) {
                    $insert[] = $row;
                }
            }
            dd($insert);
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
        if (!$insert) {
            $this->error(__('No rows were updated'));
        }

        try {
            //是否包含admin_id字段
            $has_admin_id = false;
            foreach ($fieldArr as $name => $key) {
                if ($key == 'admin_id') {
                    $has_admin_id = true;
                    break;
                }
            }
            if ($has_admin_id) {
                $auth = Auth::instance();
                foreach ($insert as &$val) {
                    if (!isset($val['admin_id']) || empty($val['admin_id'])) {
                        $val['admin_id'] = $auth->isLogin() ? $auth->id : 0;
                    }
                }
            }
            //插入数据库
            $this->model->saveAll($insert);

        } catch (PDOException $exception) {
            $msg = $exception->getMessage();
            if (preg_match("/.+Integrity constraint violation: 1062 Duplicate entry '(.+)' for key '(.+)'/is", $msg, $matches)) {
                $msg = "导入失败，包含【{$matches[1]}】的记录已存在";
            };
            $this->error($msg);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        $this->success();
    }
    
    /**
     * 导出
     */
    public function export()
    {
        if ($this->request->isPost()) {
            set_time_limit(0);
            $search = $this->request->post('search');
            $ids = $this->request->post('ids');
            $filter = $this->request->post('filter');
            $op = $this->request->post('op');
            $columns = $this->request->post('columns');

            //$excel = new PHPExcel();
            $spreadsheet = new Spreadsheet();

            $spreadsheet->getProperties()
                ->setCreator("FastAdmin")
                ->setLastModifiedBy("FastAdmin")
                ->setTitle("标题")
                ->setSubject("Subject");
            $spreadsheet->getDefaultStyle()->getFont()->setName('Microsoft Yahei');
            $spreadsheet->getDefaultStyle()->getFont()->setSize(12);

            $worksheet = $spreadsheet->setActiveSheetIndex(0);
            $whereIds = $ids == 'all' ? '1=1' : ['id' => ['in', explode(',', $ids)]];
            $this->request->get(['search' => $search, 'ids' => $ids, 'filter' => $filter, 'op' => $op]);
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            
            $line = 1;

            //设置过滤方法
            $this->request->filter(['strip_tags']);

            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)//这里需要增加where
                ->where($whereIds)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)//这里需要增加where
                ->where($whereIds)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);
            
            $first = array_keys($list[0]);
            foreach ($first as $index => $item) {
                $worksheet->setCellValueByColumnAndRow($index, 1, __($item));
            }
            
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(ROOT_PATH . '/public/template/question.xls');  //读取模板
            $worksheet = $spreadsheet->getActiveSheet();     //指向激活的工作表
            $worksheet->setTitle('模板测试标题');

            for($i=0;$i<$total;++$i){ 
                //向模板表中写入数据
                $worksheet->setCellValue('A1', '模板测试内容');   //送入A1的内容
                $worksheet->getCell('B2')->setValue($result['rows'][$i]['week']);    //星期
                $worksheet->getCell('d2')->setValue($result['rows'][$i]['genderdata']);  //性别
                $worksheet->getCell('f2')->setValue($result['rows'][$i]['hobbydata']);  //爱好
                $worksheet->getCell('b3')->setValue($result['rows'][$i]['title']);  //标题
                $worksheet->getCell('b4')->setValue($result['rows'][$i]['content']);  //内容
                
                
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
                //下载文档
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'. date('Y-m-d') . $result['rows'][$i]['admin_id'] .'_test'.'.xlsx"');
                header('Cache-Control: max-age=0');
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            }        

            return;
        }
    }
    
}
