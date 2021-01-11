<?php

namespace app\admin\model\exam;

use think\Model;
use traits\model\SoftDelete;

class QuestionTag extends Model
{

    use SoftDelete;

    

    // 表名
    protected $table = 'exam_question_tag';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'is_hot_text'
    ];
    

    
    public function getIsHotList()
    {
        return ['1' => __('Is_hot 1'), '2' => __('Is_hot 2')];
    }


    public function getIsHotTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['is_hot']) ? $data['is_hot'] : '');
        $list = $this->getIsHotList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
