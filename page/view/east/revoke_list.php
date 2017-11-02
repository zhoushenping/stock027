<?
//eastRevoke::getList();
?>

<div class="v_con">
    <div class="operate">
        <label class="checkbox-inline">
            <input type="checkbox" name="optionsCheckbox" id="chkall" value="255">
            全部
        </label>
        <button type="button" class="btn btn_cancel ml20" id="revokeAll">批量撤单</button>
    </div>
    <div class="listtable">
        <table>
            <thead>
            <tr>
                <th class="w40">选择</th>
                <th>委托时间</th>
                <th>证券代码</th>
                <th>证券名称</th>
                <th>委托方向</th>

                <th>委托数量</th>
                <th>委托状态</th>
                <th>委托价格</th>
                <th>成交数量</th>

                <th>成交金额</th>
                <th>成交价格</th>
                <th>委托编号</th>
                <th class="w80">操作</th>
            </tr>
            </thead>
            <tbody id="tabBody">
            <?
            foreach (eastRevoke::getList() as $item) {
                ?>
                <tr>
                    <td><input type="checkbox" name="chk_list" value="<?= $item['Wtrq'] ?>_<?= $item['Wtbh'] ?>"/></td>
                    <td><?= $item['Wtsj'] ?></td>
                    <td><?= $item['Zqdm'] ?></td>
                    <td><?= $item['Zqmc'] ?></td>
                    <td><?= $item['Mmsm'] ?></td>

                    <td><?= $item['Wtsl'] ?></td>
                    <td><?= $item['Wtzt'] ?></td>
                    <td><?= $item['Wtjg'] ?></td>
                    <td><?= $item['Cjsl'] ?></td>

                    <td><?= $item['Cjje'] ?></td>
                    <td><?= $item['Cjjg'] ?></td>
                    <td><?= $item['Wtbh'] ?></td>
                    <td>
                        <button class="btn btn_cancel" type="button"
                                onclick="revokeOrders('<?= $item['Wtrq'] ?>_<?= $item['Wtbh'] ?>')">撤单
                        </button>
                    </td>
                </tr>
                <?
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<link rel="stylesheet" href="/static/page/east/revoke_list.css?3">
<script src="/static/common/jquery-1.7.2.min.js"></script>
<script src="/static/common/tools.php"></script>
<script>
    var revokeOrders = function (str) {
        var url = "/?a=east&m=revokeApi&revokeStr=" + str;
        var call = function (res) {

        };
        ajaxRequest(url, call);
    };

    $(function () {
        $("#revokeAll").click(function () {
            var rowNums = '';
            $("input[name='chk_list']").each(function () {
                rowNums += ',' + $(this).val();
            });
            revokeOrders(rowNums.substr(1));
        });
    })
</script>
