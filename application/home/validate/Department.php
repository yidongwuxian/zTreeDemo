<?php
/**
 * 文件的简短描述：Department验证器文件
 *
 * 文件的详细描述：Department验证器文件
 *
 * LICENSE:
 * @author wangzhen 2016/8/24
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\home\validate;

use think\Validate;

class Department extends Validate {

    protected $rule =   [
        'department_name|部门名称' => 'require|max:50'
    ];

}