<?php

namespace app\admin\model\exam;

use think\Model;


class ExamUser extends Model
{

    

    

    // 表名
    protected $table = 'exam_user';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

    







    public function baseorg()
    {
        return $this->belongsTo('app\admin\model\BaseOrg', 'org_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
