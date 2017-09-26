<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 数据库操作类
 */
class DBnew {
    private $host;          // 主机地址
    private $port;          // 端口
    private $user;          // 授权用户
    private $pwd;           // 密码
    private $database;      // 数据库名称
    private $db_charset;    // 数据库字符集

    private static $conn =null;          // 数据库连接资源

    //构造函数，与类名同名
    /**
     * 参数$conf 是配置数组，通过配置，初始化并创建一个数据库连接
     */
    public function __construct($conf) {
        $this->host = $conf['host'];
        $this->port = $conf['port'];
        $this->user = $conf['user'];
        $this->pwd  = $conf['pwd'];
        $this->database   = $conf['db_name'];
        if(empty($conf['db_charset'])){
            $this->db_charset = 'utf8';
        } else {
            $this->db_charset = strtolower($conf['db_charset']);
        }
        $this->init_connect();
        $this->select_db();
    }

    /**
     *	初始化数据库连接 无返回值
     */
    private function init_connect() {
        $server     = $this->host.":".$this->port;
        if(self::$conn==null){
            self::$conn = mysql_connect($server, $this->user, $this->pwd,true) or die("!!!!!!!!!database connect error !");
        }
    }

    /**
     *	选择要访问的数据库
     */
    private function select_db() {
        mysql_select_db($this->database, self::$conn);
        $set_sql    = "set character set ".$this->db_charset;
        mysql_query($set_sql,self::$conn);
        $set_sql    = " set names ".$this->db_charset;
        mysql_query($set_sql,self::$conn);
    }

    /**
     *	执行查询语句。返回结果集二维数组
     *  SELECT，SHOW，EXPLAIN 或 DESCRIBE
     */
    public function query($sql) {
        $rs = mysql_query ($sql, self::$conn);
        $this->save_log($sql);
        if(is_resource($rs)) {
            if(mysql_num_rows($rs)>0) {
                while ($row = mysql_fetch_array($rs, MYSQL_ASSOC)) {
                    $records_arr[] = $row;
                }
                return $records_arr;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
    /**
     * 查询单个记录的第一列，例如
     * @param string $sql
     */
    public function query_first_column($sql) {
        $rs = mysql_query ($sql, self::$conn);
        $this->save_log($sql);
        if(is_resource($rs)) {
            if(mysql_num_rows($rs)>0) {
                if ($row = mysql_fetch_array($rs, MYSQL_ASSOC)) {
                    foreach ($row as $key => $val){
                        return $val;
                    }
                    return null;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
    /**
     * 执行数据库操作脚本语句 返回成功或失败 ： TRUE OR FALSE
     * update
     */
    public function execute_sql($sql){
        return $this->exe_sql_and_log($sql);
    }

    /**
     * 执行插入操作返回数据id或失败 ： ID OR FALSE
     * insert
     */
    public function insert_sql($sql){
        $rs = $this->exe_sql_and_log($sql);
        if($rs){
            $id = mysql_insert_id(self::$conn);
            return  $id;
        }else{
            return  false;
        }
    }

    /**
     * 返回成功或失败 ： TRUE OR FALSE
     */
    private function exe_sql_and_log($sql) {
        $rs = mysql_query($sql, self::$conn);
        $this->save_log($sql);
        if($rs){
            return true;
        }else{
            return false;
        }
    }

    /**
     *  tostring 方法，查看本实例的连接参数。
     */
    public function to_string(){
        return "host:$this->host|port:$this->port|user:$this->user|pwd:$this->pwd|database:$this->database|chartset:$this->db_charset <br>";
    }

    public function save_log($sql)
    {
        $info = mysql_errno(self::$conn) . "|$sql|" . mysql_error(self::$conn);

        if (mysql_errno(self::$conn) == 0 && strlen($sql) >= 500) return false;
        Log2::save_run_log($info, 'db2');
    }

    public function close(){
        if(self::$conn){
            mysql_close(self::$conn);
        }
    }
    /**
     * 析构函数
     */
    public function __destruct() {
        $this->close();
    }


}
?>
