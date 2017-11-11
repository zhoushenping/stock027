<style>
    table {
        margin: 50px auto;
    }

    table, td {
        border-collapse: collapse;
    }

    td, th {
        border: 1px solid deepskyblue;
        padding: 5px 10px;
    }
</style>
<table>
    <tr>
        <th>股票名称</th>
        <th>股票代码</th>
        <th>市值（亿）</th>
        <th>市盈率</th>
        <th>昨收</th>
        <th>现价</th>
        <th>降幅</th>
        <th>价格时间</th>
    </tr>
    <?php
    foreach ($records as $item) {
        $link = "http://finance.sina.com.cn/realstock/company/{$item['symbol']}/nc.shtml";
        ?>
        <tr>
            <td>
                <a href="<?= $link ?>" target="_blank">
                    <?= $item['name'] ?>
                </a>
            </td>
            <td>
                <a href="<?= $link ?>" target="_blank">
                    <?= $item['symbol'] ?>
                </a>
            </td>
            <td><?= $item['marketValue'] ?></td>
            <td><?= $item['syl'] ?></td>
            <td><?= $item['settlement'] ?></td>
            <td><?= $item['trade'] ?></td>
            <td><?= $item['amp'] ?>%</td>
            <td><?= date('Y-m-d H:i:s', $item['timestamp']) ?></td>
        </tr>
        <?
    }
    ?>
</table>

