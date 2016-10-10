<?php
/**
 * 文件的简短描述：Exam验证器类文件
 *
 * 文件的详细描述：Exam验证器类文件
 *
 * LICENSE:
 * @author lijin 2016/8/25
 * @copyright Copyright (c) 2016 DFJK
 * @version 2.0.0
 * @since File available since Release 1.0.0
**/
namespace app\home\validate;

use think\Validate;

class Apps extends Validate {

    protected $rule =   [
        'app_name' 		=> 'require|max:10',
        'app_logo'		=> 'max:20',
        'app_desc'		=> 'alphaNum|max:200',
        'app_controller'=> '',
        'status'		=> 'require',
        'sort'			=> 'number',
        'memo'			=> '',
    ];



}