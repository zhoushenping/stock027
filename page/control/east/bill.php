<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2018/5/31
 * Time: 下午4:12
 */
$date   = $_REQUEST['date'] != '' ? $_REQUEST['date'] : date('Ymd');
$amount = $_REQUEST['amount'];

eastBill::insert($amount, $date);

Browser::callback(eastBill::get());
