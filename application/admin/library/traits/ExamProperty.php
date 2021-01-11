<?php

namespace app\admin\library\traits;
use think\Db;
use app\admin\model\exam\Project as ProjectModel;
/**
 * 考试系统相关属性
 */
trait ExamProperty
{
    //获取考试项目列表
    public function getExamProject()
    {
        $list=Db::table('exam_project')->where('deletetime',null)->column('id,name');
        return $list;
    }

    
}
