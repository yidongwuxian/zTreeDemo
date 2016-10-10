<?php
/**
 * 文件的简短描述：标签类文件
 *
 * 文件的详细描述：标签类文件
 *
 * LICENSE:
 * @author wangzhen 2016/9/12
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\home\controller;

use app\home\model\Tag as TagModel;
use app\home\model\TagMember as TagMemberModel;
use app\home\model\Member as MemberModel;
use app\home\model\Department as DepartmentModel;
use app\home\model\TagMember;
use think\Request;

/**
 * 类名：Tag
 *
 * 类的详细描述：标签类
 *
 * LICENSE:
 * @author wangzhen ${DATE}
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
class Tag extends Base {

    /**
     * 构造函数
     */
    public function __construct(){
        parent::__construct();
        //导航菜单contact模块
        $this->assign('class','contact');
        $this->request = Request::instance();
    }

    /**
     * 标签主页
     * @return mixed
     */
    public function index(){
        //导航菜单sub_class模块
        $this->assign('subClass','tag');
        return $this->fetch();
    }

    /**
     * 初始化标签
     */
    public function init(){
        if($this->request->isAjax()){
            //判断标签是否为空
            $tagCount = TagMember::where('qychat_id',$this->qychatId)->count();
            if($tagCount){
                \Util::echoJson('已有标签数据，不能再次初始化！');
            }
            $initTag = $this->_initTag();
            if($initTag['result'] === false){
                //如果初始化标签失败
                \Util::echoJson('初始化失败！');
            }
            //标签列表初始化成功
            if( ! empty($initTag['taglist'])){
                //如果标签列表不为空，初始化标签列表成员
                $initTagMember = $this->_initTagMember($initTag['taglist']);
                if($initTagMember == true){
                    \Util::echoJson('初始化成功！',true);
                }else{
                    \Util::echoJson('初始化标签成员失败，稍后请同步！',true);
                }
            }else{
                //如果标签列表为空，说明没有标签，直接返回成功
                \Util::echoJson('初始化成功：没有标签！',true);
            }
        }
    }

    /**
     * 创建标签
     */
    public function add(){
        if($this->request->isAjax()){
            //接收POST参数
            $data = [
                'tagname'  => $this->request->post('tagname'),
            ];
            //校验参数
            $result = $this->validate($data,'Tag');
            if(true !== $result){
                \Util::echoJson($result);
            }
            //调用接口创建标签
            $qychatApi = new \Qychat($this->corpInfo);
            $addTag = $qychatApi->createTag($data);
            if($addTag == false){
                \Util::echoJson($qychatApi->errMsg);
            }
            //调用微信接口成功，存入数据库
            $tagid = $addTag['tagid'];
            //数据库插入数据
            $tag = new TagModel();
            $data['tagid'] = $tagid;
            $data['qychat_id'] = $this->qychatId;
            $tag->data($data);
            if ($tag->save()) {
                \Util::echoJson('创建成功！',true);
            } else {
                \Util::echoJson($tag->getError());
            }
        }
    }

    /**
     * 为标签添加个人成员
     * @throws \Exception
     */
    public function addMember(){
        if($this->request->isAjax()){
            $tagId = $this->request->post('tagid');
            $userid = $this->request->post('userid/a');
            if( ! $tagId){
                \Util::echoJson('标签Id错误！');
            }elseif(empty($userid)){
                \Util::echoJson('请至少选择一名成员！');
            }
            //调用接口
            $qychatApi = new \Qychat($this->corpInfo);
            $addTagMemberData = [
                'tagid' => $tagId,
                'userlist' => $userid
            ];
            $addTagMember = $qychatApi->addTagUser($addTagMemberData);
            if($addTagMember == false){
                \Util::echoJson($qychatApi->errMsg);
            }
            //插入数据
            $insertData = array();
            foreach($userid as $uid){
                //查询该用户是否已经加入标签
                $userInfo = TagMemberModel::where('qychat_id',$this->qychatId)
                    ->where('tagid',$tagId)
                    ->where('userid',$uid)
                    ->find();
                if( ! empty($userInfo)){
                    //如果已经加入标签不在加入
                    continue;
                }
                $insertData[] = [
                    'qychat_id' => $this->qychatId,
                    'tagid' => $tagId,
                    'userid' => $uid
                ];
            }
            //插入数据库
            $tagMemberModel = new TagMemberModel();
            if($tagMemberModel->saveAll($insertData)){
                \Util::echoJson('添加成员成功！',true);
            }else{
                \Util::echoJson('添加成员失败！',true);//也需要刷新页面
            }
        }
    }

    /**
     * 为标签添加部门成员
     * @throws \Exception
     */
    public function addDepartment(){
        if($this->request->isAjax()){
            $tagId = $this->request->post('tagid');
            $userid = $this->request->post('partyid/a');
            if( ! $tagId){
                \Util::echoJson('标签Id错误！');
            }elseif(empty($userid)){
                \Util::echoJson('请至少选择一个部门！');
            }
            //调用接口
            $qychatApi = new \Qychat($this->corpInfo);
            $addTagMemberData = [
                'tagid' => $tagId,
                'partylist' => $userid
            ];
            $addTagMember = $qychatApi->addTagUser($addTagMemberData);
            if($addTagMember == false){
                \Util::echoJson($qychatApi->errMsg);
            }
            //插入数据
            $insertData = array();
            foreach($userid as $uid){
                //查询该用户是否已经加入标签
                $userInfo = TagMemberModel::where('qychat_id',$this->qychatId)
                    ->where('tagid',$tagId)
                    ->where('departmentid',$uid)
                    ->find();
                if( ! empty($userInfo)){
                    //如果已经加入标签不在加入
                    continue;
                }
                $insertData[] = [
                    'qychat_id' => $this->qychatId,
                    'tagid' => $tagId,
                    'departmentid' => $uid
                ];
            }
            //插入数据库
            $tagMemberModel = new TagMemberModel();
            if($tagMemberModel->saveAll($insertData)){
                \Util::echoJson('添加部门成功！',true);
            }else{
                \Util::echoJson('添加部门失败！',true);//也需要刷新页面
            }
        }
    }

    /**
     * 获取标签列表
     * @return array
     */
    public function getTagList(){
        if($this->request->isAjax()){
            $tagList = TagModel::all(['qychat_id' => $this->qychatId]);
            $list = array();
            foreach($tagList as $tag){
                $list[] = [
                    'id'    =>  $tag['id'],
                    'name'  =>  $tag['tagname'],
                    'tagid' =>  $tag['tagid']
                ];
            }
            return $list;
        }
    }

    /**
     * 获取标签成员
     * @param int $tagid
     * @return array
     */
    public function getTagMember($tagid = 0){
        if($this->request->isAjax()){
            $memberList = array();
            $tagMemberList = TagMemberModel::where('qychat_id',$this->qychatId)
                ->where('tagid',$tagid)
                ->select();
            foreach($tagMemberList as $member){
                if($member['userid'] != ''){
                    //如果是个人成员
                    $userInfo = MemberModel::where('qychat_id',$this->qychatId)->where('userid',$member['userid'])->find();
                    if( ! empty($userInfo)){
                        $name = $userInfo->name;
                        $type = '<span class="label label-sm label-success">成员</span>';
                    }else{
                        //如果用户表中没有该用户记录，认为该用户已经删除，此处不再列出
                        continue;
                    }
                }elseif($member['departmentid'] != ''){
                    //如果是部门
                    $departmentInfo = DepartmentModel::where('qychat_id',$this->qychatId)
                        ->where('department_id',$member['departmentid'])
                        ->find();
                    if( ! empty($departmentInfo)){
                        $name = $departmentInfo->department_name;
                        $type = '<span class="label label-sm label-warning">部门</span>';
                    }else{
                        //如果用户表中没有该部门记录，认为该部门已经删除，此处不再列出
                        continue;
                    }
                }
                $memberList[] = [
                    '<label class="pos-rel"><input data-type="check_member" type="checkbox" class="ace" data-id="' . $member['id'] . '"/><span class="lbl"></span></label>',
                    $name,
                    $type
                ];
            }
            $memberObj = array('aaData' => $memberList);
            return $memberObj;
        }
    }

    /**
     * 删除标签成员（包含个人和部门）
     */
    public function deleteMember(){
        if($this->request->isAjax()){
            $memberid = $this->request->post('memberid/a');
            if(empty($memberid)){
                \Util::echoJson('请至少选择一个成员！');
            }
            //批量删除
            $delete = TagMemberModel::destroy($memberid);
            if($delete){
                \Util::echoJson('从标签中删除成员成功！',true);
            }else{
                \Util::echoJson('从标签中删除成员失败！',true);//也需要刷新页面
            }
        }
    }

    /**
     * 初始化标签列表
     * @return array|bool|false
     * @throws \Exception
     */
    private function _initTag(){
        //定义返回结果
        $result = [
            'result'    => true,
            'taglist'   => []
        ];
        $qychatApi = new \Qychat($this->corpInfo);
        $tagList = $qychatApi->getTagList();
        $tagList = $tagList['taglist'];
        if(empty($tagList)){
            //如果为空，表示没有标签，直接返回成功
            return $result;
        }
        //如果有标签，则插入数据库
        $insertData = array();
        foreach($tagList as $tag){
            $insertData[] = [
                'tagid'     => $tag['tagid'],
                'tagname'   => $tag['tagname'],
                'qychat_id' => $this->qychatId
            ];
        }
        //批量插入
        $tagModel = new TagModel();
        if($tagModel->saveAll($insertData)){
            $result['taglist'] = $tagList;
            return $result;
        }else{
            $result['result'] = false;
            return $result;
        }
    }

    /**
     * 初始化标签成员
     * @param $tagList
     * @return bool
     * @throws \Exception
     */
    private function _initTagMember($tagList){
        $qychatApi = new \Qychat($this->corpInfo);
        $insertData = array();
        foreach($tagList as $tag){
            $tagMemberList = $qychatApi->getTag($tag['tagid']);
            if($tagMemberList['errcode'] != 0){
                //如果报错，执行下一个标签的初始化
                continue;
            }
            if( ! empty($tagMemberList['userlist'])){
                //如果有用户列表
                foreach($tagMemberList['userlist'] as $user){
                    $insertData[] = [
                        'qychat_id' =>  $this->qychatId,
                        'tagid'     =>  $tag['tagid'],
                        'userid'    =>  $user['userid']
                    ];
                }
            }
            if( ! empty($tagMemberList['partylist'])){
                //如果有部门列表
                foreach($tagMemberList['partylist'] as $party){
                    $insertData[] = [
                        'qychat_id' =>  $this->qychatId,
                        'tagid'     =>  $tag['tagid'],
                        'departmentid'   =>  $party
                    ];
                }
            }
        }
        if(empty($insertData)){
            //如果标签成员为空，直接返回成功
            return true;
        }
        $tagMemberModel = new TagMemberModel();
        if($tagMemberModel->saveAll($insertData)){
            return true;
        }else{
            return false;
        }
    }
}

