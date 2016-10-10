{__NOLAYOUT__}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
<style type="text/css">
body{background:#FFFFFF;text-align:center; font-family: Arial, Helvetica, sans-serif;
}
h1 img{visibility:hidden;width:200px;height:20px;}

#one {font-size:16px; width:800px;height:190px; margin:100px auto;position:relative;text-align:left;}
#one ul {margin:0px; padding:0px; list-style:none;}
#speak{width:300px;position:absolute;left:160px;top:66px;}
#speak .rcontent{font-size:24px;color:#000000;font-family:"黑体";}
#jump{bottom:15px;font-size: 18px;}
#face-err{width:100px;height:111px;margin:40px 0 0 18px;background:url(/static/base/img/xiao6.jpg) no-repeat -990px top;}
#face-suc{width:100px;height:111px;margin:40px 0 0 18px;background:url(/static/base/img/xiao6.jpg) no-repeat -1990px top;}

</style>
</head>

<body>
<div id="one">
    <?php if($code== 1): ?>
    <div id='face-suc'></div>
    <p class="success"><?php echo(strip_tags($msg));?></p>
    <?php else:?>
    <div id='face-err'></div>
    <p class="error"><?php echo(strip_tags($msg));?></p>
    <?php endif;?>

	<div id="speak">
		<b class="rtop">
			<b class="r1"></b>
			<b class="r2"></b>
			<b class="r3"></b>
			<b class="r4"></b>
		</b>
		<div class="rcontent">
			<?php echo(strip_tags($msg));?>
		</div>
		<b class="rbottom">
			<b class="r4"></b>
			<b class="r3"></b>
			<b class="r2"></b>
			<b class="r1"></b>
		</b>
	</div><!--/speak-->

	<p id="jump">
		页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
	</p>

    <script type="text/javascript">
        (function(){
            var wait = document.getElementById('wait'),
                href = document.getElementById('href').href;
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if(time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                };
            }, 1000);
        })();
    </script>

</div><!--/one-->

</body>
</html>
