<?php
/**
 * 文件的简短描述：考试应用
 * 
 * LICENSE:
 * @author lijin
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\home\controller;

use think\Request;
use think\Cache;

use app\home\model\AppExam as ExamModel;
use app\home\model\AppExamLibrary as LibraryModel;
use app\home\model\AppExamProblem as ProblemModel;
use app\home\model\AppExamAnswer as AnswerModel;
use app\home\model\Department as DepartmentModel;
use app\home\model\Member as MemberModel;
use app\home\model\MemberDepartment as MemberDepartmentModel;

class Exam extends Base
{
    public function __construct()
    {
        parent::__construct();

        $request = Request::instance();
        $this->qychatId=7;
        $this->assign('class', 'app');
        $this->assign('subClass', $request->controller());
        $this->assign('action', $request->action());
    }

    // 试卷列表
    public function index()
    {
        $examList    = ExamModel::all(['qychat_id' => $this->qychatId]);
        $libListAll  = LibraryModel::all(['qychat_id' => $this->qychatId]);

        foreach ($libListAll as $key => $value)
        {
            $libList[$value['id']] = $value['title'];
        }
    
        foreach ($examList as $key => &$value)
        {   
            $libName = [];
            $libIds = explode(',', $value['lib_id']);
            foreach ($libIds as $libid)
            {
                $libName[] = isset($libList[$libid]) ? $libList[$libid] : '';
            }

            $value['lib_name'] = implode(', ', $libName);

            $value['partakeInfo'] = self::getPartake($value['departs'], $value['tags'], $value['users']);
        }

        $this->assign('examList', $examList);

        return $this->fetch();
    }

    // 获取参与者信息
    private function getPartake($depart, $tag, $user)
    {
        $departsArr = array_filter(json_decode($depart));
        $tagsArr    = array_filter(json_decode($tag));
        $usersArr   = array_filter(json_decode($user));

        $departObj  = new DepartmentModel();
        $memberObj  = new MemberModel();

        $partakeInfo = "";

        if ($departsArr)
        {
            $departs = $departObj->where(['qychat_id' => $this->qychatId, 'department_id' => ['in', $departsArr]])->field('department_name')->select();
            foreach ($departs as $key => $value)
            {
                $partakeInfo .= "{$value->department_name} ";
            }
        }
        if ($tagsArr)
        {
            $departs = DepartmentModel::all(['qychat_id' => $this->qychatId, 'tag_id' => ['in', $tagsArr]]);
            foreach ($departs as $key => $value)
            {
                $partakeInfo .= "{$value['tag_name']} ";
            }
        }
        if ($usersArr)
        {
            $departs = $memberObj->where(['qychat_id' => $this->qychatId, 'userid' => ['in', $usersArr]])->field('name')->select();
            foreach ($departs as $key => $value)
            {
                $partakeInfo .= "{$value->name} ";
            }
        }
        
        return $partakeInfo; 
    }

    // 添加试卷
    public function addExam()
    {
        // 部门
        $departObj = new DepartmentModel();
        $departs   = $departObj->getDepartmentTreeList($this->qychatId);
        $tree = new \Tree();
        $tree->tree($departs);
        $str  = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $departList = $tree->getTree(0, $str, 0);
        // 标签
        $tagList = [];//TagModel::all(['qychat_id' => $this->qychatId]);

        // 题库
        $libList = LibraryModel::all(['qychat_id' => $this->qychatId, 'status' => ['lt', 3]]);

        $this->assign('departList', $departList);
        $this->assign('tagList', $tagList);
        $this->assign('libList', $libList);

        return $this->fetch();
    }

    // 查询试卷详情
    public function getExamInfo($id = 0)
    {
        $id = intval($id);
        if (! $id)
        {
            $this->redirect('addExam');
        }

        $examInfo = ExamModel::get(['qychat_id' => $this->qychatId, 'id' => $id]);
        $examInfo['que_opt'] = explode('|', $examInfo['que_opt']);
        // 部门
        $departObj = new DepartmentModel();
        $departs   = $departObj->getDepartmentTreeList($this->qychatId);
        $tree = new \Tree();
        $tree->tree($departs);
        $str  = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $departList = $tree->getTreeMulti(0, $str, implode(',', json_decode($examInfo['departs'])));

        // 标签
        $tagList = [];

        // 题库
        $libList = LibraryModel::all(['qychat_id' => $this->qychatId, 'status' => ['lt', 3]]);

        $this->assign('departList', $departList);
        $this->assign('tagList', $tagList);
        $this->assign('libList', $libList);
        $this->assign('info', $examInfo);

        return $this->fetch('addexam');
    }
    // 查询试卷的题目详情
    public function examproblems($id = 0)
    {
        $id = intval($id);
        if (! $id)
        {
            $this->redirect('index');
        }

        $examInfo = ExamModel::get(['qychat_id' => $this->qychatId, 'id' => $id]);
        if (! $examInfo)
        {
            $this->redirect('index');
        }

        $examInfo['lib_id']     = explode(',', $examInfo['lib_id']);
        $examInfo['questions']  = json_decode($examInfo['questions'], true);
        $libList   = [];
        $seLibList = LibraryModel::all(['qychat_id' => $this->qychatId, 'id' => ['in', $examInfo['lib_id']]]);
        foreach ($seLibList as $key => $value)
        {
            $libList[$value['id']] = $value['title'];
        }

        $this->assign('libList', $libList);
        $this->assign('info', $examInfo);

        return $this->fetch();
    }

    // ajax 查询类库中的题目
    // 动态选择题目用
    public function getExamProblems($id = 0, $sid = '')
    {
        $request = Request::instance();
        $libId   = explode('~', $id);
        $libId   = array_filter($libId);
        $sid     = explode('~', $sid);
        $sid     = array_filter($sid);

        if (! $libId)
        {
            \Util::echoJson('操作成功', true);
        }

        $seLibList = LibraryModel::all(['qychat_id' => $this->qychatId, 'id' => ['in', $libId]]);
        foreach ($seLibList as $key => $value)
        {
            $libList[$value['id']] = $value['title'];
        }

        $problems = ProblemModel::all(['qychat_id' => $this->qychatId, 'lib_id' => ['IN', $libId] ]);

        $tmpProList = [];
        foreach ($problems as $key => $value)
        {
            $info   = [];
            $info[] = "<label class='pos-rel'><input type='checkbox' class='ace' name='delpro' value='" . $value['id'] . "'/><span class='lbl'></span></label>";//" . (in_array($value['id'], $sid) ? 'checked="checked"':'' ). "
            $info[] = $value['title'];
            $info[] = isset($libList[$value['lib_id']]) ? $libList[$value['lib_id']] : '题库出错';
            $info[] = $value['type'] == 1 ? '单选' : '多选';
            $info[] = $value['score'];
            $info[] = date('Y-m-d H:i:s', $value['create_time']);

            $tmpProList[] = $info;
        }

        $msg = array(
            'status'  => 1,
            'message' => '操作成功',
            'data'    => $tmpProList,
        );
        $msg = json_encode($msg);

        header("Cache-Control: no-cache");
        header('Content-Length: ' . strlen($msg));
        exit($msg);
    }
    // 查看时间试卷结果
    public function examAnswer($id = 0)
    {
        $id = intval($id);

        $examAnswerList = Cache::get("examResult{$this->qychatId}{$id}");

        if (! $examAnswerList)
        {
            $examAnswerList = AnswerModel::all(['qychat_id' => $this->qychatId, 'exam_id' => $id]);

            $departList = \QychatLib::getDepartsName($this->qychatId);

            foreach ($examAnswerList as $key => &$value) 
            {
                $userInfo  = \QychatLib::getUserRelations($this->qychatId, $value['user_id']);
                $value['timeCost'] = self::getTimeMinSec($value['end_time'] - $value['start_time']);
                $value['userName'] = isset($userInfo['name']) ? $userInfo['name'] : '';
                $value['depart']   = isset($departList[$userInfo['departId']]) ? $departList[$userInfo['departId']] : '';
            }
        }

        $this->assign('examAnswerList', $examAnswerList);

        return $this->fetch();
    }

    // 保存试卷信息
    public function saveExam()
    {
        $request = Request::instance();
        $id      = intval($request->post('id'));
        $problem = $request->post('problem/a');
        $data    = [
            'qychat_id'   => $this->qychatId,
            'lib_id'      => implode(',', array_filter($request->post('libId/a'))),
            'title'       => trim($request->post('title')),
            'description' => trim($request->post('description')),
            'status'      => intval($request->post('status')),
            'start_time'  => trim($request->post('start')),
            'end_time'    => trim($request->post('end')),
            'total'       => intval($request->post('total')),
            'item_choose' => intval($request->post('itemChoose')),
            'limit_time'  => intval($request->post('limitTime')),
            'pro_num'     => intval($request->post('nums')),
            'departs'     => $request->post('depart/a'),
            'tags'        => $request->post('tag/a'),
            'users'       => $request->post('users/a'),
            'que_opt'     => $problem ? array_filter($problem) : [],
        ];

        $data['departs']    = $data['departs'] ? array_filter($data['departs']) : [];
        $data['tags']       = $data['tags'] ? array_filter($data['tags']) : [];
        $data['users']      = $data['users'] ? array_filter($data['users']) : [];
        $data['departs']    = json_encode($data['departs']);
        $data['tags']       = json_encode($data['tags']);
        $data['users']      = json_encode($data['users']);
        $data['start_time'] = $data['start_time'] ? strtotime($data['start_time']) : 0;
        $data['end_time']   = $data['end_time'] ? strtotime($data['end_time']) : 0;

        if (! $data['title'] || ! $data['lib_id'] ||
           ( $data['item_choose'] == 1 && ($data['pro_num'] == 0 || ! $data['total']) ||  $data['item_choose'] == 2 && ! $data['que_opt']))
        {
             \Util::echoJson('请求参数错误');
        }

        if ($data['item_choose'] == 1)
        {   //机选
            $data['questions'] = self::getExamProblem($data['lib_id'], $data['total'], $data['pro_num']);

            if (! $data['questions'])
            {
                \Util::echoJson('题目机选失败');
            }
        } 
        else
        { //自选
            $questions  = ProblemModel::all(['qychat_id' => $this->qychatId, 'id' => ['in', $data['que_opt']]]);
            $totalScore = 0;
            foreach ($questions as $vo)
            {
                $totalScore += $vo['score'];
                $data['questions'][] = [
                    'id'        => $vo['id'],
                    'lib_id'    => $vo['lib_id'],
                    'title'     => $vo['title'],
                    'type'      => $vo['type'],
                    'score'     => $vo['score'],
                    'answer'    => json_decode($vo['answer'], true),
                    'options'   => json_decode($vo['options'], true),
                ];
            }

            $data['total']   = $totalScore;
            $data['pro_num'] = count($questions);
        }

        $data['que_opt']    = implode('|', $data['que_opt']);
        $data['questions']  = json_encode($data['questions']);
        
        $examObj = new ExamModel();
        if ($id)
        {
            $res = $examObj->save($data, ['id' => $id]);
        }
        else
        {
            $res =$examObj->save($data);
        }


        // 发送问卷消息
        if (! $res)
        {
            \Util::echoJson('操作失败');
        }

        \Util::echoJson('操作成功', true);
    }

    // 删除试卷
    public function deleteExam($id = 0)
    {
        $id = intval($id);

        if (! $id)
        {
            \Util::echoJson('参数错误');
        }

        $isAnswer = AnswerModel::where(['qychat_id' => $this->qychatId, 'exam_id' => $id])->count();

        if ($isAnswer > 0)
        {
            \Util::echoJson('试卷已有人做答，不能删除');
        }

        $res = ExamModel::where(['qychat_id' => $this->qychatId, 'id' => $id])->delete();

        if (! $res)
        {
            \Util::echoJson('操作失败');
        }

        \Util::echoJson('操作成功', true);

    }

    // =========添加试卷的私有方法============
    /**
     * 机选选题
     * @param  array   $libId 题库id
     * @param  integer $total 总分
     * @param  integer $nums  题目数量
     * @return mix
     */
    private function getExamProblem($libId, $total, $nums)
    {
        $libId = is_array($libId) ? array_filter($libId) : [$libId];
        $total = floatval($total);
        $nums  = intval($nums);

        if (! $libId || ! $total || ! $nums)
        {
            return [];
        }

        $problemObj = new ProblemModel();
        $proInfo    = $problemObj->where(['qychat_id' => $this->qychatId, 'lib_id' => ['IN', $libId]])->group('score')->order('null')->field('score, SUM(score) as scoreTotal, COUNT(id) as count')->select();

        $scores     = [];
        $tmpTotal   = 0;
        foreach ($proInfo as $key => $value)
        {
            $scores[$value['score']] = $value['count'];
            $tmpTotal += $value['scoreTotal'];
        }

        if (array_sum(array_values($scores)) < $nums || $tmpTotal < $total)
        {
            return [];
        }

        $selected  = [];
        $scoreKeys = array_keys($scores);
        $minScore  = min($scoreKeys);
        $avg = round($total / $nums, 1);

        if ($minScore > $avg || $minScore == $avg && $scores[$minScore] < $nums)
        {
            return [];
        }

        $problemList = [];

        $problems = ProblemModel::all(['qychat_id' => $this->qychatId, 'lib_id' => ['IN', $libId]]);

        $selected = self::selectProblem($problems, $total, $nums);

        foreach ($selected as $pkey => $vo)
        {
            $problemList[] = [
                'id'        => $vo['id'],
                'lib_id'    => $vo['lib_id'],
                'title'     => $vo['title'],
                'type'      => $vo['type'],
                'score'     => $vo['score'],
                'answer'    => json_decode($vo['answer'], true),
                'options'   => json_decode($vo['options'], true),
            ];
        }

        return $problemList;
    }

    /**
     * 随机选题
     * @param  array    $problems   题目详情
     * @param  floatval  $totalScore 选题总分
     * @param  integer  $proNums    试题数量
     * @param  integer  $num        循环次数
     * @return array
     */
    private function selectProblem($problems, $totalScore, $proNums, $num = 1)
    {
        $scoreTotal = 0;
        $flag       = 0;
        $seProblem  = [];
        shuffle($problems);
        foreach ($problems as $key => $value)
        {
            if($num != 1 && $key == count($problems) - $num)
            {
                continue;
            }

            $scoreTotal   += $value->score;
            $seProblem[]  =  $value;
            $seNums       =  count($seProblem);

            if ($scoreTotal < $totalScore && $seNums < $proNums)
            {
                continue;
            }
           
            if ($scoreTotal == $totalScore && $seNums == $proNums)
            {
                $flag = 1;
                break;
            }

            array_pop($seProblem);
            $scoreTotal -= $value->score; 
        }

        if($flag == 1)
        {
            return $seProblem;
        }
        //如果循环次数等于数组长度则未匹配成功
        if($num >= count($problems))
        {
            return [];
        }

        return self::selectProblem($problems, $totalScore, $proNums, $num + 1);
    }


    // ========================================================
    // =========================题库 start=====================
    // ========================================================
    // 题库列表
    public function lib()
    {
        $libList = LibraryModel::all(['qychat_id' => $this->qychatId, 'status' => ['lt', 3]]);

        $this->assign('libList', $libList);
        $extInfo = \QychatLib::getExcelExt();
        $this->assign('ext', json_encode($extInfo['ext']));
        $this->assign('mime', json_encode($extInfo['mime']));

        return $this->fetch();
    }

    // 新增题库
    public function addLib()
    {
        return $this->fetch();
    }

    // 查询题库详情
    public function getLibInfo($id = 0)
    {
        $id = intval($id);
        if (! $id)
        {
            \Util::echoJson('参数错误');
        }

    	$info = LibraryModel::get(['qychat_id' => $this->qychatId, 'id' => $id]);

        if (! $info)
        {
            \Util::echoJson('查询失败');
        }

        \Util::echoJson('操作成功', true, $info);
    }

    // 删除题库
    public function deleteLib($id = 0)
    {
        $id = intval($id);

        if (! $id)
        {
            \Util::echoJson('参数错误');
        }

        $liPproblemList = ProblemModel::all(['qychat_id' => $this->qychatId, 'lib_id' => $id]);
        if ($liPproblemList)
        {
            \Util::echoJson('改题库下还有题目，不能删除'); 
        }

        $res = LibraryModel::where(['qychat_id' => $this->qychatId, 'id' => $id])->delete();

        if (! $res)
        {
            \Util::echoJson('操作失败');
        }

        \Util::echoJson('操作成功', true);

    }

    // 保存题库
    public function saveLib()
    {
        $request = Request::instance();
        $id      = intval($request->post('id'));
        $data    = array(
            'qychat_id'   => $this->qychatId,
            'title'       => trim($request->post('title')),
            'description' => trim($request->post('description')),
            'status'      => intval($request->post('status')),
        );

        if (empty($data['title']) || strlen($data['title']) > 100)
        {
            \Util::echoJson('请求参数错误');
        }

        $libObj = new LibraryModel();
        if ($id)
        {
            $res = $libObj->save($data, ['id' => $id]);
        }
        else
        {
            $res = $libObj->save($data);
        }

        if (! $res)
        {
            \Util::echoJson('操作失败');
        }

        \Util::echoJson('操作成功', true);
    }

    // ========================================================
    // =========================题目 start=====================
    // ========================================================
    // 题目列表
    public function problem($id = 0)
    {
        $libId  = intval($id);

        if (! $libId)
        {
            $this->error('请求参数错误', 'lib');
        }

        $problemList = ProblemModel::all(['qychat_id' => $this->qychatId, 'lib_id' => $libId]);

        $libListAll  = LibraryModel::all(['qychat_id' => $this->qychatId]);

        foreach ($libListAll as $key => $value)
        {
            $libList[$value['id']] = $value['title'];
        }

        $extInfo = \QychatLib::getExcelExt();
        $this->assign('ext', json_encode($extInfo['ext']));
        $this->assign('mime', json_encode($extInfo['mime']));

        $this->assign('problemList', $problemList);
        $this->assign('libList', $libList);

        return $this->fetch();
    }

    // 添加题目
    public function addProblem($id = 0)
    {
        $libList = LibraryModel::all(['qychat_id' => $this->qychatId, 'status' => ['lt', 3]]);

        $this->assign('libList', $libList);
        $this->assign('libId', intval($id));

        return $this->fetch();
    }

    // 查询题库详情
    public function getProblemInfo($id = 0)
    {
        $id   = intval($id);
        $info = [];

        if ($id)
        {
            $info = ProblemModel::get(['qychat_id' => $this->qychatId, 'id' => $id]);

            if ($info)
            {
                $info['options'] = json_decode($info['options'], true);
            }
        }

        $libList = LibraryModel::all(['qychat_id' => $this->qychatId, 'status' => ['lt', 3]]);

        $this->assign('info', $info);
        $this->assign('libId', $info ? $info['lib_id'] : 0);
        $this->assign('libList', $libList);

        return $this->fetch('addProblem');
    }

    // 删除题目
    public function deleteProblem($id = 0)
    {
        $id = intval($id);

        if (! $id)
        {
            \Util::echoJson('参数错误');
        }

        $res = ProblemModel::where(['qychat_id' => $this->qychatId, 'id' => $id])->delete();

        if (! $res)
        {
            \Util::echoJson('操作失败');
        }

        \Util::echoJson('操作成功', true);
    }

    // 批量删除
    public function deleteProblemBatch()
    {
        $request    = Request::instance();
        $deleteIds  = explode('|', $request->post('delIds'));

        if (! $deleteIds)
        {
            \Util::echoJson('参数错误');
        }

        $res = ProblemModel::where(['qychat_id' => $this->qychatId, 'id' => ['in', $deleteIds]])->delete();

        if (! $res)
        {
            \Util::echoJson('操作失败');
        }

        \Util::echoJson('操作成功', true);
    }

    // 批量变更题库所属
    public function problemmovebatch()
    {
        $request = Request::instance();
        $pids    = explode('~', $request->post('ids'));
        $libId   = intval($request->post('libId'));

        if (! $pids || ! $libId)
        {
             \Util::echoJson('参数错误');
        }

        $problemObj = new ProblemModel();

        $res = $problemObj->where(['qychat_id' => $this->qychatId, 'id' => ['in', $pids]])->setField('lib_id', $libId);

        if ($res === false)
        {
            \Util::echoJson('操作失败');
        }

        \Util::echoJson('操作成功', true);
    }

    // 保存题目
    public function saveProblem()
    {
        $request = Request::instance();
        $id      = intval($request->post('id'));
        $options = $request->post('seopt/a');
        $libId   = explode(',', $request->post('libId'));
        $data    = [
            'qychat_id' => $this->qychatId,
            'lib_id'    => intval($request->post('libId')),
            'title'     => trim($request->post('title')),
            'type'      => intval($request->post('type')),
            'score'     => floatval($request->post('score')),
        ];

        if (empty($data['title']) || strlen($data['title']) > 100)
        {
            \Util::echoJson('请求参数错误');
        }

        $scoreTotal = 0;

        foreach ($options as $value)
        {
            if (empty($value['title']) || floatval($value['score']) < 0)
            {
                continue;
            }

            $tempOpt[] = $value;

            if ($value['score'] > 0)
            {
                $scoreTotal += floatval($value['score']);
                $answer[] = count($tempOpt);
            }
        }

        if (! isset($tempOpt) || count($tempOpt) < 2 ||  $scoreTotal < 1 || $scoreTotal > 100 || $data['type'] == 1 && count($answer) != 1)
        {
            \Util::echoJson('请求参数错误');
        }

        $data['score']   = $scoreTotal;
        $data['options'] = json_encode($tempOpt);
        $data['answer']  = json_encode($answer);

        $problemObj = new ProblemModel();

        if ($id)
        {
            $res = $problemObj->save($data, ['id' => $id]);
        }
        else
        {
            $res = $problemObj->save($data);
        }

        if ($res === false)
        {
            \Util::echoJson('操作失败');
        }

        \Util::echoJson('操作成功', true);
    }

    // 导入题目
    public function import()
    {   
        $request = Request::instance();
        $file    = $request->file('file');
 
        if (! is_object($file))
        {
            $this->error('请求参数错误', 'lib');
        }

        $fileMime = $_FILES["file"]["type"];
        $ext      = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
        $extInfo  = \QychatLib::getExcelExt();

        if (! in_array($ext, $extInfo['ext']) || ! in_array($fileMime, $extInfo['mime']))
        {
            $this->error('文件类型不支持', 'lib');
        }

        $info   = $file->move(ROOT_PATH . '/uploads/', true, false);

        if (! is_object($info))
        {
            $this->error('文件读取失败', 'lib');
        }

        $filePath = $info->getRealPath();

        // 下面是读取并保存数据
        ob_start();
        if ($ext == 'csv')
        {
            $fileData  = file_get_contents($filePath);
            $fileData  = explode("\r\n", $fileData);

            foreach ($fileData as $key => &$value)
            {
                $value = explode(',', $value);
            }
        }
        else
        {
            $fileData = \ExcelRead::read($filePath);
        }

        // 保存数据
        $problemObj = new ProblemModel();

        $datas = array_chunk($fileData, 200);

        // var_dump($datas);  die;

        $msg    = [];

        $count  = count($datas);

        for($i = 0; $i < $count; $i ++)
        {
            $data       = $datas[$i];
            $saveArray  = [];
            $titleType  = [];
            foreach ($data as $key => $value)
            {
                $value = array_values($value);
                
                if (isset($value[0]) && empty($value[0]))
                {
                    continue;
                }

                $libId   = intval($value[0]);
                $title   = trim($value[1]);
                $type    = intval($value[2]);
                $score   = floatval($value[3]);
                if ($ext == 'csv')
                {
                    $title = iconv('GB2312', 'UTF-8', $title);
                }

                // 校验题库
                $libIsExit = LibraryModel::where(['id' => $libId, 'qychat_id' => $this->qychatId]);
                if (! $libIsExit)
                {
                    $msg[] = "{$title} 题库不存在";
                    continue;
                }

                // 问题重复：名字、类型一致
                $problemIsRepeat = ProblemModel::get(['title' => $title, 'type' => $type, 'qychat_id' => $this->qychatId]);

                if (isset($problemIsRepeat->title) || in_array("{$title}{$type}", $titleType))
                {
                    $msg[] = "{$title} 题目已存在";
                    continue;
                }

                $optionCount = count($value);
                $options     = [];
                $answer      = [];
                $sumAnswer   = 0;
                $i           = 4; 

               do {
                    $tmp          = [];
                    $tmp['title'] = trim($value[$i]);
                    $tmp['score'] = floatval($value[$i + 1]);
                    if ($ext == 'csv')
                    {
                        $tmp['title'] = trim(iconv('GB2312', 'UTF-8', $value[$i]));
                    }

                    $i = $i + 2;

                    if (empty($tmp['title']) || $tmp['score'] < 0)
                    {
                        continue;
                    }

                    $options[] = $tmp;

                    if ($tmp['score'] > 0)
                    {
                        $answer[]  = count($options); 
                        $sumAnswer += $tmp['score']; 
                    }                    

                } while ($i < $optionCount);

                if (empty($options) || empty($answer) || $sumAnswer <= 0)
                {
                    $msg[] = "{$title} 选项设置出错";
                    continue;
                }

               if ($sumAnswer != $score)
                {
                    $score = $sumAnswer;
                }

                $recordTmp = [
                    'qychat_id'     => $this->qychatId,
                    'lib_id'        => $libId,
                    'title'         => $title,
                    'type'          => $type,
                    'score'         => $score,
                    'answer'        => json_encode($answer),
                    'options'       => json_encode($options),
                    'create_time'   => time(),
                    'update_time'   => time(),
                ];
                
                $titleType[]  = "{$title}{$type}";

                $saveArray[]  = $recordTmp;
            }

            $saveArray = array_filter($saveArray);
      
            if ($saveArray)
            {
                $res = $problemObj->saveAll($saveArray);
            }
        }

        unlink($filePath);

        ob_end_flush();

        $this->error('操作成功!', 'lib');
    }


    // 试卷导出结果
    public function export($id = 0)
    {   
        set_time_limit(0);
        if (! $id)
        {
            $this->error('请求参数错误', 'index');
        }

        $examInfo   = ExamModel::get(['qychat_id' => $this->qychatId, 'id' => $id]);
        if (! isset($examInfo['title']))
        {
            $this->error('您访问的记录不存在', 'index');
        }

        ob_start();

        $examResult = AnswerModel::all(['qychat_id' => $this->qychatId, 'exam_id' => $id]);
        $departList = \QychatLib::getDepartsName($this->qychatId);

        $datalist   = iconv('utf-8', 'gbk', "部门") . "\t" . iconv('utf-8', 'gbk', "姓名") . "\t" . iconv('utf-8', 'gbk', "分数") . "\t" . iconv('utf-8', 'gbk', "耗时(分)") . "\t" . iconv('utf-8', 'gbk', "答题时间") . "\t\n";
        $filename   = "{$examInfo['title']}考试结果.xls";

        foreach ($examResult as $key => $value)
        {
            $userInfo  = \QychatLib::getUserRelations($this->qychatId, $value['user_id']);
            if (! $userInfo)
            {
                continue;
            }

            $userId     = $userInfo['userId'];
            $userName   = iconv('utf-8', 'gbk', $userInfo['name']);
            $depart     = isset($departList[$userInfo['departId']]) ? iconv('utf-8', 'gbk', $departList[$userInfo['departId']]) : '';

            $datalist .= $depart . "\t" . $userName . "\t" . $value['total'] . "\t" . iconv('utf-8', 'gbk', self::getTimeMinSec(($value['end_time'] - $value['start_time']) / 60)) . "\t" . date('Y-m-d H:i:s', $value['start_time']) . "\t\n";
        }

        ob_end_flush();

        \ExcelMaker::exportCsv($filename, $datalist);
    }

    private function getTimeMinSec($time)
    {
        $min = floor($time / 60);
        $sec = $time % 60;

        return "{$min}分{$sec}秒";
    }
   
}
