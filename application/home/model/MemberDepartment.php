<?php
/**
 * 文件的简短描述：成员部门对应模型类文件
 *
 * 文件的详细描述：成员部门对应模型类文件
 *
 * LICENSE:
 * @author wangzhen 2016/8/22
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\home\model;

use think\Model;

class MemberDepartment extends Model {

    // 设置完整的数据表（包含前缀）
    protected $table = 'qy_member_depart';

    // 关闭自动写入时间戳
    protected $autoWriteTimestamp = false;

}