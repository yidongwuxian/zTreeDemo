<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>考试</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<meta content="yes" name="apple-mobile-web-app-capable">
		<meta content="black" name="apple-mobile-web-app-status-bar-style">
		<meta name="format-detection" content="telephone=no,address=no" />
		<meta http-equiv="Content-Type" content="application/xhtml+xml;charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache,must-revalidate">
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="expires" content="0">
		<link href="/static/mui/css/mui.min.css" rel="stylesheet"/>
		<link href="/static/mui/css/app.css" rel="stylesheet"/>
		<link href="/static/app/css/loading.css" rel="stylesheet"/>
		<style type="text/css">
		h5{line-height:26px;} 
		.mui-input-row label{line-height: 20px;}
		.mui-input-group .mui-input-row {height: auto;min-height: 40px;}
		.mui-checkbox input[type=checkbox], .mui-radio input[type=radio] {top:6px;}
		.mui-card.win100 label{width:100% !important; padding: 5px 15px;line-height: 22px;}
		</style>
	</head>
	<body>
		<header class="mui-bar mui-bar-nav">
			<!-- <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="/app/exam/index">&nbsp</a> -->
			<h1 class="mui-title"><?php  echo $examInfo['title']; ?></h1>
		</header>

		<div class="mui-content">

			<div class="mui-card win100">
				<div class="mui-input-row">
					<label>当期状态：<?php echo $examInfo['tag'] == 1 ? '已答题' : '未答题' ; ?></label>
				</div>
				<div class="mui-input-row">
					<label>总题数：<?php echo $examInfo['pro_num']; ?></label>
				</div>
				<div class="mui-input-row">
					<label>总 &nbsp; 分：<?php echo $examInfo['total']; ?> 分</label>
				</div>
				<div class="mui-input-row">
					<?php if($examInfo['tag'] == 1): ?>
					<label>得 &nbsp; 分：<span><?php echo $examInfo['totalScore']; ?> 分</span></label>
					<?php else: ?>
					<label>倒计时：<span id="time">0</span></label>
					<?php endif; ?>
				</div>
			</div>	

			<?php $answerInfo = isset($examInfo['answerInfo']) ? $examInfo['answerInfo'] : [];
				 foreach ($examInfo['questions'] as $key => $info): ?>
			<h5 class="mui-content-padded">
				<?php echo ($key + 1) . " " . $info['title']; ?>
				（<?php echo $info['type'] == 1 ? '单选': '多选'; ?> <?php echo $info['score']; ?>分）
				<?php $answer = isset($answerInfo[$info['id']]) ? $answerInfo[$info['id']] : [];?>
			</h5>
			<div class="mui-card">
				<div class="mui-input-group">
					<?php $options = json_decode($info['options'], true); foreach ($options as $opkey => $option) {?>
					<div class="mui-input-row <?php echo $info['type'] == 1 ? 'mui-radio': 'mui-checkbox'; ?> <?php if($examInfo['tag'] == 1){echo ' mui-disabled';} ?>">
						<label><?php echo $option['title']; ?></label>
						<input type="<?php echo $info['type'] == 1 ? 'radio': 'checkbox'; ?>" value="<?php echo $opkey + 1; ?>" <?php if($examInfo['tag'] == 1){echo 'disabled="disabled"';} ?> name="question-<?php echo ($info['id']); ?>" <?php if(in_array($opkey+1, $answer)){echo 'checked="checked"';} ?>>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php endforeach; ?>
			
			<div class="mui-content-padded">
				<?php if($examInfo['tag'] == 1): ?>
				<button class="mui-btn mui-btn-grey mui-btn-block" type="button">提交</button>
				<?php else: ?>
				<button class="mui-btn mui-btn-primary mui-btn-block" type="button" id="submit">提交</button>
				<?php endif; ?>
			</div>
	    </div>

	    <footer>&nbsp;</footer>

	    <script src="/static/base/js/jquery.2.1.1.min.js"></script>
		<script src="/static/mui/js/mui.min.js"></script>
		<script src="/static/app/js/loading.js?<?=time() ?>"></script>
		<script type="text/javascript">
		<?php if($examInfo['tag'] == 0): ?>
		var intDiff = parseInt(<?php echo $examInfo['limit_time'] * 60 ;?>);//倒计时总秒数量
		var start = <?php echo time();?>;
		function timer(intDiff){
			var timer = setInterval(function(){
				var hour=0, minute=0, second=0,html = '';
				if(intDiff > 0){
					hour   = Math.floor(intDiff / (60 * 60));
					minute = Math.floor(intDiff / 60) - (hour * 60);
					second = Math.floor(intDiff) - (hour * 60 * 60) - (minute * 60);
				}else{
					saveExam();
					clearInterval(timer);
				}
				if (minute <= 9) minute = '0' + minute;
				if (second <= 9) second = '0' + second;
				html += hour > 0 ? hour+' 时 ' : '';
				html += minute+' 分 '+second+' 秒'
				$('#time').html(html);
				intDiff--;
			}, 1000);
		} 
		timer(intDiff);

		mui('.mui-content').on('click', "#submit", function(){
        	saveExam(true);
        });

        function saveExam(tag = false) {
        	var data = {}, name = '', type = '', unanswer = 0;
        	$('.mui-content > div.mui-card').each(function(){
        		if ($(this).index() > 0) {
	        		name = $(this).find("input").attr('name');
	        		type = $(this).find("input").attr('type');
	        		if (type == 'radio'){
	        			var val = '';
	        			if ($(this).find('input[name='+name+']:checked').length > 0)
	        			{
	        				val = parseInt($(this).find('input[name='+name+']:checked').val());
	        			} else {
	        				unanswer += 1;
	        			}
	        		} else {
	        			var val = [];
	        			$(this).find('input[name='+name+']:checked').each(function(){
							val.push(parseInt($(this).val()));
						}); 
						if (val.length==0) {
							unanswer += 1;
						}
	        		}
	        		data[name] = val;
        		}
        	})
        	// console.log(data);
        	if (tag == true && unanswer > 0){
        		mui.confirm('您有'+unanswer+'题未作答，确定要提交么？', '提示', ['取消', '确认'], function(e){
					if (e.index == 1) {
	        			save({data:data, start:start});	
	        		}else{
	        			$(".mui-popup").css({'display':'none'}).siblings('.mui-popup-backdrop').css({'display':'none'})
	        		}
        		});
        	} else {
        		save({data:data, start:start});
        	}
        }

        function save(data){
	        showLoading();
	        mui.ajax("/app/exam/save/id/<?php echo $examInfo['id'] ;?>", {
				data:data,
				dataType:"json",
				type:"POST",         
				success:function(res){
					if(res.result == 1){
						mui.alert(res.message, function(){
							window.location.href="/app/exam/examinfo/id/" + <?php echo $examInfo['id'] ;?>;
						});						
					}else{
						mui.alert(res.message, '消息提示', '确定', function(){
							hideLoading();
							$(".mui-popup").css({'display':'none'}).siblings('.mui-popup-backdrop').css({'display':'none'})
							return false;
						})
					}
				},
				error:function(xhr,type,errorThrown){
					mui.alert('请求出错', '消息提示', '确定', function(){
						hideLoading();
						$(".mui-popup").css({'display':'none'}).siblings('.mui-popup-backdrop').css({'display':'none'})
						return false;
					})
				}
			});
    	}
        <?php endif; use think\Session; Session::set('examSatrt', time());  ?>
		</script>
	</body>
</html>
