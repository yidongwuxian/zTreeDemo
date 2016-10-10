<?php
/**
 * 文件的简短描述：标签验证器类文件
 *
 * 文件的详细描述：标签验证器类文件
 *
 * LICENSE:
 * @author wangzhen 2016/9/26
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\home\validate;

use think\Validate;

class Tag extends Validate {

    protected $rule =   [
        'tagname|部门名称' => 'require|max:32'
    ];

}