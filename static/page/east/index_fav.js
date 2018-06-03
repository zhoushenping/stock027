$(function () {
    $('#search').focus();

    $('table.tablesorter').tablesorter({
        widgets: ['zebra', 'columns'],
        usNumberFormat: true,
        sortReset: true,
        sortRestart: true
    });

    $('.viewChart').click(function () {
        $('.chartContainer').remove();
        $(this).parent().parent().after("<tr class='chartContainer'><td colspan='11'></td></tr>");
        $('.chartContainer td').append('<iframe src="./null.html" width="570" height="500" marginheight="0" marginwidth="0" frameborder="0" scrolling="no"></iframe>');
        $('.chartContainer iframe').attr('src', './?a=sina&m=clear_chart&type=simple&symbol=' + $(this).parent().parent().find('td:eq(0) a').text());
    });

    $('.chartContainer td').live('click', function () {
        $(this).parent().remove();
    });

    //搜索
    $('#search').keyup(function () {
        var str = $(this).val();
        if (str.length < 2) return;
        $('li').remove();

        var i = 0;
        for (var k in stockList) {
            var item = stockList[k];

            if (item.symbol.indexOf(str) > -1 || item.pinyin.indexOf(str) > -1) {
                if (_existSymbols.indexOf(item.symbol) > -1) {
                    $('ul').append("<li class='add invalid'><span>" + item.symbol + "</span><span>(" + item.name + ")</span></li>");
                } else {
                    i++;
                    $('ul').append("<li onclick=\"add('" + item.symbol + "');\" class='add '><span>" + item.symbol + "</span><span>(" + item.name + ")</span></li>");
                }
            }

            if (i == 10) break;
        }
    });

    $('td span').click(function () {
        $(this).hide().siblings().show().focus().select();
    });

    //修改参考价格 相关
    $('td input').keyup(function () {
        var _val = $(this).val().replace(/[^\d.]/g, "");  //清除“数字”和“.”以外的字符
        _val = _val.replace(/\.{2,}/g, ""); //只保留第一个. 清除多余的
        $(this).val(_val);
    });

    //修改参考价格 相关
    $('td input').focusout(function () {
        var _symbol = $(this).parent().parent().children('td').first().text();
        _symbol = $.trim(_symbol);
        var _price = $(this).val();
        var reg = /^\d*\.?\d{0,2}$/;

        if (reg.test(_price)) {
            var url = window.location + '&priceType=' + $(this).attr('class') + '&symbol=' + _symbol + '&price=' + _price;
            var callback = function (res) {
                console.log(res);
                if (res.status == 'ok') {
                    window.location.reload();
                }
            };
            ajaxRequest(url, callback);
        } else {
            $(this).focus().select();

            return;
        }
    });
});

//增加收藏
function add(symbol) {
    var url = window.location + '&func=add&symbol=' + symbol;
    var callback = function (res) {
        if (res.status == 'ok') {
            window.location.reload();
        }
    };
    ajaxRequest(url, callback);
}


