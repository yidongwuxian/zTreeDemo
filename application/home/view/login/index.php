<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>微信企业号管理平台</title>

    <meta name="description" content="User login page" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="/static/base/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/static/base/font-awesome/4.2.0/css/font-awesome.min.css" />

    <!-- text fonts -->
    <link rel="stylesheet" href="/static/base/fonts/fonts.googleapis.com.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="/static/base/css/ace.min.css" />

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/static/base/css/ace-part2.min.css" />
    <![endif]-->
    <link rel="stylesheet" href="/static/base/css/ace-rtl.min.css" />

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/static/base/css/ace-ie.min.css" />
    <![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="/static/base/js/html5shiv.min.js"></script>
    <script src="/static/base/js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="login-layout blur-login">
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center">
                        <h1>
                            <i class="ace-icon fa fa-comments green"></i>
                            <span class="white" id="id-text2">企业号管理平台</span>
                        </h1>
                        <h4 class="light-blue" id="id-company-text">&copy; 东方金科</h4>
                    </div>

                    <div class="space-6"></div>

                    <div class="position-relative">
                        <div id="login-box" class="login-box visible widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header blue lighter bigger">
                                        <i class="ace-icon fa fa-coffee green"></i>
                                        管理员信息
                                    </h4>

                                    <div class="space-6"></div>
                                    <div class="alert alert-danger" style="display: none;">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>
                                        <strong>
                                            <i class="ace-icon fa fa-times"></i>
                                        </strong>
                                        <span id="error">
                                        </span>
                                        <br>
                                    </div>
                                    <div class="space-6"></div>

                                    <form id="loginform">
                                        <fieldset>
                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" placeholder="用户名" name="user_name" id="user_name"/>
															<i class="ace-icon fa fa-user"></i>
														</span>
                                            </label>

                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="密码" name="password" id="password"/>
															<i class="ace-icon fa fa-lock"></i>
														</span>
                                            </label>

                                            <div class="space"></div>

                                            <div class="clearfix">
                                                <!--<label class="inline">-->
                                                <!--<input type="checkbox" class="ace" />-->
                                                <!--<span class="lbl"> Remember Me</span>-->
                                                <!--</label>-->
                                                <button type="button" class="width-35 pull-right btn btn-sm btn-primary" id="tologin">
                                                    <i class="ace-icon fa fa-key"></i>
                                                    <span class="bigger-110">登录</span>
                                                </button>
                                            </div>

                                            <div class="space-4"></div>
                                        </fieldset>
                                    </form>

                                </div>
                            </div><!-- /.widget-body -->
                        </div>
                    </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.main-content -->
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script src="/static/base/js/jquery.2.1.1.min.js"></script>

<!-- <![endif]-->

<!--[if IE]>
<script src="/static/base/js/jquery.1.11.1.min.js"></script>
<![endif]-->

<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='/static/base/js/jquery.min.js'>"+"<"+"/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='assets/js/jquery1x.min.js'>"+"<"+"/script>");
</script>
<![endif]-->
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='/static/base/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>

<!-- inline scripts related to this page -->
<script type="text/javascript">
    $(function($) {
        $(document).on('click', '.toolbar a[data-target]', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            $('.widget-box.visible').removeClass('visible');//hide others
            $(target).addClass('visible');//show target
        });

        $('.close').on('click',function(){
            $('.alert').css('display','none');
        });

        //登录动作
        $('#tologin').bind('click',function(){
            var user_name = $('#user_name').val();
            var password = $('#password').val();
            if (user_name.length < 2 ){
                $('#error').html('用户名不能为空');
                $('.alert').css('display','block');
                return false;
            }
            if (password.length < 6 ){
                $('#error').html('密码至少6位');
                $('.alert').css('display','block');
                return false;
            }else{
                $('#error').html('');
                $('.alert').css('display','none');
            }
            $.ajax({
                type: 'post',
                url: "/Home/login/login",
                data: $("#loginform").serialize(),
                dataType: 'json',
                success: function (res) {
                    if(res.result == 1){
                        $('#tologin').html('正在登录...');
                        window.location.href = res.extension;
                    }else{
                        $('#error').html(res.message);
                        $('.alert').css('display','block');
                    }
                }
            });
        });

    });
</script>
</body>
</html>
