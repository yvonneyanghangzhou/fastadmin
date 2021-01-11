<?php

namespace app\admin\model\exam;

use think\Model;

class Project extends Model
{
    // 表名
    protected $table = 'exam_project';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';

    // 追加属性
    protected $append = [
        'allow_or_not_text',
        'exam_status_text'
    ];
    
    public function getAllowOrNotList()
    {
        return ['1' => '是', '2' => '否'];
    }

    public function getAllowOrNotTextAttr($value, $data)
    {
        $list = $this->getAllowOrNotList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getExamStatusTextAttr($value, $data)
    {
        $status=compareDateStatus($data['start_date'], $data['close_date']);
        return $status;
    }
}