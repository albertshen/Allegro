<!DOCTYPE html>
<html>
<head lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>欧舒丹普罗旺斯</title>
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="x5-fullscreen" content="true">
    <meta name="full-screen" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <!-- inject:css -->
    <link rel="stylesheet" type="text/css" href="http://cdn.loccitane.samesamechina.com/src/dist/css/style.css?v=2.0"/>
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?6e3140f28810838b7df46da22a5d3189";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
    <!-- endinject -->
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script src="/api/wechat/jssdk"></script>
    <script src="http://cdn.loccitane.samesamechina.com/src/dist/js/all_invitation.min.js?v=2.0"></script>
</head>
<body class="page-home">
<div id="orientLayer" class="mod-orient-layer">
    <div class="mod-orient-layer__content">
        <i class="icon mod-orient-layer__icon-orient"></i>
        <div class="mod-orient-layer__desc">请在解锁模式下使用竖屏浏览</div>
    </div>
</div>
<!--main content-->
<!-- 已关注 -->
<div class="wrapper animate fade">
    <div class="preload">
        <div class="v-content">

        </div>
    </div>
    <!-- 中间层 公用部分End-->
    <div class="container">
        <!-- 预约成功邀请函页面-->
        <div class="pin pin-invitation current" id="pin-invitation">
            <div class="invitaion-header">
                <div class="h-img">
                    <img src="<?php print $data->headimgurl ;?>" alt=""/>
                </div>
                <div class="h-nickname"><?php print $data->nickname ;?></div>
            </div>
            <h3 class="title">
                预约成功！
            </h3>
            <p class="des">
                我们诚挚邀请您参加<br>
                <?php print $data->time ;?><br>
                在上海长宁来福士的普罗旺斯探索之旅
            </p>
            <div class="tips">（邀请函仅在预约时间段有效）</div>
            <form id="form-check">
                <input type="text" id="input-check-pwd" class="input-check-pwd" placeholder="核销密码填写"/>
                <div class="btn-yellow btn-check">核销</div>
                <div class="tips tips-2">（核销码由活动现场工作人员填写，擅自填写无效）</div>
            </form>
            <p class="des">
                地址：上海长宁来福士（长宁区长宁路1139号）<br>
                Tips：凭此邀请函可携带一位好友入场
            </p>
        </div>
        <div class="pin pin-invitation" id="pin-consume">
            <div class="r-logo">
                <img src="http://cdn.loccitane.samesamechina.com/src/dist/images/p4-logo.png" alt=""/>
            </div>
            <div class="v-content">
                <div class="des-2">
                    欢迎来到欧舒丹
                </div>
                <div class="r-slogan">
                    <img src="http://cdn.loccitane.samesamechina.com/src/dist/images/p8-t1.png" alt=""/>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>