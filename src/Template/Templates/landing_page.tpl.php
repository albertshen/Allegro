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
    <script src="http://cdn.loccitane.samesamechina.com/src/dist/js/all_home.min.js?v=2.1"></script>
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
            <div class="icon-loading">
                <img src="http://cdn.loccitane.samesamechina.com/src/dist/images/icon-loading.png" alt=""/>
            </div>
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
            <div class="progress-percent"></div>
        </div>
    </div>
    <!-- 中间层 公用部分End-->
    <div class="container">
        <!-- 热气球游戏-->
        <div class="img-collection">
            <img id="rimg" src="http://cdn.loccitane.samesamechina.com/src/dist/images/game-car.png" alt=""/>
            <img id="bimg" src="http://cdn.loccitane.samesamechina.com/src/dist/images/game-balloon.png" alt=""/>
            <img id="seed-1" src="http://cdn.loccitane.samesamechina.com/src/dist/images/seed-1.png" alt=""/>
            <img id="seed-2" src="http://cdn.loccitane.samesamechina.com/src/dist/images/seed-2.png" alt=""/>
            <img id="seed-3" src="http://cdn.loccitane.samesamechina.com/src/dist/images/seed-3.png" alt=""/>
            <img id="seed-4" src="http://cdn.loccitane.samesamechina.com/src/dist/images/seed-4.png" alt=""/>
        </div>
        <!-- 活动说明页面-->
        <div class="pin" id="pin-activity-intro">
            <div class="v-content">
                <div class="pa-1">
                    <img src="http://cdn.loccitane.samesamechina.com/src/dist/images/p3-1.png" alt=""/>
                </div>
                <div class="btn-yellow pa-activity-link">
                    活动说明
                </div>
                <div class="btn-yellow btn-gogame">参与游戏赢取好礼</div>
                <div class="pa-logo">
                    <img src="http://cdn.loccitane.samesamechina.com/src/dist/images/p4-logo.png" alt=""/>
                </div>
            </div>
        </div>
        <!-- 游戏页面-->
        <div class="pin pin-1" id="pin-game">
            <audio src="http://cdn.loccitane.samesamechina.com/src/dist/audio/333.wav" id="common-audio"></audio>
            <audio src="http://cdn.loccitane.samesamechina.com/src/dist/audio/confirm.m4a" id="confirm-audio"></audio>
            <canvas id="game-canvas"></canvas>
            <!--<div class="count-down">10s</div>-->
            <div class="handle">
                <div class="left-bar"></div>
                <div class="right-bar"></div>
            </div>
            <div class="countdown">00:15</div>
            <ul class="seed-lists">
                <li class="item item-1">
                    <span class="seed"></span>
                </li>
                <li class="item item-2">
                    <span class="seed"></span>
                </li>
                <li class="item item-3">
                    <span class="seed"></span>
                </li>
                <li class="item item-4">
                    <span class="seed"></span>
                </li>
            </ul>
            <div class="game-cloud">
                <img src="http://cdn.loccitane.samesamechina.com/src/dist/images/game-cloud.png" alt=""/>
            </div>
            <div class="popup" id="popup-game-result">
                <div class="inner">
                    <div class="p4-logo">
                        <img src="http://cdn.loccitane.samesamechina.com/src/dist/images/p4-logo.png" alt=""/>
                    </div>
                    <div class="result-text result-text-success">
                        <h3>恭喜您<br>成功装满欧舒丹小卡车</h3>
                        <p class="des">获取来自普罗旺斯的精美礼品<br>
                            请于2018年10月13-14日前往活动现场领取<br>
                            活动期间每人限领一份
                        </p>
                    </div>
                    <div class="result-text result-text-fail">
                        <h3>谢谢参与!</h3>
                        <p class="des">重新挑战采集普罗旺斯新鲜原料</p>
                    </div>
                    <div class="buttons">
                        <div class="btn-yellow btn-play-again">再玩一次</div>
                        <div class="btn-yellow btn-go-france">前往普罗旺斯</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pin" id="pin-qrcode">
            <div class="p4-logo">
                <img src="http://cdn.loccitane.samesamechina.com/src/dist/images/p4-logo.png" alt=""/>
            </div>
            <div class="des-1">
                欧舒丹诚挚邀请您参加<br>
                于10月13-14日<br>
                在普罗旺斯——上海站的探索之旅
            </div>
            <div class="qrcode">
                <img src="http://cdn.loccitane.samesamechina.com/src/dist/images/prod_transparent.png" alt=""/>
            </div>
            <div class="des-2">
                请识别二维码预约时间<br>
                预约确认于下页提示
            </div>
        </div>

        <div class="pin" id="pin-reservation">
            <div class="r-logo">
                <img src="http://cdn.loccitane.samesamechina.com/src/dist/images/p4-logo.png" alt=""/>
            </div>
            <form id="form-reservation">
                <p class="title">
                    预 约<br>
                    普罗旺斯欧舒丹探索之旅
                </p>
                <div class="form-information">
                    <div class="input-box input-box-date clearfix">
                        <label for="input-date">日期</label>
                        <select name="title" id="input-date">
                            <option value="先生">10月13日</option>
                            <option value="女士">10月14日</option>
                        </select>
                    </div>
                    <div class="input-box input-box-time">
                        <label for="input-time">场次</label>
                        <select name="title" id="input-time">
                            <option value="11:00-13:00">11:00-13:00</option>
                            <option value="13:00-15:00">13:00-15:00</option>
                            <option value="15:00-17:00">15:00-17:00</option>
                            <option value="17:00-19:00">17:00-19:00</option>
                        </select>
                    </div>
                </div>
                <div class="btn btn-submit">一键预约</div>
            </form>
        </div>

    </div>
</div>
</body>
</html>