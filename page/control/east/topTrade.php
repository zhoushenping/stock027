<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/12/13
 * Time: 下午10:28
 */

$rs = DBHandle::select(eastLast::table, "1 ORDER BY `amount` DESC LIMIT 0,100");
