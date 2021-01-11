<?php

namespace app\admin\model\exam;

use think\Model;
use traits\model\SoftDelete;

class Question extends Model
{
    use SoftDelete;

    

    // 表名
    protected $table = 'exam_question';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'type_text',
        'level_text',
        'title_text',
        'option_text',
        'answer_text',
    ];
    
    public function getTypeList()
    {
        return ['1' => __('Type 1'), '2' => __('Type 2'), '3' => __('Type 3'), '4' => __('Type 4'), '5' => __('Type 5')];
    }
    //判断题答案 1 正确  2错误
    public function getTrueOrFalseList()
    {
        return ['1' => __('trueOrfalse 1'), '0' => __('trueOrfalse 2')];
    }

    public function getLevelList()
    {
        return ['1' => __('Level 1'), '2' => __('Level 2'), '3' => __('Level 3')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }
    //设置列表显示时不带html标签文字
    public function getTitleTextAttr($value, $data)
    {
        return msubstr(strip_tags($data['title_content']),0, 30);
    }

    //设置列表显示时不带html标签文字
    public function getOptionTextAttr($value, $data)
    {
        if ($data['option_content']) {
            return msubstr(strip_tags($data['option_content']), 0, 16);
        } else {
            return '';
        }
    }

    /**
     * 1判断题，选择题 标准答案存储格式为1,0 1101等数据库格式
     * 2 其他存储为文本格式，设置列表显示时不带html标签文字
     * 
     */
    public function getAnswerTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['answer_content']) ? $data['answer_content'] : '');
        $question_type=config('exam.question_type');
        if($data['type']==$question_type['true_false_question']){
            $list=$this->getTrueOrFalseList();
            return isset($list[$value]) ? $list[$value] : '';
        }elseif ($data['type'] == $question_type['single_choice'] || $data['type'] == $question_type['multiple_choice']) {
            return answer_bin2abc($value);
        }else{
            return msubstr(strip_tags($value), 0, 10);
        }

    }

    public function getLevelTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['level']) ? $data['level'] : '');
        $list = $this->getLevelList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function examlibrary()
    {
        return $this->belongsTo('app\admin\model\exam\Library', 'library_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function examquestiontag()
    {
        return $this->belongsTo('app\admin\model\exam\QuestionTag', 'tag_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
