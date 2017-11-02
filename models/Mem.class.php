<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-23
 * Time: 下午8:46
 */
class Mem
{

    static $handleType = 1;//1=memcached 0=memcache

    static function getConfig()
    {
        $arr = array(
            'server' => '127.0.0.1',
            'port'   => '11211',
        );

        return $arr;
    }

    static function getMemHandle()
    {
        static $ret;
        if (!$ret) {
            $config = self::getConfig();

            if (Extention::isLoaded('memcached')) {
                /**@var Memcached $ret */
                $ret = new Memcached();
                $ret->addServers(
                    array(
                        array($config['server'], $config['port']),
                    )
                );
                self::$handleType = 1;
            }
            else {
                /**@var Memcache $ret */
                $ret = new Memcache();
                $ret->connect($config['server'], $config['port']);
                self::$handleType = 0;
            }
        }

        return $ret;
    }

    static function set($key, $value, $expire = 0)
    {
        if ($expire == 0) {
            $expire = 3600 * 24 * 7;//默认有效时间为7天
        }
        if ($expire == 'forever') {
            $expire = 0;//可以设置为永不过期
        }
        $handle = self::getMemHandle();

        if (self::$handleType) {
            /**@var Memcached $handle */
            $handle->set($key, $value, $expire);
        }
        else {
            /**@var Memcache $handle */
            $handle->set($key, $value, 0, $expire);
        }
    }

    static function get($key)
    {
        $handle = self::getMemHandle();

        return $handle->get($key);
    }

    //多少秒后删除key为$key的元素
    static function delete($key, $after_secs = 0)
    {
        $handle = self::getMemHandle();
        $handle->delete($key, $after_secs);
    }

    static function replace($key, $value)
    {
        $handle = self::getMemHandle();
        $handle->replace($key, $value);
    }

    static function increase($key, $num = 1)
    {
        $handle = self::getMemHandle();
        $handle->increment($key, $num);
    }

    static function decrease($key, $num = 1)
    {
        $handle = self::getMemHandle();
        $handle->decrement($key, $num);
    }
}
