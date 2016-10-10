<?php
namespace app\app\controller;

use app\home\model\AppCarApply;
use app\home\model\AppCarProcess;
use think\Db;
use think\Request;
use app\home\model\AppCar as AppcarModel;
use app\home\model\Member as MemberModel;
use app\home\model\AppCarApply as AppCarApplyModel;
use app\home\model\AppCarManage as CarManageModel;
use app\home\model\AppCarProcess as CarProcessModel;
use app\home\model\MemberDepartment as MemberDepartModel;
use app\app\model\AppCarApplyReply as AppCarApplyReplyModel;

/**
 * 类名：Car
 *
 * 类的详细描述：APP用车申请控制器类
 *
 * LICENSE:
 * @author zhangpeng 2016/9/8
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
class Car extends Base
{
    protected $limt = 20;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *app申请记录列表。根据当前qychat_id和userid区分
     */
    public function apply(){
        $list = AppCarApplyModel::where('qychat_id', $this->appQychatId)
                ->where('member_id', $this->userId)
                ->order('use_date desc')
                ->limit($this->limt)
                ->select();
        //取审核状态
        foreach ($list as $vo) {
            switch ($vo->status)
            {
                case 1:
                    $processData = AppCarProcess::get($vo->process_id);
                    $tip = ($processData->mark == 1) ? ' 审核中' : ' 办理中';
                    $vo['reply_status'] = ( !empty($processData)) ? $processData->name.$tip : '';
                    break;
                case 2:
                    $vo['reply_status'] = '审核通过，办理中';
                    break;
                case 3:
                    $vo['reply_status'] = '已办理，派车中';
                    break;
                case 4:
                    $vo['reply_status'] = '已派车';
                    break;
                case 5:
                    $vo['reply_status'] = '已结束';
                    break;
                default:
                    $vo['reply_status'] = '已拒绝';
            }
        }
        //获取总记录数，求总页数，用于 没有更多数据时 上拉加载停止
        $total = AppCarApplyModel::all(['qychat_id' => $this->appQychatId, 'member_id' => $this->userId]);
        $totalPage = ceil( count($total)/$this->limt );

        $this->assign('list', $list);
        $this->assign('totalPage', $totalPage);
        return $this->fetch();
    }

    /**
     * ajax调取申请记录，用于申请列表的上拉刷新
     * mark 1 为审核列表的请求，参数为qychat_id,depart_id
     * mark 2 为派发列表的请求，参数为qychat_id
     * 如有没有mark参数，默认为个人申请列表的请求，参数为qychat_id,member_id
     */
    public function ajaxData(){
        $request = Request::instance();
        if($request->isAjax()){
            $page = $request->get('page');
            switch ($request->param('mark'))
            {
                case 1:
                    $data = Db::table('app_car_apply')
                        ->where('qychat_id', $this->appQychatId)
                        ->where('department_id', 'in', $request->get('depart_id'))
                        ->order('use_date desc')
                        ->page($page, $this->limt)
                        ->select();
                    break;
                case 2:
                    echo "mark 2";
                    break;
                default:
                    $data = Db::table('app_car_apply')
                        ->where('qychat_id', $this->appQychatId)
                        ->where('member_id', $this->userId)
                        ->order('use_date desc')
                        ->page($page, $this->limt)
                        ->select();
            }

            //取审核状态
            foreach ($data as $k=>$vo) {
                $data[$k]['use_date'] = date('Y-m-d', $vo['use_date']);
                switch ($vo['status'])
                {
                    case 1:
                        $processData = AppCarProcess::get($vo['process_id']);
                        $tip = ($processData->mark == 1) ? ' 审核中' : ' 办理中';
                        $data[$k]['reply_status'] = ( !empty($processData)) ? $processData->name.$tip : '';
                        break;
                    case 2:
                        $data[$k]['reply_status'] = '审核通过，办理中';
                        break;
                    case 3:
                        $data[$k]['reply_status'] = '已办理，派车中';
                        break;
                    case 4:
                        $data[$k]['reply_status'] = '已派车';
                        break;
                    case 5:
                        $data[$k]['reply_status'] = '已结束';
                        break;
                    default:
                        $data[$k]['reply_status'] = '已拒绝';
                }

            }
            $result = ['statusCode' => 200, 'data' => $data];
            echo json_encode($result);
        }
    }

    /**
     * app申请用车，qychatid userid在base类初始化获取
     * @return json result
     */
    public function applyCar(){
        $request = Request::instance();
        if($request->isAjax()){
            //获取当前微信用户的部门id
            $memberDepartData = MemberDepartModel::where('qychat_id', $this->appQychatId)
                ->where('userid', $this->userId)
                ->find();

            $carManageModel = new CarManageModel();
            //根据qychat_id,depart_id,mark=1（审核流程） 取当前用户部门 审核流程 管理者信息，如果为空提示错误信息
            $mark1ManageData = $carManageModel->getDepartManageData($this->appQychatId, $memberDepartData['department_id'], 1);

            //如果有审核节点设置了管理者
            if( !empty($mark1ManageData)){
                $mark1ManageRet = $carManageModel->mergeDepartManage($mark1ManageData);   //获取合并后的各个审核节点管理者
                //接收POST参数
                $data = array(
                    'use_date'   => strtotime($request->post('use_date')),
                    'use_time'   => $request->post('use_time'),
                    'person_num' => trim($request->post('person_num')),
                    'cars_num'   => trim($request->post('cars_num')),
                    'mobile'     => trim($request->post('mobile')),
                    'start_city' => trim($request->post('start_city')),
                    'end_city'   => trim($request->post('end_city')),
                    'reason'     => trim($request->post('reason')),
                    'desc'       => trim($request->post('desc')),
                    'status'     => 1,
                    'qychat_id'  => $this->appQychatId,
                    'member_id'  => $this->userId,
                );
                $memberData = MemberModel::get(['qychat_id' => $this->appQychatId, 'userid' => $this->userId]); //取当前用户信息
                $data['member_name'] = $memberData->name;
                $data['department_id'] = $memberDepartData['department_id'];
                //如果当前申请人是审核流程中的某一级，那么开始审核时，指针指向下一级，反之 取第一个审核节点
                if(array_key_exists($this->userId, $mark1ManageRet)){  //如果当前userid在部门审核节点管理者中
                    //而且如果当前userid是审核节点的最后一级，申请状态默认为审核通过，并找派发流程的第一个节点
                    if( end($mark1ManageRet)->member_id == $this->userId ){
                        $data['status'] = 2;
                        $mark2ManageData = $carManageModel->getDepartManageData($this->appQychatId, 0, 2);  //派发流程节点，部门id统一为0
                        if( !empty($mark2ManageData))
                        {
//                            $mark2ManageRet = $carManageModel->mergeDepartManage($mark2ManageData);   //获取合并后的各个审核节点管理者
//                            //如果当前userid在派发流程管理者中
//                            if(array_key_exists($this->userId, $mark2ManageData))
//                            {
//                                //如果当前userid是派发流程的最后一级，取最后一级
//                                if( end($mark2ManageRet)->member_id == $this->userId ){
//                                    $pointerArray = end($mark2ManageRet);
//                                }else{
//                                    //如果当前userid不是派发节点的最后一级，找它的下一级节点
//                                    $nextKey = ( array_search($this->userId, array_keys($mark2ManageRet)) )+1;     //[0=>'zhangsan',1=>'lisi'] 找到zhangsan的键'0' +1
//                                    $pointerKey = ( array_keys($mark2ManageRet)[$nextKey] );   //获取当前用户所管理的派发节点的下一级节点
//                                    $pointerArray = $mark2ManageRet[$pointerKey];  //获取当前应该指向的派发节点对象
//                                }
//
//                            } else {
//                                //当前userid不在派发流程管理者中，取第一个派发节点
//                                $pointerArray = reset($mark2ManageData);
//                            }
                            $pointerArray = reset($mark2ManageData);    //如果有派发节点，直接选第一个派发节点

                        } else {
                            \Util::echoJson('当前部门没有设置派发流程管理员，请联系客服');
                        }

                    }else{
                        //如果当前userid不是审核节点的最后一级，找它的下一级节点
                        $nextKey = ( array_search($this->userId, array_keys($mark1ManageRet)) )+1;     //[0=>'zhangsan',1=>'lisi'] 找到zhangsan的键'0' +1
                        $pointerKey = ( array_keys($mark1ManageRet)[$nextKey] );   //获取当前用户所管理的审核节点的下一级节点
                        $pointerArray = $mark1ManageRet[$pointerKey];  //获取当前应该指向的审核节点对象
                    }
                }else{
                    //当前userid不在审核节点管理者中，取第一个审核节点
                    $pointerArray = reset($mark1ManageRet);
                }

                $data['process_id'] = $pointerArray->process_id;
                //校验参数
                $checkRet = $this->validate($data, 'Car.applycar');
                if(true !== $checkRet) \Util::echoJson($checkRet);
                //校验通过，保存数据
                $model = new AppCarApplyModel();
                $model->data($data);
                if($model->save()){
                    //添加成功后，发送消息
                    $id = $model->id;
                    $sendMessage['touser'] = $pointerArray->member_id;
                    $sendMessage['safe']   = 'news';
                    $sendMessage['agentid']= 1;
                    $newsData = [
                        'title' => '您有【'.$data['use_date'].'-用车申请-'.$memberData->name.'】需处理',
                        'description' => '请点击处理',
                        'url' => '/app/car/replyapply?id='.$id,
                        ];
                    $sendMessage['news']['articles'] = $newsData;
                    //$this->wxapi->sendMessage($sendMessage);
                    \Util::echoJson('申请成功！', true);
                }else{
                    \Util::echoJson($model->getError());
                }

            }else{
                \Util::echoJson('当前部门没有设置管理员，请联系客服');
            }

        }else{
            return $this->fetch();
        }
    }


    /**
     * 申请用车详情页
     * @param $id
     * @return mixed
     */
    public function applyInfo($id){
        $data = AppCarApplyModel::get(['id' => $id, 'qychat_id' => $this->appQychatId]);
        if(!$data){
            $this->error('页面不存在！');
        }
        //根据 qychat_id+userid+depart_id+apply_id 从apply_reply表取数据，如果没有 为第一次审批
        $applyReplyData = AppCarApplyReplyModel::where('qychat_id', $this->appQychatId)
            ->where('apply_id', $data['id'])
            ->where('department_id', $data['department_id'])
            ->find();
        $applyReplyInfo = ($applyReplyData) ? unserialize($applyReplyData->info) : array();  //如果有记录 取info，并反序列化。反之为空
        if( !empty($applyReplyInfo)){   //取各个流程的进度
            $mark1ReplyData = isset($applyReplyInfo['mark1']) ? $applyReplyInfo['mark1'] : '';
            $mark2ReplyData = isset($applyReplyInfo['mark2']) ? $applyReplyInfo['mark2'] : '';
            $this->assign('mark1ReplyData', $mark1ReplyData);
            $this->assign('mark2ReplyData', $mark2ReplyData);
            $replyModel = new AppCarApplyReplyModel();
            $mark3ReplyData = $replyModel->getUseCarInfo($applyReplyInfo);
            $this->assign('mark3ReplyData', $mark3ReplyData);
        }
        //取审核状态
        switch ($data['status'])
        {
            case 1:
                $processData = AppCarProcess::get($data['process_id']);
                $tip = ($processData->mark == 1) ? ' 审核中' : ' 办理中';
                $data['reply_status'] = ( !empty($processData)) ? $processData->name.$tip : '';
                break;
            case 2:
                $data['reply_status'] = '审核通过，办理中';
                break;
            case 3:
                $data['reply_status'] = '已办理，派车中';
                break;
            case 4:
                $data['reply_status'] = '已派车';
                break;
            case 5:
                $data['reply_status'] = '已结束';
                break;
            default:
                $data['reply_status'] = '已拒绝';
        }
        //var_dump($mark1ReplyData,$mark2ReplyData,$mark3ReplyData);die;;
        $this->assign('data', $data);
        return $this->fetch();
    }

    public function replyApply(){
        $request = Request::instance();
        if ($request->isAjax()) {
            //取当前申请所在的管节点name
            $currentProcessData = CarProcessModel::get($request->post('process_id'));
            $carApplyModel = AppCarApplyModel::get($request->post('apply_id'));
            //第一次审核，直接将数组序列化，生成一条审核记录
            if ($request->post('reply_id') == 0) {

                $data = array(
                    'qychat_id'  => $this->appQychatId,
                    'apply_id'   => $request->post('apply_id'),
                    'department_id' => $request->post('depart_id'),
                );

                switch ($request->post('mark'))
                {
                    case 1:
                        $infoArr = array('mark1' =>
                            array([
                            'user_name' => $this->userName,
                            'process_name' => $currentProcessData->name,
                            'status'  => $request->post('reply_status'),
                            'content' => trim($request->post('reply_content')),
                            'time' => time()
                            ])
                        );
                        $data['info'] = serialize($infoArr);
                        break;
                    default:
                        $infoArr = array('mark2' => [
                            'user_name' => $this->userName,
                            'user_id'   => $this->userId,
                            'process_name' => $currentProcessData->name,
                            'execute_id'   => $request->post('execute_id'),
                            'content' => trim($request->post('reply_content')),
                            'time'    => time()
                        ]);
                        $data['info'] = serialize($infoArr);
                        $data['execute_id'] = $request->post('execute_id');
                }
                //开启事务
                Db::startTrans();
                $carApplyReplyModel = new AppCarApplyReplyModel();
                $carApplyReplyModel->data($data);
                if($carApplyReplyModel->save()){
                    //根据qychat_id,depart_id,mark=1（审核流程） 取当前用户部门 审核流程 管理者信息，如果为空提示错误信息
                    $carManageModel = new CarManageModel();
                    switch ($request->post('mark'))
                    {
                        case 1:
                            $markManageData = $carManageModel->getDepartManageData($this->appQychatId, $request->post('depart_id'), 1);
                            $markManageRet = $carManageModel->mergeDepartManage($markManageData);   //获取合并后的各个审核节点管理者
                            if( end($markManageRet)->member_id == $this->userId ){
                                $mark2ManageData = $carManageModel->getDepartManageData($this->appQychatId, 0, 2);  //派发流程节点，部门id统一为0
                                if( !empty($mark2ManageData))
                                {
                                    $nextManageData = reset($mark2ManageData);    //如果有派发节点，直接选第一个派发节点
                                } else {
                                    Db::rollback();
                                    \Util::echoJson('当前部门没有设置派发流程管理员，请联系客服');
                                }
                                if($request->post('reply_status') == 1)
                                    $carApplyModel->status = 2;     //审核通过
                            }else{
                                $nextKey = ( array_search($this->userId, array_keys($markManageRet)) )+1;     //[0=>'zhangsan',1=>'lisi'] 找到zhangsan的键'0' +1
                                $nextUserId = ( array_keys($markManageRet)[$nextKey] );  //获取当前用户所管理的审核节点的下一级节点
                                $nextManageData = $markManageRet[$nextUserId];  //获取当前应该指向的审核节点对象
                            }
                            break;
                        default:
                            $nextManageData['process_id'] = $request->post('next_process_id');
                            $nextManageData['member_id'] = $request->post('execute_id');
                            $carApplyModel->status = 3;     //已办理
                    }
                    //var_dump($nextManageData);die;
                }else{
                    \Util::echoJson($carApplyReplyModel->getError());
                }

            }
            else {
                //不是首次审核，通过reply_id 只修改apply_reply表的info字段
                $oldData = AppCarApplyReplyModel::where('id', $request->post('reply_id'))
                    ->where('qychat_id', $this->appQychatId)
                    ->where('apply_id', $request->post('apply_id'))
                    ->find();
                switch ($request->post('mark'))
                {
                    case 1:
                        $mark1Data = unserialize($oldData['info'])['mark1'];
                        $infoArr = [
                                'user_name' => $this->userName,
                                'process_name' => $currentProcessData->name,
                                'status'  => $request->post('reply_status'),
                                'content' => trim($request->post('reply_content')),
                                'time' => time()
                            ];
                        array_push($mark1Data, $infoArr);
                        $data['info'] = serialize( array('mark1' => $mark1Data) );
                        break;
                    case 2:
                        $mark2Data = unserialize($oldData['info']);
                        $infoArr = [
                            'user_name' => $this->userName,
                            'user_id'   => $this->userId,
                            'process_name' => $currentProcessData->name,
                            'execute_id'   => $request->post('execute_id'),
                            'content' => trim($request->post('reply_content')),
                            'time'    => time()
                        ];
                        $mark2Data['mark2'] = $infoArr;
                        $data['info'] = serialize( $mark2Data );
                        $data['execute_id'] = $request->post('execute_id');
                        break;
                    case 3:
                        $mark3Data = unserialize($oldData['info']);
                        $infoArr = [
                            'user_name' => $this->userName,
                            'process_name' => $currentProcessData->name,
                            'car_type' => $request->post('car_type'),
                            'desc'  => trim($request->post('desc')),
                            'cars_ids' => ($request->post('car_type') != 2) ? $_POST['cars_ids'] : array(),
                            'cars_num' => ($request->post('car_type') != 1) ? $_POST['cars_num'] : array(),
                            'time'     => time(),
                            'send_car_date' => $request->post('send_car_date'),
                            'end_info' => array()
                        ];
                        $mark3Data['mark3'] = $infoArr;
                        $data['info'] = serialize( $mark3Data );
                        break;
                    default:
                        $replyData = unserialize($oldData['info']);
                        if(isset($_POST['driver'])){
                            foreach ($_POST['driver'] as $key=>$vo){
                                $endInfo[$key]['driver'] = $vo;
                                $endInfo[$key]['mileage'] = $_POST['mileage'][$key];
                            }
                            $replyData['mark3']['end_info'] = $endInfo;
                        }
                        $replyData['mark3']['end_desc'] = $request->post('desc');
                        $data['info'] = serialize($replyData);

                }
                //开启事务
                Db::startTrans();
                $carApplyReplyModel = new AppCarApplyReplyModel();
                //$carApplyReplyModel->save([ 'info' => $data['info'] ], [ 'id' => $request->post('reply_id') ]);
                //根据qychat_id,depart_id,mark=1（审核流程） 取当前用户部门 审核流程 管理者信息，如果为空提示错误信息
                $carManageModel = new CarManageModel();
                switch ($request->post('mark'))
                {
                    case 1:
                        $markManageData = $carManageModel->getDepartManageData($this->appQychatId, $request->post('depart_id'), 1);
                        $markManageRet = $carManageModel->mergeDepartManage($markManageData);   //获取合并后的各个审核节点管理者
                        if( end($markManageRet)->member_id == $this->userId ){
                            $mark2ManageData = $carManageModel->getDepartManageData($this->appQychatId, 0, 2);  //派发流程节点，部门id统一为0
                            if( !empty($mark2ManageData))
                            {
                                $nextManageData = reset($mark2ManageData);    //如果有派发节点，直接选第一个派发节点
                            } else {
                                Db::rollback();
                                \Util::echoJson('当前部门没有设置派发流程管理员，请联系客服');
                            }
                            if($request->post('reply_status') == 1)
                                $carApplyModel->status = 2;     //审核通过
                        }else{
                            $nextKey = ( array_search($this->userId, array_keys($markManageRet)) )+1;     //[0=>'zhangsan',1=>'lisi'] 找到zhangsan的键'0' +1
                            $nextUserId = ( array_keys($markManageRet)[$nextKey] );  //获取当前用户所管理的审核节点的下一级节点
                            $nextManageData = $markManageRet[$nextUserId];  //获取当前应该指向的审核节点对象
                        }
                        break;
                    case 2:
                        $nextManageData['process_id'] = $request->post('next_process_id');
                        $nextManageData['member_id'] = $request->post('execute_id');
                        $carApplyModel->status = 3;     //已办理
                        break;
                    case 3:
                        //承办人选择派车形式，派车时间，填写车辆信息 修改app_apply表status
                        //$carApplyModel = AppCarApplyModel::get($request->post('apply_id'));
                        $carApplyModel->status = 4;     //已派车
                        $carApplyModel->type = $request->post('car_type');     //修改主表派车形式
                        if($carApplyReplyModel->save([ 'info' => $data['info'] ], [ 'id' => $request->post('reply_id') ])!==false && $carApplyModel->save()!==false ){
                            Db::commit();
                            \Util::echoJson('办理成功！', true, '/app/car/replyapply/id/'.$request->post('apply_id'));
                        }
                        break;
                    default:
                        //用车完成。承办人设置司机，填写里程 修改app_apply表status
                        //$carApplyModel = AppCarApplyModel::get($request->post('apply_id'));
                        $carApplyModel->status = 5;     //已结束
                        if($carApplyReplyModel->save([ 'info' => $data['info'] ], [ 'id' => $request->post('reply_id') ])!==false && $carApplyModel->save()!==false ){
                            Db::commit();
                            \Util::echoJson('用车结束！', true);
                        }
                }

            }

            //修改app_apply表信息
            //$carApplyModel = AppCarApplyModel::get($request->post('apply_id'));
            $carApplyModel->process_id = $nextManageData['process_id'];
            //如果审核节点拒绝申请,将apply表状态改为结束,当前审核节点id不变
            if($request->post('mark') == 1){
                if($request->post('reply_status') == 2){    //拒绝
                    $carApplyModel->status = 0;
                    $carApplyModel->process_id = $request->post('process_id');
                }
            }
            $carApplyModel->save();

            //如果审核通过，并添加成功后，发送消息
            if($request->post('reply_status') == 1){
                $id = ($request->post('reply_id') == 0) ? $carApplyReplyModel->id : $request->post('reply_id');
                $sendMessage['touser'] = $nextManageData['member_id'];
                $sendMessage['safe']   = 'news';
                $sendMessage['agentid']= 1;
                $newsData = [
                    'title' => '您有【'.$request->post('use_date').'-用车申请-'.$request->post('member_name').'】需处理',
                    'description' => '请点击处理',
                    'url' => '/app/car/replyapply?id='.$id,
                ];
                $sendMessage['news']['articles'] = $newsData;
            }else{
                //审核不通过，给申请人发送审核失败信息
                $sendMessage['touser'] = $carApplyModel['member_id'];
                $sendMessage['safe']   = 'news';
                $sendMessage['agentid']= 1;
                $newsData = [
                    'title' => '您有【'.$request->post('use_date').'-用车申请-'.$request->post('member_name').'】未通过审核',
                    'description' => '请点击查看',
                    'url' => '/app/car/apply?id='.$request->post('apply_id'),
                ];
                $sendMessage['news']['articles'] = $newsData;
            }
            //$this->wxapi->sendMessage($sendMessage);

            if($request->post('reply_id') == 0){
                if($carApplyReplyModel->save()!==false && $carApplyModel->save()!==false )
                    Db::commit();
                    \Util::echoJson('审核成功！',true);
            }else{
                if($request->post('mark') != 2){
                    if($carApplyReplyModel->save([ 'info' => $data['info'] ], [ 'id' => $request->post('reply_id') ])!==false && $carApplyModel->save()!==false )
                        Db::commit();
                        \Util::echoJson('审核成功！',true);
                }else{
                    if($carApplyReplyModel->save([ 'info' => $data['info'], 'execute_id' => $data['execute_id'] ], [ 'id' => $request->post('reply_id') ])!==false && $carApplyModel->save()!==false )
                        Db::commit();
                        \Util::echoJson('审核成功！',true);
                }
            }
            \Util::echoJson('审核失败！');

        } else {
            $id = $request->param('id');
            $data = AppCarApplyModel::get(['id' => $id, 'qychat_id' => $this->appQychatId]);
            if(!$data)
                $this->error('页面不存在！');
            //取审核状态
            switch ($data['status'])
            {
                case 1:
                    $processData = AppCarProcess::get($data['process_id']);
                    $tip = ($processData->mark == 1) ? ' 审核中' : ' 办理中';
                    $data['reply_status'] = ( !empty($processData)) ? $processData->name.$tip : '';
                    break;
                case 2:
                    $data['reply_status'] = '审核通过，办理中';
                    break;
                case 3:
                    $data['reply_status'] = '已办理，派车中';
                    break;
                case 4:
                    $data['reply_status'] = '已派车';
                    break;
                case 5:
                    $data['reply_status'] = '已结束';
                    break;
                default:
                    $data['reply_status'] = '已拒绝';
            }
            //根据 qychat_id+userid+depart_id+process_id 判断当前用户是否有审核权限 process_id=当前申请status
            //先通过申请的process_id判断当前状态是mark1还是mark2，mark2部门id是0
            $mark = CarProcessModel::get($data['process_id'])->mark;
            $manageDepartId = ($mark == 1) ? $data['department_id'] : 0;
            $userManageData = CarManageModel::where('qychat_id', $this->appQychatId)
                ->where('department_id', $manageDepartId)
                ->where('process_id', $data['process_id'])
                ->where('member_id', $this->userId)
                ->find();
            $canReply = ($userManageData) ? 1 : 2;  //如果有记录 1，反之 2

            //根据 qychat_id+userid+depart_id+apply_id 从apply_reply表取数据，如果没有 为第一次审批
            $applyReplyData = AppCarApplyReplyModel::where('qychat_id', $this->appQychatId)
                ->where('apply_id', $data['id'])
                ->where('department_id', $data['department_id'])
                ->find();
            $replyId = ($applyReplyData) ? $applyReplyData->id : 0;  //如果有记录返回id，反之0
            $applyReplyInfo = ($applyReplyData) ? unserialize($applyReplyData->info) : array();  //如果有记录 取info，并反序列化。反之为空
            if( !empty($applyReplyInfo)){   //取各个流程的进度
                $mark1ReplyData = isset($applyReplyInfo['mark1']) ? $applyReplyInfo['mark1'] : '';
                $mark2ReplyData = isset($applyReplyInfo['mark2']) ? $applyReplyInfo['mark2'] : '';
                $this->assign('mark1ReplyData', $mark1ReplyData);
                $this->assign('mark2ReplyData', $mark2ReplyData);
                $replyModel = new AppCarApplyReplyModel();
                $mark3ReplyData = $replyModel->getUseCarInfo($applyReplyInfo);
                $this->assign('mark3ReplyData', $mark3ReplyData);
            }

            $carManageModel = new CarManageModel();
            $mark3ManageData = $carManageModel->getDepartManageData($this->appQychatId, 0, 3);
            switch ($mark)
            {
                case 1:
                    true;
                    break;
                case 2:
                    //如果当前流程是派发，取办理人员数据
                    $mark3ManageIds = explode(',', $mark3ManageData[0]->member_id);     //取mark3的第一个节点，（承办人）
                    $mark3ManageRet = array();
                    foreach ($mark3ManageIds as $vv) {
                        $mark3ManageRet[] = MemberModel::where('qychat_id', $this->appQychatId)
                            ->where('userid', $vv)
                            ->find();
                    }
                    if(empty($mark3ManageRet))
                        \Util::echoJson('当前部门没有设置办理流程管理员，请联系客服');
                    $this->assign('mark3ManageRet', $mark3ManageRet);
                    $this->assign('nextProcessId', $mark3ManageData[0]->process_id);    //办理流程的节点id只能在业务开始前取
                    break;
                default:
                    //如果是办理流程（mark3）
                    $oldData = AppCarApplyReplyModel::where('apply_id', $id)
                        ->where('qychat_id', $this->appQychatId)
                        ->find();
                    $mark2Data = unserialize($oldData['info'])['mark2'];    //取派发管理员派发的对象,只有该对象才能办理此申请
                    $canReply = ($mark2Data['execute_id'] == $this->userId) ? 1 : 0;  //如果有记录 1，反之 2
                    //如果当前状态是1 审核中，进入选择派车形式，设置派车日期流程
                    if($data['status'] == 3){
                        $carsType1 = AppcarModel::where('type', 1)
                            ->where('qychat_id', $this->appQychatId)
                            ->select();
                        $carsType2 = AppcarModel::where('type', 2)
                            ->where('qychat_id', $this->appQychatId)
                            ->select();
                        $this->assign('carsType1', $carsType1);
                        $this->assign('carsType2', $carsType2);
                    }elseif( $data['status'] == 4 ){
                        //如果当前状态是4 已派车，进入设置司机流程
                        $mark = 4;
                        $mark3Data = unserialize($oldData['info'])['mark3'];
                        $carType = $mark3Data['car_type'];
                        if($carType != 2){  //内部用车
                            $carType1Ret = AppcarModel::where('type', 1)
                                ->where('qychat_id', $this->appQychatId)
                                ->where('id', 'IN', $mark3Data['cars_ids'])
                                ->select();
                            $this->assign('carType1Ret', $carType1Ret);
                        }
                        if($carType != 1){  //外部用车
                            $carsType2Data = $mark3Data['cars_num'];
                            foreach ($carsType2Data as $k=>$v) {
                                if($v > 0){
                                    $carType2Ret[$k]['carType2_name'] = AppcarModel::get($k)->name;
                                    $carType2Ret[$k]['carType2_num'] = $v;
                                }
                            }
                            $this->assign('carType2Ret', $carType2Ret);
                        }
                        //取司机
                        $driverIds = explode(',', $mark3ManageData[1]->member_id);     //取mark3的第二个节点（司机,取第一个司机）
                        $firstDriver = MemberModel::where('userid', $driverIds[0])
                                    ->where('qychat_id', $this->appQychatId)
                                    ->find();
                        $firstDriver = ( !empty($firstDriver)) ? $firstDriver->name : '';
                        $this->assign('driver', $firstDriver);
                    }
            }
            //var_dump($mark3ReplyData,$canReply,$mark);die;
            $this->assign('data', $data);
            $this->assign('mark', $mark);
            $this->assign('replyId', $replyId);
            $this->assign('canReply', $canReply);
            $this->assign('departId', $data['department_id']);
            return $this->fetch();
        }
    }

    /**
     * 审核申请列表
     * @return mixed
     */
    public function reply(){
        //根据 qychat_id+userid 判断当前用户是否有审核权限
        $manageData = CarManageModel::where('qychat_id', $this->appQychatId)
            ->select();
        $auth = false;
        foreach ($manageData as $v) {
            //$memberDepartIds[] = $v->department_id;
            if( in_array($this->userId,explode(',',$v->member_id)) ){
                $auth = true;
                $departAuthIds[]  = $v->department_id;
                if($v->department_id == 0){
                    $processAuthIds = $v->process_id;    //当前用户可管理的部门
                    break;
                } else {
                    $processAuthIds[] = $v->process_id;     //当前用户可管理的部门
                }
            }
        }
        if(!$auth){
            $this->error('您没有权限！');
        }

        //取数据
        //如果当前管理者拥有部门id=0的管理权限（表示为派发流程或办理流程），取数据不受部门限制
        //var_dump($departAuthIds,$processAuthIds);die;
        if(in_array(0, $departAuthIds)){
            $mark = AppCarProcess::get($processAuthIds)->mark;
            $carApplyModel = new AppCarApplyModel();
            $list = $carApplyModel->getAuthApplyData($this->appQychatId, $mark, $this->limt, $this->userId);
        }else{
            //获取当前管理者所拥有的节点id集合
            $userProcessIds = CarManageModel::where('qychat_id', $this->appQychatId)
                ->where('member_id', $this->userId)
                ->select();
            $carManageModel = new CarManageModel();
            $list = array();
            $data = AppCarApply::where('qychat_id', $this->appQychatId)
                ->where('department_id', 'IN', $departAuthIds)
                ->order('use_date desc')
                ->select();
            //var_dump($userProcessIds);die;
            foreach ($data as $k=>$v) {

                switch ($v['status'])
                {
                    //如果申请被某节点拒绝，只在这个节点和它之前的节点显示。（如 节点1,2,3，申请被2拒绝，只在1,2 显示）
                    case 0:
                        $markManageData = $carManageModel->getDepartManageData($this->appQychatId, $v['department_id'], 1);
                        $markManageRet = $carManageModel->mergeDepartManage($markManageData);   //获取合并后的各个审核节点管理者
                        $canSeeIdsArr1 = array();
                        //取当前申请的审核节点集合
                        foreach ($markManageRet as $vo1) {
                            $canSeeIdsArr1[] = $vo1->process_id;
                        }
                        //组合可见次申请的节点集合
                        foreach ($canSeeIdsArr1 as $vv2) {
                            if( $vv2 == $v['process_id'] ){
                                $canSeeArr[] = $vv2;
                                break;
                            }
                            $canSeeArr[] = $vv2;
                        }

                        //如果当前用户所拥有的管理节点在 当前申请的可见集合中
                        foreach ($userProcessIds as $vv3) {
                            if( in_array($vv3['process_id'], $canSeeArr) ){
                                $list[] = $v;
                            }
                        }
                        unset($canSeeIdsArr1, $canSeeArr, $markManageData, $markManageRet);
                        break;
                    case 1:
                        $markManageData = $carManageModel->getDepartManageData($this->appQychatId, $v['department_id'], 1);
                        $markManageRet = $carManageModel->mergeDepartManage($markManageData);   //获取合并后的各个审核节点管理者
                        $subStart = array_search($this->userId, array_keys($markManageRet));
                        $canSeeArr = array_slice($markManageRet, $subStart);
                        $canSeeIdsArr2 = array();
                        foreach ($canSeeArr as $vo2) {
                            $canSeeIdsArr2[] = $vo2->process_id;
                        }
                        if(in_array($v['process_id'], $canSeeIdsArr2)){
                            $list[] = $v;
                        }
                        unset($canSeeIdsArr2, $canSeeArr, $markManageData, $markManageRet);
                        break;
                    default:
                        $list[] = $v;
                }

            }

        }
        //取审核状态
        foreach ($list as $k=>$vo) {
            switch ($vo['status'])
            {
                case 1:
                    $processData = AppCarProcess::get($vo['process_id']);
                    $tip = ($processData->mark == 1) ? ' 审核中' : ' 办理中';
                    $vo['reply_status'] = ( !empty($processData)) ? $processData->name.$tip : '';
                    break;
                case 2:
                    $vo['reply_status'] = '审核通过，办理中';
                    break;
                case 3:
                    $vo['reply_status'] = '已办理，派车中';
                    break;
                case 4:
                    $vo['reply_status'] = '已派车';
                    break;
                case 5:
                    $vo['reply_status'] = '已结束';
                    break;
                default:
                    $vo['reply_status'] = '已拒绝';
            }
            $list[$k] = $vo;
        }
        $this->assign('list', $list);
        $this->assign('depart_id', '0');
        return $this->fetch();
    }

}
