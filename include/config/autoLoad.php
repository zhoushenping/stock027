<?php
/**
 * Created by PhpStorm.
 * User: zsp
 * Date: 2017/8/15
 * Time: 下午5:58
 */
function __autoload($className)
{
    //使得可以自动加载目录内的类文件
    $arr_dir = array('east');
    foreach ($arr_dir as $i) {
        if (strpos($className, $i) === 0) {
            $className = str_replace($i, "$i/", $className);
            break;
        }
    }

    $fileName = ROOT_PATH . "/models/" . $className . ".class.php";
    if (file_exists($fileName)) {
        require_once $fileName;
    }
}
