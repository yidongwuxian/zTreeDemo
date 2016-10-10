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
					<a href="#">群发消息</a>
				</li>
			</ul><!-- /.breadcrumb -->

		</div>
		<style type="text/css">
			form ul#optionsWrap li.col-sm-10 {list-style: none;margin-bottom: 15px;padding-left:0; }
			form ul#optionsWrap li input{margin-right: 5%;}
			form .col-sm-10 .chosen-container{width: 46% !important;}
			form .col-sm-10 ul .chosen-container, #users, form .tags{width: 56% !important;}
			form .col-sm-10 ul.chosen-choices{border: 1px solid #d5d5d5;border-radius: 0 !important;box-shadow: none !important;color: #858585;font-family: inherit;font-size: 14px;padding: 5px 4px 6px; transition-duration: 0.1s;}
			form .tabbable.col-xs-10.col-sm-5{width: 62%;padding-left: 0;height: auto; min-height: 280px;}
			form .tabbable.col-xs-10.col-sm-5 .tab-content{border: 0;padding-left: 0;}
			form .tabbable.col-xs-10.col-sm-5 .tab-content input, form .tabbable.col-xs-10.col-sm-5 .tab-content textarea{width:77%;}
			form .banner-upload{width: 62px !important;}
		</style>
		<div class="page-content">
			<div class="page-header">
                <h1>群发消息</h1>
        	</div>
        	<div class="row">
        		<div class="col-xs-12">
					<form role="form" class="form-horizontal" novalidate="novalidate">
						<div class="form-group">
							<label for="agentId" class="col-sm-2 control-label no-padding-right"> 应用 </label>
							<div class="col-sm-10">
								<div class="clearfix">
									<select id="agentId" class="chosen-select tag-input-style col-xs-10 col-sm-5" name="agentId" multiple="multiple" data-placeholder="请选择应用">
										<?php foreach ($appList as $vo) : ?>
										<option value="<?php echo $vo['agent_id']; ?>"><?php echo $vo['app_custom_name']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="optionsWrap" class="col-sm-2 control-label no-padding-right"> 发送给 </label>
							<div class="controls col-sm-10">
								<div class="clearfix">
									<ul style="margin-left: 0;" class="recent-posts" id="optionsWrap">
										<li class="col-sm-10">
											<div class="clearfix">
												<select id="depart" class="chosen-select tag-input-style col-xs-10 col-sm-5" name="depart" multiple="multiple" data-placeholder="部门">
													<?php echo $departList; ?>
												</select>													
											</div>
										</li>
										<li class="col-sm-10">
											<div class="clearfix">
												<select id="tag" class="chosen-select tag-input-style col-xs-10 col-sm-5" name="tag" multiple="multiple" data-placeholder="标签">
													<?php foreach ($tagList as $vo) : ?>
													<option value="<?php echo $vo['id']; ?>" <?php if(isset($info['lib_id']) && in_array($vo['id'], $info['partake']['tag'])) echo "selected"; ?>><?php echo $vo['title']; ?></option>
													<?php endforeach; ?>
												</select>													
											</div>
										</li>
										<li class="col-sm-10">
											<div class="clearfix">
												<input  name="users" id="users" type="text" class="col-xs-10 col-sm-5" placeholder="成员" readonly/>										
											</div>
											<button class="btn btn-sm" type="button" value="选择成员" id="selectUser">
												选择成员
											</button>
										</li>
									</ul>
								</div>
							</div>
						</div> 
						<div class="form-group">
							<label for="optionsWrap" class="col-sm-2 control-label no-padding-right"> 消息内容 </label>
							<div class="controls col-sm-10">
								<div class="clearfix">
									<div class="tabbable col-xs-10 col-sm-5">
										<ul id="myTab" class="nav nav-tabs">
											<li class="active">
												<a href="#text" data-toggle="tab" aria-expanded="false">
													<i class="green ace-icon fa fa-font bigger-120"></i>
													 文字 
												</a>
											</li>
											<li class="">
												<a href="#news" data-toggle="tab" aria-expanded="false">
													<i class="green ace-icon fa fa-eye bigger-120"></i>
													 图文
												</a>
											</li>
											<li class="">
												<a href="#image" data-toggle="tab" aria-expanded="false">
													<i class="green ace-icon fa fa-photo bigger-120"></i>
													 图片 
												</a>
											</li>
											<li class="">
												<a href="#file" data-toggle="tab" aria-expanded="false">
													<i class="green ace-icon fa fa-file bigger-120"></i>
												 	文件 
												</a>
											</li>
											<li class="">
												<a href="#video" data-toggle="tab" aria-expanded="false">
													<i class="green ace-icon fa fa-film bigger-120"></i>
												 	视频 
												</a>
											</li>
											<li class="">
												<a href="#voice" data-toggle="tab" aria-expanded="false">
													<i class="green ace-icon fa fa-volume-up bigger-120"></i>
												 	语音 
												</a>
											</li>
										</ul>

										<div class="tab-content">
											<div class="tab-pane fade active in" id="text">
												<textarea placeholder="消息内容(最多600个字符)" maxlength="600" rows="8" name="text-content" id="text-content" class="form-control"></textarea>
											</div>

											<div class="tab-pane fade" id="news">
												<div class="form-group">
													<label for="news-title" class="col-sm-2 control-label no-padding-right"> 标题 </label>
													<div class="col-sm-10">
														<input type="text" maxlength="64" class="default" placeholder="标题" name="news-title" id="news-title">
													</div>
												</div>
												<div class="form-group">
													<label for="banner-img" class="col-sm-2 control-label no-padding-right"> 封面图片 </label>
													<div class="col-sm-10">
														<img style="width:180px;height:100px" alt="推荐尺寸900*500" id="news-banner-img" src="http://placehold.it/900x500" /><br/>
														<input type="hidden" id="news-banner-path" name="news-banner-path" value="">		
														<input type="file" id="news-banner" name="news-banner" class="banner-upload"  />
														<a href="javascript:;" data="news-banner" class="hover hidden deleteImage">删除重新上传</a>	
													</div>
												</div>
												<div class="form-group">
													<label for="author" class="col-sm-2 control-label no-padding-right"> 作者(选填) </label>
													<div class="col-sm-10">
														<input type="text" maxlength="8" class="default" placeholder="内容摘要(最多8个字符)" name="news-author" id="news-author">
													</div>
												</div>
												<div class="form-group">
													<label for="url" class="col-sm-2 control-label no-padding-right"> 原文链接(选填) </label>
													<div class="col-sm-10">
														<input type="text" class="default" placeholder="原文链接(选填)" name="news-url" id="news-url">
													</div>
												</div>
												<div class="form-group">
													<label for="summary" class="col-sm-2 control-label no-padding-right"> 摘要(选填) </label>
													<div class="col-sm-10">
														<textarea placeholder="内容摘要(最多120个字符)" maxlength="120" rows="4" name="news-desc" id="news-desc"></textarea>
													</div>
												</div>
												<div class="form-group">
													<label for="title" class="col-sm-2 control-label no-padding-right"> 正文 </label>
													<div class="col-sm-10">
														<div class="wysiwyg-editor" id="editor1" name="news-content"></div>
													</div>
												</div>

											</div>

											<div class="tab-pane fade" id="image">
												<img style="width:360px;height:200px" alt="推荐尺寸900*500" id="image-banner-img" src="http://placehold.it/900x500" /><br/>
												<input type="hidden" id="image-banner-path" name="image-banner-path" value="">		
												<input type="file" id="image-banner" name="image-banner" class="banner-upload"  />
												<a href="javascript:;" data="image-banner" class="hover hidden deleteImage">删除重新上传</a>	
											</div>

											<div class="tab-pane fade" id="file">
												<img style="width:80px;height:80px" id="file-banner-img" src="/static/demo/filetype/file_psd.png" /><br/>
												<input type="hidden" id="file-banner-path" name="file-banner-path" value="">		
												<input type="file" id="file-banner" name="file-banner" class="banner-upload"  />
												<a href="javascript:;" data="file-banner" class="hover hidden deleteFile">删除重新上传</a>
												<br/><br/>
												只能上传txt、pdf、xml、zip、doc、ppt、xls、docx、pptx、xlsx格式！
											</div>


											<div class="tab-pane fade" id="video">
												<div class="form-group">
													<label for="banner-img" class="col-sm-2 control-label no-padding-right"> 视频 </label>
													<div class="col-sm-10">
														<img style="width:80px;height:80px" id="video-banner-img" src="/static/demo/filetype/file_psd.png" /><br/>
														<input type="hidden" id="video-banner-path" name="video-banner-path" value="">		
														<input type="file" id="video-banner" name="video-banner" class="banner-upload"  />
														<a href="javascript:;" data="video-banner" class="hover hidden deleteFile">删除重新上传</a>
														<br/>
														大小：不超过20M； 格式：rm、rmvb、wmv、avi、mpg、mpeg、mp4
													</div>
												</div>
												<div class="form-group">
													<label for="video-title" class="col-sm-2 control-label no-padding-right"> 标题(选填) </label>
													<div class="col-sm-10">
														<input type="text" maxlength="64" class="default" placeholder="标题" name="video-title" id="video-title">
													</div>
												</div>
												<div class="form-group">
													<label for="video-summary" class="col-sm-2 control-label no-padding-right"> 简介(选填) </label>
													<div class="col-sm-10">
														<textarea placeholder="简介(最多120个字符)" maxlength="120" rows="4" name="video-summary" id="video-summary"></textarea>
													</div>
												</div>
											</div>
											<div class="tab-pane fade" id="voice">
												<img style="width:80px;height:80px" id="voice-banner-img" src="/static/demo/filetype/file_psd.png" /><br/>
												<input type="hidden" id="voice-banner-path" name="voice-banner-path" value="">		
												<input type="file" id="voice-banner" name="voice-banner" class="banner-upload"  />
												<a href="javascript:;" data="voice-banner" class="hover hidden deleteFile">删除重新上传</a>
												<br/><br/>
												格式支持mp3、wma、wav、amr，文件大小不超过5M，语音时长不超过1分钟
											</div>
										</div>
									</div>
								</div>
							</div>
						</div> 
						<input type="hidden" id="type" name="type" value="text"/>
						<input type="hidden" id="userIds" name="userIds" value=""/>
						<div class="form-group">
							<label class="col-sm-3 control-label no-padding-right">  </label>
							<div class="col-md-9">
								<a id="submit" class="btn btn-info"><i class="ace-icon fa fa-check bigger-110"></i>发送</a>	
								<label style="margin-left:20px;">
									<input type="checkbox" class="ace" name="safe" id="safe">
									<span class="lbl"> 保密消息 </span>
								</label>		
							</div>
						</div>


					</form>
				</div>
        	</div>
		</div>


	</div>
</div>
<script src="/static/base/js/chosen.jquery.min.js"></script>
<script src="/static/base/js/bootstrap-wysiwyg.min.js"></script>
<script src="/static/base/js/jquery.hotkeys.min.js"></script>
<script src="/static/base/js/ajaxfileupload.js"></script>
<script src="/static/base/js/choosePerson.js"></script>
<script src="/static/base/js/bootstrap-tag.min.js"></script>
{include file="layout/tree" /}
{include file="layout/datatable" /}
<script type="text/javascript">
jQuery(function($){
	// ==========init multiple select chosen===============
	if(!ace.vars['touch']) {
		$('.chosen-select').chosen({allow_single_deselect:true}); 
		//resize the chosen on window resize

		$(window)
		.off('resize.chosen')
		.on('resize.chosen', function() {
			$('.chosen-select').each(function() {
				 var $this = $(this);
				 $this.next().css({'width': $this.parent().width()});
			})
		}).trigger('resize.chosen');
		//resize chosen on sidebar collapse/expand
		$(document).on('settings.ace.chosen', function(e, event_name, event_val) {
			if(event_name != 'sidebar_collapsed') return;
			$('.chosen-select').each(function() {
				 var $this = $(this);
				 $this.next().css({'width': $this.parent().width()});
			})
		});
	}
	// ==========multiple select chosen end===============
	// ==========EDITOR START===============
	//but we want to change a few buttons colors for the third style
	function showErrorAlert (reason, detail) {
		var msg='';
		if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
		else {
			//console.log("error uploading file", reason, detail);
		}
		$('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 
		 '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
	}
	$('#editor1').ace_wysiwyg({
		toolbar:
		[
			'font',
			null,
			'fontSize',
			null,
			{name:'bold', className:'btn-info'},
			{name:'italic', className:'btn-info'},
			{name:'strikethrough', className:'btn-info'},
			{name:'underline', className:'btn-info'},
			null,
			{name:'insertunorderedlist', className:'btn-success'},
			{name:'insertorderedlist', className:'btn-success'},
			{name:'outdent', className:'btn-purple'},
			{name:'indent', className:'btn-purple'},
			null,
			{name:'justifyleft', className:'btn-primary'},
			{name:'justifycenter', className:'btn-primary'},
			{name:'justifyright', className:'btn-primary'},
			{name:'justifyfull', className:'btn-inverse'},
			null,
			{name:'createLink', className:'btn-pink'},
			{name:'unlink', className:'btn-pink'},
			null,
			{name:'insertImage', className:'btn-success'},
			null,
			'foreColor',
			null,
			{name:'undo', className:'btn-grey'},
			{name:'redo', className:'btn-grey'}
		],
		'wysiwyg': {
			fileUploadError: showErrorAlert
		}
	}).prev().addClass('wysiwyg-style2');
	//RESIZE IMAGE
	//Add Image Resize Functionality to Chrome and Safari
	//webkit browsers don't have image resize functionality when content is editable
	//so let's add something using jQuery UI resizable
	//another option would be opening a dialog for user to enter dimensions.
	if ( typeof jQuery.ui !== 'undefined' && ace.vars['webkit'] ) {
		
		var lastResizableImg = null;
		function destroyResizable() {
			if(lastResizableImg == null) return;
			lastResizableImg.resizable( "destroy" );
			lastResizableImg.removeData('resizable');
			lastResizableImg = null;
		}

		var enableImageResize = function() {
			$('.wysiwyg-editor')
			.on('mousedown', function(e) {
				var target = $(e.target);
				if( e.target instanceof HTMLImageElement ) {
					if( !target.data('resizable') ) {
						target.resizable({
							aspectRatio: e.target.width / e.target.height,
						});
						target.data('resizable', true);
						
						if( lastResizableImg != null ) {
							//disable previous resizable image
							lastResizableImg.resizable( "destroy" );
							lastResizableImg.removeData('resizable');
						}
						lastResizableImg = target;
					}
				}
			})
			.on('click', function(e) {
				if( lastResizableImg != null && !(e.target instanceof HTMLImageElement) ) {
					destroyResizable();
				}
			})
			.on('keydown', function() {
				destroyResizable();
			});
	    }

		enableImageResize();
	}
	// ==========EDITOR END===============

	$("#myTab li").on('click', function(){
		var type = $(this).find('a').attr('href').split("#");
		$("#type").val(type[type.length - 1]);
	});
	// 图片上传
	$(".banner-upload").on('change', function(){
		var fileId  = $(this).prop('id');
		var isImage = $(this).siblings('a').attr('class').indexOf('deleteImage');
		var url =  isImage > -1 ? '/home/upload/image/fid/'+fileId : '/home/upload/file/fid/'+fileId+"/type/"+$("#type").val();
		var imgsrc 	= '', path = '';
		$.ajaxFileUpload({
		   url: url,
           secureuri: false,
           fileElementId: fileId,
           dataType: 'json',// 上传完成后, 返回json, text
           success: function(res) { // 上传之后回调
               if(res.result == 1){
               	   if (isImage > -1){
               	   		imgsrc = res.extension;
               	   		path  = res.extension;
               	   } else {
               	   		imgsrc = res.extension.show;
               	   		path  = res.extension.path;
               	   } 
                   $("#" + fileId + "-img").attr('src', imgsrc);
                   $("#" + fileId + "-path").val(path);
                   $('#' + fileId).addClass('hidden').siblings('a').removeClass('hidden');
               }else{
                   bootbox.alert(res.message);
               }
           }
		});
	});
	// 删除图片
	$("a.deleteImage, a.deleteFile").on('click', function(){
		var  defaultImg = $(this).attr('class').indexOf('deleteImage') > -1 ? 'http://placehold.it/900x500' : '/static/demo/filetype/file_psd.png';
		var fileId = $(this).attr('data');
		var path = $("#" + fileId + "-path").val();
		if (path == '') return false;

		$.ajax({
            type: "POST",
            url : "/home/upload/deleteimage",
            data : {path:path},
            dataType: "json",
            success : function(res){
                if(res.result == 1){
                	$("#" + fileId + "-img").attr('src', defaultImg);
                    $("#" + fileId + "-path").val('');
                    $('#' + fileId).removeClass('hidden').siblings('a').addClass('hidden');
                }else{
                    bootbox.alert(res.message);
                    return false;
                }
            }
        });
	});

	// 触发 消息发送
	$("#submit").on('click', function(){
		var data = {}, activeId = '', activeInfo = [];
		data.agentId = $("#agentId").val();
		data.depart  = $("#depart").val();
		data.tag 	 = $("#tag").val();
		data.users 	 = $("#userIds").val();
		data.userName= $("#users").val();
		data.type 	 = $('#type').val();
		data.safe	 = $("#safe")[0].checked ? 1 : 0;
 		activeInfo 	 =  $("#myTab li.active > a").prop('href').split("#");
		if (data.type != activeInfo[activeInfo.length - 1]){
			bootbox.alert('请求参数错误', function(){
				window.location.reload();
			});
		}
		if ($.isEmptyObject(data.agentId)){
			bootbox.alert('请选择应用');
			return false;
		}
		if ($.isEmptyObject(data.depart) && $.isEmptyObject(data.tag) && $.isEmptyObject(data.users)){
			bootbox.alert('请选择消息接收人');
			return false;
		}

		if (data.type == 'text') {
			data.content = $("#text-content").val();
			if ($.isEmptyObject(data.content)){
				bootbox.alert('请输入消息内容');
				return false;
			}
		} else if (data.type == 'news'){
			data.title 	  = $("#news-title").val();
			data.filePath = $("#news-banner-path").val();
			data.author   =	$("#news-author").val();
			data.url 	  =	$("#news-url").val();
			data.desc 	  =	$("#news-desc").val();
			data.content  = $("#editor1").html();
			if ($.isEmptyObject(data.filePath)){
				bootbox.alert('请选择封面图片');
				return false;
			}
			if ($.isEmptyObject(data.title)){
				bootbox.alert('请输入消息标题');
				return false;
			}
			if ($.isEmptyObject(data.content)){
				bootbox.alert('请输入消息内容');
				return false;
			}
		} else if (data.type == 'video'){
			data.filePath = $("#video-banner-path").val();
			data.title    = $("#video-title").val();
			data.content  = $("#video-summary").val();
			if ($.isEmptyObject(data.filePath)){
				bootbox.alert('请选择视频文件');
				return false;
			}
		} else {
			data.filePath = $("#"+data.type+"-banner-path").val();
			if ($.isEmptyObject(data.filePath)){
				bootbox.alert('请选择消息文件');
				return false;
			}
		}
		// console.log(data);
		$.ajax({
            type: "POST",
            url : "/home/message/send",
            data : data,
            dataType: "json",
            success : function(res){
                if(res.result == 1){
                	bootbox.alert('消息发送成功', function(){
                		window.location.reload();
                	});
                }else{
                    bootbox.alert(res.message);
                    return false;
                }
            }
        });

	});
	// ====================表单提交结束===================
	
    var _send = new choosePerson('#selectUser', {
        chooseType: 'multi',
        onsubmit: function(values,type){
        	var beforeUsers = $("#userIds").val() ? JSON.parse($("#userIds").val()) : '';
        	values = $.merge(values, beforeUsers);
        	values = $.unique(values);
            $("#userIds").val(JSON.stringify(values));
            userTagStyle(values);
            // console.log(values); 
        }
    });
    // ======TAG input style=======
    function userTagStyle(values){
	    var tag_input = $('#users');
		try{
			tag_input.tag({
				placeholder:tag_input.attr('placeholder'),
				source: ace.vars['US_STATES'],
			 })
			$.each(values, function( i , n ){
				$('#users').data('tag').add(n.name);
            });
		}catch(e) {
			tag_input.after('<textarea id="'+tag_input.attr('id')+'" name="'+tag_input.attr('name')+'" rows="3">'+tag_input.val()+'</textarea>').remove();
		}
		$('#users').siblings('input').addClass('hidden');
	}

})


</script>

