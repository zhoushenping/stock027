<script src="./static/common/jquery-1.7.2.min.js"></script>
<style>
    table {
        border: 2px solid gray;
        margin: 20px auto;
        display: none;
        width: 380px;
    }

    button {
        cursor: pointer;
        margin-top: 15px;
    }

    button.on {
        background-color: deeppink;
    }

    button:hover {
        background-color: deeppink;
    }

    #buttonContainer button {
        margin-right: 10px;
    }
</style>


<div style="width: 500px;margin:50px auto;">
    <div style="text-align: center;" id="buttonContainer">
        <button class="login on">登录</button>
        <button class="register">注册</button>
        <button class="reset_pwd">修改密码</button>
    </div>

    <table id="login">
        <form action="" method="post">
            <input type="hidden" name="act" value="login">
            <tr>
                <td>用户名:</td>
                <td>
                    <input type="text" name='uname' value="<?= $_COOKIE['zhanmo_uname'] ?>"/>
                </td>
            </tr>
            <tr>
                <td>密码:</td>
                <td>
                    <input type="password" name="pwd"/>
                </td>
            </tr>
            <tr style="text-align: center;">
                <td colspan="2">
                    <button type="submit">登录</button>
                </td>
            </tr>
        </form>
    </table>

    <table id="register">
        <form action="" method="post">
            <input type="hidden" name="act" value="register">
            <tr>
                <td>用户名:</td>
                <td>
                    <input type="text" name='uname' value=""/>
                </td>
            </tr>
            <tr>
                <td>请输入密码:</td>
                <td>
                    <input type="password" name="pwd"/>
                </td>
            </tr>
            <tr>
                <td>请再次输入密码:</td>
                <td>
                    <input type="password" name="pwd_rep"/>
                </td>
            </tr>
            <tr style="text-align: center;">
                <td colspan="2">
                    <button type="submit">注册</button>
                </td>
            </tr>
        </form>
    </table>

    <?
    if (ZhanmoUsers::isLogined()) {
        ?>
        <table id="reset_pwd">
            <form action="" method="post">
                <input type="hidden" name="act" value="reset_pwd">
                <tr>
                    <td>请输入新密码:</td>
                    <td>
                        <input type="password" name="pwd"/>
                    </td>
                </tr>
                <tr>
                    <td>请再次输入新密码:</td>
                    <td>
                        <input type="password" name="pwd_rep"/>
                    </td>
                </tr>
                <tr style="text-align: center;">
                    <td colspan="2">
                        <button type="submit">修改</button>
                    </td>
                </tr>
            </form>
        </table>
    <? } ?>
</div>

<script>
    $(function () {
        $('div button').click(function () {
            $(this).addClass('on').siblings().removeClass('on');
            $('table').hide();
        });

        $('button.login').click(function () {
            $('#login').show().children('input:eq(1)').focus();

            if ($('#login').children('input:eq(1)').val() != '') {
                $('#login').children('input:eq(2)').focus();
            }
        });

        $('button.register').click(function () {
            $('#register').show().children('input:eq(1)').focus();
        });

        $('button.reset_pwd').click(function () {
            $('#reset_pwd').show().children('input:eq(1)').focus();
        });


        var show_act = '<?=$act?>';

        if (show_act == '') {
            show_act = 'login';
        }

        $('button.' + show_act).click();
    })
</script>
