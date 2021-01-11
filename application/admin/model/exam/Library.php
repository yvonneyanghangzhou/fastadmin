<?php

namespace app\admin\model\exam;

use think\Model;
use traits\model\SoftDelete;

class Library extends Model
{

    use SoftDelete;

    

    // 表名
    protected $table = 'exam_library';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'status_text',
        'library_type_text',
        'front_show_text',
        'question_count'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
    public function getStatusList()
    {
        return ['1' => __('Status 1'), '0' => __('Status 2')];
    }

    public function getLibraryTypeList()
    {
        return ['1' => __('Library_type 1'), '2' => __('Library_type 2'), '3' => __('Library_type 3')];
    }

    public function getFrontShowList()
    {
        return ['1' => __('Front_show 1'), '0' => __('Front_show 2')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getLibraryTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['library_type']) ? $data['library_type'] : '');
        $list = $this->getLibraryTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getFrontShowTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['front_show']) ? $data['front_show'] : '');
        $list = $this->getFrontShowList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getQuestionCountAttr($value, $data)
    {
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function baseorg()
    {
        return $this->belongsTo('app\admin\model\BaseOrg', 'org_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
