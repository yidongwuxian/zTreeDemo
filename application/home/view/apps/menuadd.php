<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">首页</a>
				</li>
				<li>
					<a href="#">应用中心</a>
				</li>
				<li>
					<a href="/home/apps/index">应用列表</a>
				</li>
				<li>
					<a href="#">添加菜单</a>
				</li>
			</ul>
		</div>

		<div class="page-content">						
			<div class="page-header">
				<h1>添加应用菜单</h1>
			</div>
			<div class="row">
				<div class="col-xs-12">
				<form class="form-horizontal" role="form" method="post" id="save">
					<input type="hidden" id="id" name="id" value="<?php echo isset($info['id']) ? $info['id'] : 0; ?>"/>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="app_name">应用名称</label>
						<div class="col-sm-10">
							<input type="text" value="<?php echo $appInfo['app_name']; ?>" readonly="readonly" class="col-xs-10 col-sm-5"/>
							<input type="hidden" id="appId" name="appId" value="<?php echo $appInfo['id']; ?>"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="app_controller">父菜单</label>
						<div class="col-sm-10">
							<select name="pid" class="col-xs-10 col-sm-5">
								<option value="0">无</option>
								<?php foreach ($menuList as $key => $value) { if(isset($info['id']) && $info['id'] == $value['id']) continue;   ?>
								<option value="<?php echo $value['id']; ?>" <?php if(isset($info['pid']) && $info['pid']==$value['id']){echo 'selected="selected"';} ?>><?php echo $value['name']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>				
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="name">菜单名称</label>
						<div class="col-sm-10">
							<input type="text" id="name" name="name" value="<?php echo isset($info['name']) ? $info['name'] : ''; ?>" placeholder="菜单名称" class="col-xs-10 col-sm-5" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="type">菜单类型</label>
						<div class="col-sm-10">
							<select name="type" class="col-xs-10 col-sm-5">
								<option value="click" <?php if(isset($info['type']) && $info['type']=='click'){ echo 'selected="selected"';} ?>>点击推事件</option>
								<option value="view" <?php if(isset($info['type']) && $info['type']=='view'){ echo 'selected="selected"';} ?>>跳转URL</option>
								<option value="scancode_push" <?php if(isset($info['type']) && $info['type']=='scancode_push'){ echo 'selected="selected"';} ?>>扫码推事件</option>
								<option value="scancode_waitmsg" <?php if(isset($info['type']) && $info['type']=='scancode_waitmsg'){ echo 'selected="selected"';} ?>>扫码带提示</option>
								<option value="pic_sysphoto" <?php if(isset($info['type']) && $info['type']=='pic_sysphoto'){ echo 'selected="selected"';} ?>>弹出系统拍照发图</option>
								<option value="pic_photo_or_album" <?php if(isset($info['type']) && $info['type']=='pic_photo_or_album'){ echo 'selected="selected"';} ?>>弹出拍照或相册发图</option>
								<option value="pic_weixin" <?php if(isset($info['type']) && $info['type']=='pic_weixin'){ echo 'selected="selected"';} ?>>弹出微信相册发图器</option>
								<option value="location_select" <?php if(isset($info['type']) && $info['type']=='location_select'){ echo 'selected="selected"';} ?>>弹出地理位置选择器</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="banner-img">菜单CODE</label>
						<div class="col-sm-10">
							<input type="text" id="code" name="code" value="<?php echo isset($info['code']) ? $info['code'] : ''; ?>" placeholder="菜单CODE" class="col-xs-10 col-sm-5" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="memo">菜单排序</label>
						<div class="col-sm-10">
							<input type="number" id="sort" name="sort" value="<?php echo isset($info['sort']) ? $info['sort'] : 0; ?>" placeholder="菜单排序：值越大越靠前" class="col-xs-10 col-sm-5" />
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="state">菜单级别</label>
						<div class="col-sm-10">
							<input type="number" id="level" name="level" value="<?php echo isset($info['level']) ? $info['level'] : 0; ?>" placeholder="菜单级别" class="col-xs-10 col-sm-5" />
						</div>
					</div>			
					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right">  </label>
						<div class="col-md-9">
							<a class="btn btn-info" id="submit"><i class="ace-icon fa fa-check bigger-110"></i>提交</a>
							&nbsp; &nbsp; &nbsp;	
							<button class="btn" type="reset">
								<i class="ace-icon fa fa-undo bigger-110"></i>
								<span>重置</span>
							</button>				
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>

	</div>
</div>
<script src="/static/base/js/ajaxfileupload.js"></script>

<script type="text/javascript">
	jQuery(function($) {
		// 保存
		$("#submit").on('click', function(){
			$.ajax({
                type: "POST",
                url : "/home/apps/menusave",
                data : $('#save').serialize(),
                dataType: "json",
                success : function(res){
                    if(res.result == '1'){
                        bootbox.alert(res.message,function(){
                            window.location.href = "/home/apps/menulist/id/<?php echo $appInfo['id']; ?>";
                        });
                    }else{
                        bootbox.alert(res.message);
                        return false;
                    }
                }
            });
		});
	});
</script>