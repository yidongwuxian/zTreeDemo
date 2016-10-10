<html lang="ch">
<head>
    <meta charset="UTF-8">
    <title>选择企业微信号</title>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta content="IE=Edge" http-equiv="X-UA-Compatible">
    <meta content="chrome=1" http-equiv="X-UA-Compatible">
    <meta content="webkit" name="renderer">
    <link href="/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="/static/base/css/select-qylist.css" />
    <style>
        .ui-page-width{text-align:center}
        .aaa {
            background: #394557 url("/static/base/css/images/meteorshower2.jpg") repeat scroll 0 0;
            height:auto;
        }
        .mod-case__wrap {
            height: 200px;
            margin: 150px auto;
            overflow: hidden;
            width: 940px;
        }
        .mod-case__list {
            height: 200px;
        }
        .mod-index__block {
            border-bottom: 0px solid #e9ebee;
            padding: 60px 0;
        }
    </style>
</head>
<body class="aaa">
<div class="mod-index">
    <div class="mod-index__block mod-index__block_auto-height">
        <div class="ui-page-width">
            <h3 class="mod-index__subtitle" style="color: #FFFFFF;margin: 50px 0;">请选择企业微信号</h3>
            <div class="mod-case">

                <div class="mod-case">
                    <div class="mod-case__wrap">
                        <div style="width: <?php echo ($listPage+1)*960 ;?>px; margin-left: 0px;" class="mod-case__list js_case_imgs_list_content">
                            <ul style="width:<?php echo $listPage*960 ;?>px;float:left" class="mod-case__list js_case_imgs_list">
                                <?php if( ! empty($list)): ?>
                                    <?php foreach($list as $k=>$qychat): ?>
                                        <a href="/Home/login/selectQy/qyId/<?php echo $qychat->id; ?>">
                                        <li data-case-id="<?php echo $k+1; ?>" class="mod-case__item">
                                            <img alt="" src="https://res.wx.qq.com/mmocbiz/zh_CN/tmt/home/dist/img/mod-case/banner1_bb303fa2.jpg" class="mod-case__item-image">
                                            <img src="https://res.wx.qq.com/mmocbiz/zh_CN/tmt/home/dist/img/mod-case/banner1-blur_d180b345.jpg" class="mod-case__item-image-cover">
                                            <i class="mod-case__icon mod-case__icon-case10"></i><p class="mod-case__item-text"><?php echo $qychat->name; ?></p>
                                        </li>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                            <ul style="width:960px;float:left" class="mod-case__list">
                                <?php if( ! empty($list)): ?>
                                    <?php foreach($list as $k2=>$qychat2): ?>
                                        <?php if( $k2>2 )break; ?>
                                        <li data-case-id="<?php echo $k2+1; ?>" class="mod-case__item"><img alt="" src="https://res.wx.qq.com/mmocbiz/zh_CN/tmt/home/dist/img/mod-case/banner1_bb303fa2.jpg" class="mod-case__item-image"> <img src="https://res.wx.qq.com/mmocbiz/zh_CN/tmt/home/dist/img/mod-case/banner1-blur_d180b345.jpg" class="mod-case__item-image-cover"> <i class="mod-case__icon mod-case__icon-case1"></i><p class="mod-case__item-text"><?php echo $qychat2->name; ?></p>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <a title="上一页" class="mod-case__arrow-left" href="javascript:;"></a>
                        <a title="下一页" class="mod-case__arrow-right" href="javascript:;"></a>
                        <div class="mod-case__trigger-wrp" style="display: none;">
                            <ul class="mod-case__trigger js_case_trigger">
                                <?php for($i=1;$i<=$listPage;++$i): ?>
                                    <?php if($i==1): ?>
                                        <li data-index="1" class="selected"></li>
                                    <?php else : ?>
                                        <li data-index="<?php echo $i; ?>" class=""></li>
                                    <?php endif; ?>
                                <?php endfor;?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="/static/base/js/jquery.2.1.1.min.js"></script>
<script type="text/javascript">
    (function(){
        // 轮播图处理
        var case_index = 1;
        var case_length = $('.mod-case__trigger li').length;

        function preCase(){
            if(case_index > 1){
                $('.js_case_imgs_list_content').animate({'margin-left':'+=960px'});
                --case_index;
            }
            // 在第一屏点击上一页按钮
            else{
                $('.js_case_imgs_list_content').css({'margin-left':'-<?php echo $listPage*960 ;?>px'});
                $('.js_case_imgs_list_content').animate({'margin-left':'+=960px'});
                case_index = case_length;
            }
            $('.mod-case__trigger li').eq(case_index-1).addClass('selected').siblings().removeClass('selected');

        }
        function nextCase(){
            if(case_index < case_length){
                $('.js_case_imgs_list_content').animate({'margin-left':'-=960px'});
                ++case_index;
            }
            // 在最后一屏点下一页按钮
            else{
                $('.js_case_imgs_list_content').animate({'margin-left':'-=960px'},function(){
                    $('.js_case_imgs_list_content').css({'margin-left':'0px'});
                });
                case_index = 1;
            }
            $('.mod-case__trigger li').eq(case_index-1).addClass('selected').siblings().removeClass('selected');

        }


        $('.mod-case__arrow-left').click(preCase);
        $('.mod-case__arrow-right').click(nextCase);
        // 窄屏处理
        $(window).resize(function(){
            if($(window).width()<1100){
                $('body').addClass('screen-low');
            }
            else{
                $('body').removeClass('screen-low');
            }
        });
        $(window).resize();

    })()</script>
</body>
</html>