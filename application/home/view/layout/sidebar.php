<div class="main-container" id="main-container">
    <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
    </script>

    <div id="sidebar" class="sidebar                  responsive">
        <script type="text/javascript">
            try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
        </script>

        <ul class="nav nav-list">

            <li class="<?php if($class == 'index_manage') echo 'active'; ?>">

                <a href="index.html">
                    <i class="menu-icon fa fa-tachometer"></i>
                    <span class="menu-text"> 控制台 </span>
                </a>

                <b class="arrow"></b>
            </li>

            <?php if( !empty($isSuper) ): ?>
            <li class="<?php if($class == 'qychat_manage') echo 'active'; ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-desktop"></i>
							<span class="menu-text">
								企业号管理
							</span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">

                    <li class="<?php if($subClass == 'qychat_list') echo 'active'; ?>">
                        <a href="<?php echo '/home/qychat'; ?>">
                            <i class="menu-icon fa fa-caret-right"></i>
                            企业号列表
                        </a>

                        <b class="arrow"></b>
                    </li>

                </ul>
            </li>

            <li class="<?php if($class == 'user_manage') echo 'active'; ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-list-alt"></i>
                    <span class="menu-text">企业用户管理</span>
                </a>
                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="<?php if($subClass == 'user_list') echo 'active'; ?>">
                        <a href="/home/user/index">
                            <i class="menu-icon fa fa-caret-right"></i>
                            用户列表
                        </a>
                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>

            <li class="<?php if($class == 'apps') echo 'active'; ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-list-alt"></i>
                    <span class="menu-text">应用中心</span>
                </a>
                <b class="arrow"></b>
                <ul class="submenu">
                    <li class="<?php if(isset($action) && (in_array($action, ['index', 'menulist', 'menuadd'])) )echo 'active'; ?>">
                        <a href="/home/apps/index/">
                            <i class="menu-icon fa fa-caret-right"></i>
                            应用列表
                        </a>
                    </li>
                    <li class="<?php if(isset($action) && $action == 'appadd') echo 'active'; ?>">
                        <a href="/home/apps/appadd">
                            <i class="menu-icon fa fa-caret-right"></i>
                            添加应用
                        </a>
                    </li>
                </ul>
            </li>

            <?php endif; ?>

            <li class="<?php if($class == 'contact') echo 'active'; ?>">
                <a href="#" class="dropdown-toggle">
                   <i class="menu-icon fa fa-users"></i>
					<span class="menu-text">
						通讯录
					</span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">

                    <li class="<?php if($subClass == 'contact') echo 'active'; ?>">
                        <a href="/home/contact">
                            <i class="menu-icon fa fa-caret-right"></i>
                            通讯录
                        </a>

                        <b class="arrow"></b>
                    </li>

                    <li class="<?php if($subClass == 'tag') echo 'active'; ?>">
                        <a href="/home/tag">
                            <i class="menu-icon fa fa-caret-right"></i>
                            标签
                        </a>

                        <b class="arrow"></b>
                    </li>

                </ul>
            </li>

            <li class="<?php if($class == 'app') echo 'active'; ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-list"></i>
                            <span class="menu-text">
                                应用中心
                            </span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="<?php if($subClass == 'qyapp') echo 'active'; ?>">
                        <a href="/home/qyapp/index">
                            <i class="menu-icon fa fa-caret-right"></i>
                            应用安装
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                    </li>
                    <li class="<?php if($subClass == 'exam') echo 'active'; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-caret-right"></i>
                            考试
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <ul class="submenu">
                            <li class="<?php if(isset($action) && $action == 'index') echo 'active'; ?>">
                                <a href="/home/exam/index/">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    试卷管理
                                </a>
                            </li>
                            <li class="<?php if(isset($action) && $action == 'lib') echo 'active'; ?>">
                                <a href="/home/exam/lib">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    题库管理
                                </a>
                            </li>
                            <li class="<?php if(isset($action) && $action == 'addexam') echo 'active'; ?>">
                                <a href="/home/exam/addexam/">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    新增试卷
                                </a>
                            </li>
                            <li class="<?php if(isset($action) && $action == 'addlib') echo 'active'; ?>">
                                <a href="/home/exam/addlib/">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    新增题库
                                </a>
                            </li>
                            <li class="<?php if(isset($action) && ($action == 'addproblem' || $action == 'getprobleminfo')) echo 'active'; ?>">
                                <a href="/home/exam/addproblem/">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    新增题目
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="<?php if($subClass == 'app_car') echo 'active'; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-caret-right"></i>
                            用车申请
                            <b class="arrow fa fa-angle-down"></b>
                        </a>

                        <b class="arrow"></b>

                        <ul class="submenu">
                            <li class="<?php if(!empty($botClass)){if($botClass == 'app_car_process') echo 'active';} ?>">
                                <a href="/home/car/process">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    流程管理
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <li class="<?php if(!empty($botClass)){if($botClass == 'app_car_car') echo 'active';} ?>">
                                <a href="/home/car/carlist">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    车辆管理
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <li class="<?php if(!empty($botClass)){if($botClass == 'app_car_apply') echo 'active';} ?>">
                                <a href="/home/car/apply">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    申请记录
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <li class="<?php if(!empty($botClass)){if($botClass == 'app_car_manage') echo 'active';} ?>">
                                <a href="/home/car/manage">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    配置人员
                                </a>
                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>
                    
                </ul>
            </li>
            <li class="">
                <a href="#">
                    <i class="menu-icon fa fa-twitter"></i>
                    <span class="menu-text"> 员工邀请 </span>
                </a>

                <b class="arrow"></b>
            </li>

        </ul><!-- /.nav-list -->

        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>

        <script type="text/javascript">
            try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
        </script>
    </div>