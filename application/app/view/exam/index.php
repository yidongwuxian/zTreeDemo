<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<meta name="format-detection" content="telephone=no,address=no" />
		<meta http-equiv="Content-Type" content="application/xhtml+xml;charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache,must-revalidate">
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="expires" content="0">
		<title>考试</title>
		<link href="/static/mui/css/mui.min.css" rel="stylesheet"/>
		<link href="/static/app/css/common.css" rel="stylesheet"/>
	</head>
	<body>
		<div class="mui-content">
			<div id="segmentedControl" class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-primary">
				<a class="mui-control-item mui-active" href="#in" data="1">未答</a>
				<a class="mui-control-item" href="#end" data="2">已答</a>
			</div>

			<div class="vote_list alllist mui-control-content mui-active" id="in">
			     <ul class="mui-table-view mui-table-view-striped mui-table-view-condensed">
			    	<?php foreach ($inIds as $key => $value) : ?>
			        <li class="mui-table-view-cell">
			        	<div class="mui-table">
			        		<div class="mui-table-cell mui-col-xs-10">
					        	<a href="/app/exam/examinfo/id/<?php echo $value; ?>">
									<?php echo $examList[$value]['title']; ?>
								</a>
								<p>截止时间：<?php echo date('Y-m-d H:i:s',$examList[$value]['end_time']); ?></p>
							</div>
						</div>
			        </li>
			    	<?php endforeach; ?>
			    </ul>
			</div>

			<div class="vote_list alllist mui-control-content" id="end">
			    <ul class="mui-table-view mui-table-view-striped mui-table-view-condensed">
			    	<?php foreach ($endIds as $key => $value) : ?>
			        <li class="mui-table-view-cell">
			        	<div class="mui-table">
			        		<div class="mui-table-cell mui-col-xs-10">
					        	<a href="/app/exam/examinfo/id/<?php echo $value; ?>">
									<?php echo $examList[$value]['title']; ?>
								</a>
								<p>截止时间：<?php echo date('Y-m-d H:i:s',$examList[$value]['end_time']); ?></p>
							</div>
						</div>
			        </li>
			    	<?php endforeach; ?>
			    </ul>
			</div>

	    </div>
	    
	    <script src="/static/base/js/jquery.2.1.1.min.js"></script>
		<script src="/static/mui/js/mui.min.js"></script>

	</body>
</html>
