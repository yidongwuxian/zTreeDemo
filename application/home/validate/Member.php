<?php
/**
 * 文件的简短描述：Member验证器类文件
 *
 * 文件的详细描述：Member验证器类文件
 *
 * LICENSE:
 * @author wangzhen 2016/8/26
 * @copyright Copyright (c) 2016 DFJK
 * @version 2.0.0
 * @since File available since Release 1.0.0
**/
namespace app\home\validate;

use think\Validate;

class Member extends Validate {

    protected $rule =   [
        'name|姓名'           => 'require|max:50',
        'userid|账号'         => 'require|alphaNum|max:50',
        'gender|性别'         => 'require|in:1,2',
        'mobile|手机号'       => 'alphaDash|max:20',
        'weixinid|微信号'     => 'alphaDash|max:50',
        'email|邮箱'          => 'email',
        'department|部门'     => 'require|array'
    ];

    protected $scene = [
        'edit'  =>  ['name','gender','mobile','weixinid','email','department'],
    ];
}