<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/11/4
 * Time: 下午7:48
 */
$symbol    = $_REQUEST['symbol'];
$stockInfo = eastStockInfo::readRecord($symbol);
