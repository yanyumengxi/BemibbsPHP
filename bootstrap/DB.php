<?php

namespace bemibbs;


use mysql_xdevapi\SqlStatement;
use mysqli;
use mysqli_result;

/**
 * @author Linqgi <3615331065@qq.com>
 * @time 2022-05-16 19:15
 */
class DB
{
    /**
     * Mysqli数据库对象
     * @var mysqli
     */
    public Mysqli $mysql;

    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->mysql = new Mysqli();
    }

    /**
     * 链接数据库
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @param int $port
     * @return DB
     */
    public function connect(string $host, string $username, string $password, string $dbname, int $port): DB
    {
        $this->mysql->connect($host.':'.$port, $username, $password, $dbname);
        return $this;
    }

    /**
     * 执行Sql语句
     * @param $sql
     * @return bool|mysqli_result
     */
    public function execute($sql)
    {
        return $this->mysql->query($sql);
    }

    /**
     * 设置字符集
     * @param $charset
     * @return void
     */
    public function setCharset($charset)
    {
        $this->execute("set names $charset");
    }

    /**
     * 选择数据库
     * @param $name
     * @return void
     */
    public function select($name)
    {
        $this->execute("use $name");
    }

    /**
     * 增加数据
     * @param $sql
     * @return false|int|string
     */
    public function insert($sql)
    {
        $this->execute($sql);
        return mysqli_affected_rows($this->mysql) ? mysqli_affected_rows($this->mysql) : false;
    }

    /**
     * 删除数据
     * @param $sql
     * @return false|int|string
     */
    public function delete($sql)
    {
        $this->execute($sql);
        return mysqli_affected_rows($this->mysql) ? mysqli_affected_rows($this->mysql) : false;
    }

    /**
     * 更新数据
     * @param $sql
     * @return false|int|string
     */
    public function update($sql)
    {
        $this->execute($sql);
        return mysqli_affected_rows($this->mysql) ? mysqli_affected_rows($this->mysql) : false;
    }

    /**
     * 查询一条数据
     * @param $sql
     * @return array|false|null
     */
    public function query($sql)
    {
        $res = $this->execute($sql);
        return mysqli_fetch_row($res) ? mysqli_fetch_assoc($res) : false;
    }

    /**
     * 查询多条数据
     * @param $sql
     * @return array|false
     */
    public function queryAll($sql)
    {
        $res = $this->execute($sql);

        if (mysqli_num_rows($res)) {
            $list = array();
            while ($row = mysqli_fetch_assoc($res)) {
                $list[] = $row;
            }
            return $list;
        }
        return false;
    }

    /**
     * 关闭链接
     * @return void
     */
    public function close()
    {
        $this->mysql->close();
    }
}