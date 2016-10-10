<?php
/**
 * 文件的简短描述：考试用户操作界面
 * 
 * LICENSE:
 * @author lijin
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\app\controller;

use think\Request;
use think\Session;

use app\home\model\AppExam as ExamModel;
use app\home\model\AppExamAnswer as AnswerModel;

class Exam extends Base
{
    public function __construct()
    {
        parent::__construct();

    }

	// 所有进行中的问卷
	public function index()
	{
		// 该用户能参与的问卷列表，用户所在部门、标签
		$userRelation = \QychatLib::getUserRelations($this->appQychatId, $this->userId);
		$userDeaprts  = \QychatLib::getDepartIds($this->appQychatId, $userRelation['departId']);
		$userDeaprts  = explode('-', $userDeaprts);

		$where 	= [
			'qychat_id' => $this->appQychatId,
			'status' => 1,
			'start_time' => ['elt', time()],
			'end_time' => ['egt',  time()],
		];
		$examList = ExamModel::where($where)->order('id desc')->field('id, title, departs, tags, users, end_time')->select();

		$examIds = [];
		$result  = [];
		foreach ($examList as $key => $value)
		{
			$value 	 = $value->toArray();
			$departs = json_decode($value['departs']);
			$tags	 = json_decode($value['tags']);
			$users 	 = json_decode($value['users']);

			if ($departs && array_intersect($userDeaprts, $departs) || 
				$tags && array_intersect($userRelation['tags'], $tags) || 
				$users && in_array($this->userId, $users))
			{
				$result[$value['id']] = $value;
				$examIds[] = $value['id'];
			}
		}

		$answerIds  = [];
		$answerList = [];

		if ($examIds)
		{
			$answerList = AnswerModel::where(['qychat_id' => $this->appQychatId, 'user_id' => $this->userId, 'exam_id' => ['in', $examIds]])->field('exam_id')->select();
			foreach ($answerList as $key => $value)
			{
				$answerIds[] = $value->exam_id;
				$exitKey = array_search($value->exam_id, $examIds);
				unset($examIds[$exitKey]);
			}
			unset($answerList);
		}

		$this->assign('examList', $result);
		$this->assign('endIds', $answerIds);//已答
		$this->assign('inIds', $examIds);//未答

		return $this->fetch();
	}

	// 查询试卷详情
	public function examInfo($id)
	{
		$id = intval($id);
		if (! $id)
		{
			$this->redirect('index');
		}

		$info = ExamModel::get(['qychat_id' => $this->appQychatId, 'id' => $id]);
		if (! $info)
		{
			$this->redirect('index');
		}

		$userRelation = \QychatLib::getUserRelations($this->appQychatId, $this->userId);
		$userDeaprts  = \QychatLib::getDepartIds($this->appQychatId, $userRelation['departId']);
		$userDeaprts  = explode('-', $userDeaprts);

		$departs = json_decode($info['departs']);
		$tags	 = json_decode($info['tags']);
		$users 	 = json_decode($info['users']);

		if ($departs && array_intersect($userDeaprts, $departs) || 
			$tags && array_intersect($userRelation['tags'], $tags) || 
			$users && in_array($this->userId, $users))
		{
			
		}
		else
		{
			$this->redirect('index');
		}

		$answer = AnswerModel::get(['qychat_id' => $this->appQychatId, 'exam_id' => $id, 'user_id' => $this->userId]);

		$info['tag'] = 0;
		if ($answer)
		{
			$info['totalScore'] = $answer['total'];
			$info['answerInfo'] = json_decode($answer['answer_info'], true);
			$info['tag'] = 1;
		}

		$info['questions'] = json_decode($info['questions'], true);

		$this->assign('examInfo', $info);
		
		return $this->fetch();
	}

	// 保存答题，计算得分
	public function save($id = 0)
	{
		$request = Request::instance();
		$data    = $request->post('data/a');
		$startTime = intval(Session::get('examSatrt'));
		$endTime   = time();

		$id  = intval($id);
		if (! $id || $endTime - $startTime < 60) //一分钟之内不能提交试卷
		{
			\Util::echoJson('请求参数错误');
		}

		$examInfo = ExamModel::get(['qychat_id' => $this->appQychatId, 'id' => $id]);

		if (! $examInfo)
		{
			\Util::echoJson('该问卷您没有权限答题');
		}

		$answer = AnswerModel::get(['qychat_id' => $this->appQychatId, 'exam_id' => $id, 'user_id' => $this->userId]);
		if ($answer)
		{
			\Util::echoJson('改试卷您已经答过题了');
		}

		// 存在bug有待优化
		$answerInfo = [];
		foreach ($data as $key => $value)
		{
			$keyVal = explode('-', $key);
			$val 	= [];
			if (is_array($value))
			{
				foreach ($value as $k => $vo)
				{
					$val[] = intval($vo);
				}
			}
			else
			{
				$val[] = intval($value);
			}

			$answerInfo[$keyVal[1]] = $val;
		}

		// 计算得分
		$totalScore = 0;
		$questions = json_decode($examInfo['questions'], true);
		
		foreach ($questions as $key => $value)
		{
			$answer  = json_decode($value['answer'], true);
			$options = json_decode($value['options'], true);

			if (empty($answerInfo[$value['id']]))
			{
				continue;
			}
			if (empty(array_diff($answer, $answerInfo[$value['id']])) && empty(array_diff($answerInfo[$value['id']], $answer)))
			{
				$totalScore += $value['score'];
				continue;
			}
			// 单选
			if ($value['type'] == 1)
			{
				continue;
			}
			if (count($answer) < count($answerInfo[$value['id']]) || ! empty(array_diff($answerInfo[$value['id']], $answer)))
			{
				continue;
			}
			// 复选
			foreach ($options as $opkey => $opval)
			{
				if (in_array(($opkey + 1), $answerInfo[$value['id']]))
				{
					$totalScore += $opval['score'];
				}
			}
		}

		$row = [
			'qychat_id'	  => $this->appQychatId,
			'user_id'	  => $this->userId,
			'exam_id'	  => $id,
			'total'		  => $totalScore,
			'answer_info' => json_encode($answerInfo),
			'start_time'  => $startTime,
			'end_time'	  => $endTime,
		];

		$answerObj = new AnswerModel();
		$res = $answerObj->save($row);

		if (! $res)
		{
			\Util::echoJson('信息保存失败，请稍后再试');
		}

		\Util::echoJson('保存成功'.$totalScore, true);
	}



}
