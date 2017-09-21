<?php

/**
 * Created by PhpStorm.
 * User: shenping zhou
 * Date: 2015/5/19
 * Time: 17:14
 */
class DBHandle
{

    static function select($table, $where = '1', $columns = '*')
    {
        $sql = "SELECT $columns FROM `$table` WHERE $where";

        return self::query($sql);
    }

    static function update($table, $str_set, $where)
    {
        if ($str_set == '') {
            return false;
        }
        $sql = "UPDATE IGNORE `$table` SET  $str_set WHERE $where LIMIT 1";

        return self::execute($sql);
    }

    static function updateMulti($table, $str_set, $where)
    {
        if ($str_set == '') {
            return false;
        }
        $sql = "UPDATE `$table` SET  $str_set WHERE $where";

        return self::execute($sql);
    }

    static function MyInsert($table, $str_set)
    {
        if ($str_set == '') {
            return false;
        }
        $sql = "INSERT INTO `$table` SET $str_set";

        return self::insert($sql);
    }

    static function insertMulti($table, $arr_columns, $arr_data)
    {
        $str_columns = implode("`,`", $arr_columns);

        $temp = array();

        foreach ($arr_data as $item) {
            foreach ($item as $k => $v) {
                $item[$k] = is_numeric($v) ? "$v" : "'$v'";
            }
            $uu = implode(",", $item);

            $temp[] = "($uu)";
        }

        $str_values = implode(",", $temp);

        $sql = "INSERT INTO `$table` (`$str_columns`) VALUES $str_values";

        return self::insert($sql);
    }

    //尽可能用上面写好的方法

    static function getHandle()
    {
        static $handle;
        global $oas_db_conf;
        if (!$handle) {
            $handle = new DBnew($oas_db_conf);
        }

        return $handle;
    }

    static function query($sql)
    {
        $db = self::getHandle();

        return $db->query($sql);
    }

    static function insert($sql)
    {
        $db = self::getHandle();

        return $db->insert_sql($sql);
    }

    static function execute($sql)
    {
        $db = self::getHandle();

        return $db->execute_sql($sql);
    }

    static function insertMultiIgnore($table, $arr_columns, $arr_data)
    {
        $str_columns = implode("`,`", $arr_columns);

        $temp = array();

        foreach ($arr_data as $item) {
            foreach ($item as $k => $v) {
                $v        = addslashes($v);
                $item[$k] = "'$v'";
            }
            $uu = implode(",", $item);

            $temp[] = "($uu)";
        }

        $str_values = implode(",", $temp);

        $sql = "INSERT IGNORE INTO `$table` (`$str_columns`) VALUES $str_values";

        return self::insert($sql);
    }
}
