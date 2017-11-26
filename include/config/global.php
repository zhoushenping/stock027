<?php
error_reporting(0);
require_once ROOT_PATH . "models/SupperLog.class.php";
function register_shutdown_function_handle()
{
    $logTypes = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR);
    $error    = error_get_last();
    if (is_null($error)) {
        return;
    }

    if (in_array($error['type'], $logTypes)) {
        $sys_error = array('error' => $error);
        $str_error = '[ALERT]|[system_error]' . json_encode($sys_error);
        SupperLog::save_run_log($str_error, 'shutdown_lobr');
    }
}

register_shutdown_function('register_shutdown_function_handle');

header("Content-type: text/html; charset=utf-8");
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');/*使得IE接受第三方cookie*/
@session_start();
/* 初始化设置 */
@ini_set('memory_limit', '64M');
@ini_set('session.cache_expire', 180);
@ini_set('session.use_cookies', 1);
@ini_set('session.auto_start', 0);

error_reporting(0);//E_ALL
//error_reporting(E_ALL);//E_ALL
define('TEST', 0);
define('DEVELOP_MODE', 0);//0=非开发模式(上线)	1=测试服上	2=本地开发
define('DB_SET', 'online');//用那个数据库  online=线上  develop=开发
define("ODP_URL", "//odp.oasgames.com");

date_default_timezone_set("Asia/Shanghai");
define("APP_ID", "340989056058098");
define("APP_SECRET", "8888888");
define("FB_JS_LANG", "ar_AR");
define("FB_URL", "http://apps.facebook.com/legend_ar/");//可配置
define("GAME_CODE", "loar");
define("GAME_CODE_STANDARD", "loar");
define("GA_ACCOUNT", "UA-33361405-27");
define('GAME_NAME', 'Legend Online');
define("LANG_OAS", "ar-ar");
define('LANG_URL', 'ar_ar');
define("SYSTEM_LANG", "ar");
define("WEB_HOST", "stock027.oasgames.com");

define('ZHUISUMA', time() . '_' . rand(100, 999));

// 要求定义好 _PATH_ 和 SCRIPT_NAME ，及定义 DS 为路径分隔符
if (!defined('DS')) {
    die("Error : (" . realpath(__FILE__) . ") not defined 'DS' ");
}
if (!defined('ROOT_PATH')) {
    die("Error : (" . realpath(__FILE__) . ") not defined 'ROOT_PATH' ");
}
if (!defined('SCRIPT_NAME')) {
    die("Error : (" . realpath(__FILE__) . ") not defined 'SCRIPT_NAME' ");
}
define('IN_APP', true);
/* Set the include path. */

define("CONFIG_PATH", ROOT_PATH . 'include' . DS . 'config' . DS);
define("MODELS_PATH", ROOT_PATH . 'include' . DS . 'models' . DS);
define("MAIL_PATH", ROOT_PATH . 'include' . DS . 'email' . DS);
define("LANG_PATH", ROOT_PATH . 'include' . DS . 'languages' . DS);
define("STATIC_PATH", ROOT_PATH . 'static' . DS);

/* Include MySQL CONFIG File */
require(CONFIG_PATH . 'config.db.php');

//自动导入类文件   命令行模式下此方法无效
require_once(ROOT_PATH . 'include/config/autoLoad.php');

//模拟未来当前时间,以方便测试
$_CURRENT_TIME = Time::getCurrentTime();

define("LOG_DIR", "/data/log/zsp");
define('OAS_DOMAIN', 'oasgames.com');//不变
define("PLATFORM", "home");//所在的平台是app还是home

