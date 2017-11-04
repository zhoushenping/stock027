<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="renderer" content="webkit">
    <link rel="Stylesheet" type="text/css" href="/static/page/sina/base_stock_A_20170306.css"/>
    <link rel="Stylesheet" type="text/css" href="/static/page/sina/stock.20121213.3.css"/>
    <link rel="stylesheet" type="text/css" href="/static/page/sina/finance.min_2.css"/>
    <link rel="stylesheet" type="text/css" href="/static/page/sina/tzyTG.css"/>
    <style type="text/css" id="globalIndexScrollerCss">
        #h5Container .flash {
            overflow: visible;
        }

        <?
        if($_REQUEST['type']=='simple'){
        ?>
        .hq_details {
            display: none;
        }

        <?
        }
        ?>
    </style>
    <script type="text/javascript" src="http://hq.sinajs.cn/list=sz002354"></script>
    <script type="text/javascript" src="/static/page/sina/swfobject2.2.js"></script>
    <script src="/static/page/sina/jquery.newest.js"></script>
    <script type="text/javascript" src="/static/page/sina/utils-hq.js" charset="utf-8"></script>
    <script src='/static/page/sina/sf_sdk.js'></script>
    <script type="text/javascript" src="/static/page/sina/stock_A_sz20170608.js" charset="utf-8"></script>
    <script type="text/javascript">
        var flag = 1; //判断标志
        var a_code = '<?=$symbol?>'; //流通A股代码
        var b_code = ''; //流通B股代码
        var papercode = '<?=$symbol?>'; //当前页面个股代码
        var mgjzc = <?=$stockInfo['mgjzc']?>;//最近报告的每股净资产
        var stock_state = 1;//个股状态（0:无该记录; 1:上市正常交易; 2:未上市; 3:退市）
        var trans_flag = 1;//是否显示涨跌停价（1:显示 0:不显示）
        var profit_four = <?=$stockInfo['profit_four']?>;//最近四个季度净利润
        var stockType = 'A'; //股票类型  A-A股 B-B股  I-指数
        var stockname = 'stockname'; //股票名称
        var corr_hkstock = ''; //相关港股代码
        var corr_bdc = ''; //相关债券可转换债
        var corr_bde = ''; //相关债券普通企业债

        var bkSymbol = 'new_dzxx';
        var wbAppKey = '3202088101';
        var mrq_mgsy = 1.1333333333333;
        var flashURL = '/static/page/sina/cn.swf';

        //相关期货
        var RS = {};
        RS.corr_future = [];
        //综合评级级别
        var gradeLevel = 3;
        //综合评级研究报告数量 ( TODO PHP写进页面)
        var gradeAmt = 6;
        <?
        if($_REQUEST['type'] == 'simple'){
        ?>
        //        setInterval("autoHide();", 100);
        <?
        }
        ?>

        function autoHide() {
            $("div[id ^='KKE_chart']>div>div:eq(1)>div:gt(0)").hide();
            $("div[id ^='KKE_chart']>div>div:eq(2)").hide();
//            $("div[id ^='KKE_chart']").css({'height':'340px','overflow':'hidden'});
        }
    </script>
</head>
<body>
<div class="wrap main_wrap clearfix">

    <div class="R">
        <div class="block_hq clearfix">
            <div class="hq_L">
                <?
                if ($_REQUEST['type'] != 'simple') {
                    ?>
                    <div class="hq_title">
                        <h1 id="stockName"><span><?= $symbol ?></span></h1>
                    </div>
                    <?
                }
                ?>

                <div class="hq_details has_limit" id="hq">
                    <div class="price_time">
                        <div class="price clearfix" id="trading">
                            <div class="change">
                                <div id="change" class="@UD_change@">@change@</div>
                                <div id="changeP" class="@UD_change@">@changeP@</div>
                            </div>
                            <div id="arrow" class="arrow arrow_@UD_change@"></div>
                            <div id="price" class="@UD_change@">@now@</div>
                            <div class="ud_limit" id="ud_limie">
                                <div>涨停：@up_limit@</div>
                                <div>跌停：@down_limit@</div>
                            </div>
                        </div>
                        <div class="price" id="closed">
                            停牌
                        </div>
                        <div class="time" id="hqTime">
                            @date@ @time@
                        </div>
                        <div class="time blue_l" id="hqPause">临时停牌</div>
                    </div>
                    <div class="other" id="hqDetails">
                        <table border="0" cellpadding="0" cellspacing="0">
                            <colgroup>
                                <col width="60"/>
                                <col width="50"/>
                                <col width="60"/>
                                <col width="70"/>
                                <col width="70"/>
                                <col width="40"/>
                            </colgroup>
                            <tbody>
                            <tr>
                                <th>今&nbsp;&nbsp;开：</th>
                                <td class="@UD_open_color@">@open@</td>
                                <th>成交量：</th>
                                <td>@volume@</td>
                                <th>振&nbsp;&nbsp;幅：</th>
                                <td>@swing@</td>
                            </tr>
                            <tr>
                                <th>最&nbsp;&nbsp;高：</th>
                                <td class="@UD_high_color@">@high@</td>
                                <th>成交额：</th>
                                <td>@amount@</td>
                                <th>换手率：</th>
                                <td>@turnover@</td>
                            </tr>
                            <tr>
                                <th>最&nbsp;&nbsp;低：</th>
                                <td class="@UD_low_color@">@low@</td>
                                <th>总市值：</th>
                                <td>@totalShare@</td>
                                <th>市净率：</th>
                                <td>@pb@</td>
                            </tr>
                            <tr>
                                <th>昨&nbsp;&nbsp;收：</th>
                                <td>@preClose@</td>
                                <th>流通市值：</th>
                                <td>@cvs@</td>
                                <th>市盈率<sup>TTM</sup>：</th>
                                <td>@pe@</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id='h5Container'>
                    <div class="wrapflash">
                        <div class='flash fs_full' style='position:relative;' id='h5Figure'>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">hq.init();</script>
    </div>
</div>
</body>
</html>
