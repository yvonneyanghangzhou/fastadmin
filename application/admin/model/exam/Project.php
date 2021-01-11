<?php

namespace app\admin\model\exam;

use think\Model;
use traits\model\SoftDelete;

class Project extends Model
{
    use SoftDelete;

    // 表名
    protected $table = 'exam_project';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'allow_mock_text',
        'enable_commitment_text',
        'allow_print_commitment_text',
        'allow_print_certificate_text',
        'status'
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
        return ['1' => __('Status 1'), '2' => __('Status 2'), '3' => __('Status 3')];
    }

    public function getAllowMockList()
    {
        return ['1' => __('Allow_mock 1'), '2' => __('Allow_mock 2')];
    }

    public function getEnableCommitmentList()
    {
        return ['1' => __('Enable_commitment 1'), '2' => __('Enable_commitment 2')];
    }

    public function getAllowPrintCommitmentList()
    {
        return ['1' => __('Allow_print_commitment 1'), '2' => __('Allow_print_commitment 2')];
    }

    public function getAllowPrintCertificateList()
    {
        return ['1' => __('Allow_print_certificate 1'), '2' => __('Allow_print_certificate 2')];
    }

    public function getAllowMockTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['allow_mock']) ? $data['allow_mock'] : '');
        $list = $this->getAllowMockList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getEnableCommitmentTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['enable_commitment']) ? $data['enable_commitment'] : '');
        $list = $this->getEnableCommitmentList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getAllowPrintCommitmentTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['allow_print_commitment']) ? $data['allow_print_commitment'] : '');
        $list = $this->getAllowPrintCommitmentList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getAllowPrintCertificateTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['allow_print_certificate']) ? $data['allow_print_certificate'] : '');
        $list = $this->getAllowPrintCertificateList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    /**
     * 根据考试开始结束日期判断考试状态
     * 状态:1=未开始,2=进行中,3=已结束
     * list表中显示
     */
    public function getStatusAttr($value, $data)
    {
        $value=compareDateStatus($data['start_date'], $data['close_date']);
        return $value;
    }



    /**
     * 关联模型
     */
    public function baseorg()
    {
        return $this->belongsTo('app\admin\model\BaseOrg', 'org_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
