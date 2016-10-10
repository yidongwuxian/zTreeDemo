<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>审核申请</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!--标准mui.css-->
    <link rel="stylesheet" href="/static/mui/css/mui.min.css">
    <style>
        h5 {
            margin: 5px 7px;
        }
        .mui-table h4,.mui-table h5,.mui-table .mui-h5,.mui-table .mui-h6,.mui-table p{
            margin-top: 0;
        }
        .mui-table h4{
            line-height: 21px;
            font-weight: 500;
        }

        .mui-table .oa-icon{
            position: absolute;
            right:0;
            bottom: 0;
        }
        .mui-table .oa-icon-star-filled{
            color:#f14e41;
        }
    </style>
</head>

<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">审核申请</h1>
</header>
<div id="pullrefresh" class="mui-content mui-scroll-wrapper">
    <div class="mui-scroll">
        <ul class="mui-table-view mui-table-view-striped mui-table-view-condensed">
            <?php if( ! empty($list)): ?>
                <?php foreach($list as $vo): ?>
                    <li class="mui-table-view-cell">
                        <a href="/app/car/replyapply/id/<?php echo $vo['id'] ;?>">
                            <div class="mui-table">
                                <div class="mui-table-cell mui-col-xs-10">
                                    <h4 class="mui-ellipsis"><?php echo date('Y-m-d', $vo['use_date']) ;?>-用车申请-<?php echo $vo['member_name'] ;?></h4>
                                    <p class="mui-h6 mui-ellipsis">人数：<?php echo $vo['person_num'] ;?>人，车辆数：<?php echo $vo['cars_num'] ;?>辆，状态：<?php echo $vo['reply_status'] ;?></p>
                                </div>
                                <div class="mui-table-cell mui-col-xs-2 mui-text-right">
                                    <span class="mui-h5">12:25</span>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>
</body>
</html>

<script src="/static/mui/js/mui.min.js"></script>
<script>
    mui.init({
        pullRefresh: {
            container: '#pullrefresh',
            up: {
                auto:false,
                contentrefresh: '正在加载...',
                callback: pullupRefresh
            }
        }
    });

    var count = 1;
    var page = 1;
    var totalPage = 1;
    /**
     * 上拉加载具体业务实现
     */
    function pullupRefresh() {
        ++page;
        setTimeout(function() {
            mui('#pullrefresh').pullRefresh().endPullupToRefresh((++count > totalPage)); //页面加载最大数 参数为true代表没有更多数据了。
            var table = document.body.querySelector('.mui-table-view');
            //li 加载的list样式参考  mark=1 为审核列表
            mui.getJSON('/app/car/ajaxdata/mark/1/?page=' + page , {depart_id:''}, function(json){
                if( json.statusCode == 200 ){
                    mui.each(json.data,function(i,item){
                        var li = document.createElement('li');
                        li.className = 'mui-table-view-cell';
                        li.innerHTML = '<a href="/app/car/replyapply/id/'+ item.id +'">' +
                        '<div class="mui-table">' +
                        '<div class="mui-table-cell mui-col-xs-10">' +
                        '<h4 class="mui-ellipsis">'+item.use_date+'-用车申请-'+item.member_name+'</h4>' +
                        '<h5>状态：'+item.status+'</h5>' +
                        '<p class="mui-h6 mui-ellipsis">人数：'+item.person_num+'人，车辆数：'+item.cars_num+'辆，状态：'+item.reply_status+'</p>' +
                        '</div>' +
                        '<div class="mui-table-cell mui-col-xs-2 mui-text-right">' +
                        '<span class="mui-h5">12:25</span>' +
                        '</div>' +
                        '</div>' +
                        '</a>';
                        table.appendChild(li);
                    });
                }
            });
        }, 1500);
    }

    //html a连接生效
    mui('#pullrefresh').on('tap','a',function(){document.location.href=this.href;});
</script>
