<?
?>
<!--<script>-->
    var _p = function (data) {
        if (window.console) window.console.info(data);
    };

    function trim(str) {
        str = str.replace(/^(\s|\u00A0)+/, '');
        for (var i = str.length - 1; i >= 0; i--) {
            if (/\S/.test(str.charAt(i))) {
                str = str.substring(0, i + 1);
                break;
            }
        }
        return str;
    }

    //前面的进程结束后  后一进程才可以开始
    function ajaxRequest(url, callback) {
        if (url === null || url.length === 0) {
            _p('ajaxRequest: url is null');
            return;
        }
        if (url.indexOf('?') > -1) {
            url += "&callback=?";
        }
        else {
            url += "?callback=?";
        }
        $.ajaxSettings.async = false;
        $.getJSON(
            url,
            function (data) {
                callback(data);
            }
        );
    }

    //不等前一进程结束  后一进程就会开始
    function ajaxRequest2(url, callback) {
        if (url === null || url.length === 0) {
            _p('ajaxRequest: url is null');
            return;
        }
        if (url.indexOf('?') > -1) {
            url += "&callback=?";
        }
        else {
            url += "?callback=?";
        }
        $.ajaxSettings.async = true;
        $.getJSON(
            url,
            function (data) {
                callback(data);
            }
        );
    }

    function addBookMark() {
        var url = arguments[0] ? arguments[0] : '';
        var message = arguments[1] ? arguments[1] : 'Haz clic CTRL+D para añadirlo a favorito.';//西班牙语 按ctrl+D收藏

        if (document.all) {
            try { //for IE
                window.external.addFavorite(url, 'Legend Online');
            } catch (e) {
                alert(message);
            }
        } else if (window.sidebar) {
            try {
                //Firefox
                window.sidebar.addPanel('Legend Online', url, "game");
            } catch (e) {
                alert(message);
            }
        } else {
            alert(message);
        }
    }

    //跳转至土语app
    function href_fbtr() {
        top.location.href = "http://apps.facebook.com/legend_tr/";
    }

    //跳转至葡语app
    function href_fbpt() {
        top.location.href = "http://apps.facebook.com/legend_pt/";
    }

    //跳转至波语app
    function href_fbpl() {
        top.location.href = "http://apps.facebook.com/legend_pl/";
    }

    //跳转至西语app
    function href_fbes() {
        top.location.href = "http://apps.facebook.com/legend_es/";
    }


    //跳转至德语app
    function href_fbde() {
        top.location.href = "http://apps.facebook.com/legend_de/";
    }

    //跳转至荷兰语app
    function href_fbnl() {
        top.location.href = "http://apps.facebook.com/legend_nl/";
    }

    //跳转至荷兰语app
    function href_fbsv() {
        top.location.href = "http://apps.facebook.com/legend_sv/";
    }

    //跳转至阿拉伯语语app
    function href_fbar() {
        top.location.href = "http://apps.facebook.com/legend_ar/";
    }

    //动态设置APP右上角的时钟
    function Estime() {
        localTime= getLocalTime();
        $(".time_num").html(localTime);

        app_display_time += 1;
        setTimeout("Estime()", 1000);
    }

    function getLocalTime() {
        var d = new Date();
        var localOffset = d.getTimezoneOffset() * 60000;
        var nd = new Date(app_display_time * 1000 + localOffset);

        var Hours = nd.getHours();
        if (Hours < 10) {
            Hours = "0" + Hours;
        }
        var Minutes = nd.getMinutes();
        if (Minutes < 10) {
            Minutes = "0" + Minutes;
        }
        var Seconds = nd.getSeconds();
        if (Seconds < 10) {
            Seconds = "0" + Seconds;
        }

        return Hours + ":" + Minutes + ":" + Seconds
    }

    var isChrome      = navigator.userAgent.toLowerCase().match(/chrome/) != null;//判断是否是谷歌浏览器
    var isFirefox     = navigator.userAgent.toLowerCase().match(/firefox/) != null;//判断是否是firefox浏览器
    var isIE          = navigator.userAgent.toLowerCase().match(/msie/) != null;//判断是否是IE浏览器
    var isIE7         = navigator.userAgent.toLowerCase().match('msie 7') != null;
    var isIE8         = navigator.userAgent.toLowerCase().match('msie 8') != null;
    var isIE9         = navigator.userAgent.toLowerCase().match('msie 9') != null;
    var isIE10        = navigator.userAgent.toLowerCase().match('msie 10') != null;
    var isIE11        = navigator.userAgent.toLowerCase().match('msie 11') != null;

    var COOKIE_TOOLS = {
        setCookie: function (name, value, date)//两个参数，一个是cookie的名子，一个是值
        {
            var Days = date; //此 cookie 将被保存 30 天
            var exp = new Date();    //new Date("December 31, 9998");
            exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);

            document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString() + ";path=/";
        },

        setOasCookie: function (name, value, date)//两个参数，一个是cookie的名子，一个是值
        {
            var Days = date; //此 cookie 将被保存 30 天
            var exp = new Date();    //new Date("December 31, 9998");
            exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);

            document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString() + ";path=/;domain=oasgames.com";
        },

        getCookie: function (name)//取cookies函数
        {
            var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
            if (arr != null) return unescape(arr[2]);
            return null;
        },

        delCookie: function (name)//删除cookie
        {
            COOKIE_TOOLS.setCookie(name, 0, -2);
        }
    };

    //如果浏览器是IE且a的href是一些指定的字符串  则将href属性删除    以解决IE下a点击后游戏错误地认为页面即将跳转的bug
    function ie_tab_bug_fixer() {
        if ($.browser.msie) {
            $("a").each(function () {
                var myHref = $(this).attr('href');
                var toRemove = false;
                if (myHref == 'javascript:void(0)') toRemove = true;
                if (myHref == 'javascript:void(0);') toRemove = true;
                if (myHref == 'javascript:') toRemove = true;
                if (myHref == 'javascript:;') toRemove = true;
                if (myHref == 'javascript:undefined') toRemove = true;
                if (myHref == 'javascript:undefined;') toRemove = true;

                if (toRemove) {
                    $(this).removeAttr('href').removeAttr('target').css('cursor', 'pointer');
                }
            })
        }
    }

    function checkAppWidth() {
        var width = window.screen.width;
        if (width <= 1152 && window.top != window) {
            window.onbeforeunload = function (e) {
                return;
            };
            window.top.location.href = FULL_SCREEN_URL;
        }
    }

    function resizeIE10() {
        if (navigator.userAgent.indexOf('MSIE 10') != -1) {
            var w = document.body.clientWidth;
            document.body.style.width = w + 1 + 'px';
            setTimeout(function () {
                document.body.style.width = w - 1 + 'px';
                document.body.style.width = 'auto';
            }, 0);  // 这个延时时间看情况可能需要适当调大
        }
    }


    is_login_status = function (t, v) {
        if (uid == '') {
            show_common_login();
        } else {
            t.target = '_blank';
            t.href = v;
        }
    };

    function show_common_login() {
        OAS_GAMES_WIDGETS.LoginBox.showLogin();
    }

    function show_common_reg() {
        OAS_GAMES_WIDGETS.LoginBox.showReg();
    }

    function isEmail(a) {
        var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return filter.test(a);
    }
<!--</script>-->
