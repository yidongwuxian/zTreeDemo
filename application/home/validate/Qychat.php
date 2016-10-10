<?php
/**
 * 文件的简短描述：Qychat验证器类文件
 *
 * 文件的详细描述：Qychat验证器类文件
 *
 * LICENSE:
 * @author wangzhen 2016/8/8
 * @copyright Copyright (c) 2016 DFJK
 * @version 2.0.0
 * @since File available since Release 1.0.0
**/
namespace app\home\validate;

use think\Validate;

class Qychat extends Validate {

    protected $rule =   [
        'chat_str|简称'           => 'require|alphaNum|max:10',
        'name|企业号名称'          => 'require|max:20',
        'corp_id|CorpID'         => 'require|alphaNum|max:50',
        'corp_secret|CorpSecret' => 'require|alphaDash|max:100',
        'status|状态'             => 'require|in:1,2',
    ];

    /*protected $message  =   [
        'chat_str.require' => '简称必须',
        'chat_str.alphaNum' => '简称应为英文或数字组合',
        'chat_str.max' => '简称不能超过10个字符',
        'name.max'     => '名称最多不能超过25个字符',
        'age.number'   => '年龄必须是数字',
        'age.between'  => '年龄只能在1-120之间',
        'email'        => '邮箱格式错误',
    ];*/
}