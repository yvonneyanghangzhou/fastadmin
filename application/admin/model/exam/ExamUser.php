<?php

namespace app\admin\model\exam;

use think\Model;
use traits\model\SoftDelete;

class ExamUser extends Model
{

    use SoftDelete;

    

    // 表名
    protected $table = 'exam_user';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'type_text',
        'change_online_time'
    ];
    

    public function getTypeList()
    {
        return ['1' => __('Type 1'), '2' => __('Type 2'), '3' => __('Type 3')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    //将在线时长（秒）转化成可读时间格式
    public function getChangeOnlineTimeAttr($value, $data)
    {
        if(isset($data['online_time']) && $data['online_time']){
            return changeTimeType($data['online_time']);
        }else{
            return '';
        }
    }


    public function baseorg()
    {
        return $this->belongsTo('app\admin\model\BaseOrg', 'org_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
