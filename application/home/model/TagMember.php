<?php
/**
 * 文件的简短描述：标签成员模型文件
 *
 * 文件的详细描述：标签成员模型文件
 *
 * LICENSE:
 * @author wangzhen 2016/9/23
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\home\model;

use think\Model;

class TagMember extends Model{

    // 设置完整的数据表（包含前缀）
    protected $table = 'qy_tag_member';

    // 关闭自动写入时间戳
    protected $autoWriteTimestamp = false;

}