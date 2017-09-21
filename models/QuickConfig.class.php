<?php

/**
 * Created by PhpStorm.
 * User: shenping zhou
 * Date: 2017/7/26
 * Time: 17:40
 * 本类的防注入没有做好 仅供内部使用
 */
class QuickConfig
{

    const table = 'quick_config';
    static $cache = array();

    static function renewCache($page)
    {
        $ret = array();
        $rs  = DBHandle::select(self::table, "`page`='{$page}'", "`key`,`page`,`value`");
        foreach ($rs as $item) {
            $ret[$item['key']] = $item['value'];
        }

        self::$cache[$page] = $ret;
    }

    static function getValue($str)
    {
        $temp = explode('|', $str);
        $page = $temp[0];
        $key  = $temp[1];
        if (!isset(self::$cache[$page])) {
            self::renewCache($page);
        }

        return self::$cache[$page][$key];
    }

    static function insert($data)
    {
        $arr_key   = array('page', 'key', 'value', 'comment',);
        $arr_value = array(
            array(
                $data['page_r'],
                $data['key_r'],
                $data['value_r'],
                $data['comment_r'],
            ),
        );

        DBHandle::insertMultiIgnore(self::table, $arr_key, $arr_value);
    }

    static function update($data)
    {
        $arr_value = array(
            'value'   => $data['value_r'],
            'comment' => $data['comment_r'],
        );

        $arr_where = array(
            "`page`='{$data['page_r']}'",
            "`key`='{$data['key_r']}'",
        );

        DBHandle::update(self::table, Mysql::makeInsertString($arr_value), Mysql::makeWhereFromArray($arr_where));
    }

    static function getMultiRecord($page_r = '')
    {
        $str_where = $page_r == '' ? 1 : "`page`='$page_r'";

        return DBHandle::select(self::table, $str_where);
    }

    static function getItem($id)
    {
        $id = (int)$id;
        $rs = DBHandle::select(self::table, "`id`='$id'");

        return $rs[0];
    }

}
