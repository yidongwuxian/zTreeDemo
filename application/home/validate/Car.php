<?php
namespace app\home\validate;
use think\Validate;

class Car extends Validate
{
    protected $rule = [
        'name'  =>  'require|max:25',
        'type'  =>  'require',
        'department_id'  =>  'require',
        'mark'  =>  'require',
        'member_id'  =>  'require',
        'process_id'  =>  'require',
        'order'   => 'number|between:1,50',
    ];

    protected $message = [
        'name.require' => '名称不能为空',
        'name.max'     => '名称不能超过25个字符',
        'type.require' => '请选择车辆类型',
        'department_id.require' => '部门id错误',
        'mark.require' => '流程标记不能为空',
        'member_id.require' => '请选择一个用户',
        'process_id.require' => '参数错误，流程节点id丢失',
        'order.number' => '排序必须是1-50的数字',
    ];

    protected $scene = [
        'addcar'   =>  ['name', 'type'],
        'addprocess'  =>  ['name', 'mark', 'order'],
        'setmanage'   =>  ['department_id', 'member_id', 'process_id'],
    ];

}