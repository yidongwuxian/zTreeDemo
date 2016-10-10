<?php
namespace app\app\validate;
use think\Validate;

class Car extends Validate
{
    protected $rule = [
        'use_time'    =>  'require|date',
        'person_num'  =>  'require|number|between:1,20',
        'cars_num'    =>  'require|number|between:1,20',
        'mobile'      =>  'require',
        'start_city'  =>  'require',
        'end_city'    =>  'require',
        'reason'      =>  'require',
        'department_id' => 'require',
    ];

    protected $message = [
        'department_id.require' => '当前用户没有所属部门',
        'use_time.require'    => '用车时段不能为空',
        'person_num.require'  => '人数不能为空',
        'cars_num.require'    => '用车数不能为空',
        'mobile.require'      => '联系方式不能为空',
        'start_city.require'  => '出发地不能为空',
        'end_city.require'    => '目的地不能为空',
        'reason.require'      => '用车事由不能为空',
    ];


    protected $scene = [
        'applycar'   =>  ['department_id', 'use_time', 'person_num', 'cars_num', 'mobile', 'start_city', 'end_city', 'reason'],
    ];

}