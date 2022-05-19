<?php

namespace bemibbs\db\v1;


use mysqli;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-18 21:39
 */
class DB
{
    private $_db = null;//数据库连接句柄
    private string $_table = "";//表名
    private string $_where = "";//where条件
    private string $_order = "";//order排序
    private string $_limit = "";//limit限定查询
    private string $_group = "";//group分组
    private array $_configs = array(
        'hostname' => 'localhost',
        'dbname' => 'test',
        'username' => 'root',
        'password' => '1234'
    );//数据库配置

    /**
     * 构造函数，连接数据库
     */
    public function __construct()
    {
        $link = $this->_db;
        if (!$link) {
            $db = mysqli_connect($this->_configs['hostname'], $this->_configs['username'], $this->_configs['password'], $this->_configs['dbname']);
            mysqli_query($db, "set names utf8");
            if (!$db) {
                $this->ShowException("错误信息" . mysqli_connect_error());
            }
            $this->_db = $db;
        }
    }

    /**
     * 获取所有数据
     * @param string $table The table
     * @return array|bool
     */
    public function getAll(string $table = "")
    {
        $link = $this->_db;
        if (!$link) return false;
        $sql = "SELECT * FROM {$table}";
        return mysqli_fetch_all($this->execute($sql));
    }

    public function table($table): DB
    {
        $this->_table = $table;
        return $this;
    }

    /**
     * 实现查询操作
     * @param string $fields The fields
     * @return array|bool
     */
    public function select(string $fields = "*")
    {
        $fieldsStr = '';
        $link = $this->_db;
        if (!$link) return false;
        if (is_array($fields)) {
            $fieldsStr = implode(',', $fields);
        } elseif (is_string($fields) && !empty($fields)) {
            $fieldsStr = $fields;
        }
        $sql = "SELECT {$fields} FROM {$this->_table} {$this->_where} {$this->_order} {$this->_limit}";
        return mysqli_fetch_all($this->execute($sql));
    }

    /**
     * order排序
     * @param string $order The order
     * @return DB|bool
     */
    public function order(string $order = '')
    {
        $orderStr = '';
        $link = $this->_db;
        if (!$link) return false;
        if (is_string($order) && !empty($order)) {
            $orderStr = "ORDER BY " . $order;
        }
        $this->_order = $orderStr;
        return $this;
    }

    /**
     * where条件
     * @param string $where The where
     * @return DB  ( description_of_the_return_value )
     */
    public function where(string $where = ''): DB
    {
        $whereStr = '';
        $link = $this->_db;
        if (!$link) return $link;
        if (is_array($where)) {
            foreach ($where as $key => $value) {
                if ($value == end($where)) {
                    $whereStr .= "`" . $key . "` = '" . $value . "'";
                } else {
                    $whereStr .= "`" . $key . "` = '" . $value . "' AND ";
                }
            }
            $whereStr = "WHERE " . $whereStr;
        } elseif (is_string($where) && !empty($where)) {
            $whereStr = "WHERE " . $where;
        }
        $this->_where = $whereStr;
        return $this;
    }

    /**
     * group分组
     * @param string $group The group
     * @return DB|bool
     */
    public function group(string $group = '')
    {
        $groupStr = '';
        $link = $this->_db;
        if (!$link) return false;
        if (is_array($group)) {
            $groupStr = "GROUP BY " . implode(',', $group);
        } elseif (is_string($group) && !empty($group)) {
            $groupStr = "GROUP BY " . $group;
        }
        $this->_group = $groupStr;
        return $this;
    }

    /**
     * limit限定查询
     * @param string $limit The limit
     * @return DB  ( description_of_the_return_value )
     */
    public function limit(string $limit = ''): DB
    {
        $limitStr = '';
        $link = $this->_db;
        if (!$link) return $link;
        if (is_string($limit) || !empty($limit)) {
            $limitStr = "LIMIT " . $limit;
        } elseif (is_numeric($limit)) {
            $limitStr = "LIMIT " . $limit;
        }
        $this->_limit = $limitStr;
        return $this;
    }

    /**
     * 执行sql语句
     *
     * @param string|null $sql The sql
     * @return boolean  ( description_of_the_return_value )
     */
    public function execute(string $sql = null): bool
    {
        $link = $this->_db;
        if (!$link) return false;
        $res = mysqli_query($this->_db, $sql);
        if (!$res) {
            $errors = mysqli_error_list($this->_db);
            $this->ShowException("报错啦！<br/>错误号：" . $errors[0]['errno'] . "<br/>SQL错误状态：" . $errors[0]['sqlstate'] . "<br/>错误信息：" . $errors[0]['error']);
            die();
        }
        return $res;
    }

    /**
     * 插入数据
     * @param $data
     * @return boolean  ( description_of_the_return_value )
     */
    public function insert($data): bool
    {
        $link = $this->_db;
        if (!$link) return false;
        if (is_array($data)) {
            $keys = '';
            $values = '';
            foreach ($data as $key => $value) {
                $keys .= "`" . $key . "`,";
                $values .= "'" . $value . "',";
            }
            $keys = rtrim($keys, ',');
            $values = rtrim($values, ',');
        }
        $sql = "INSERT INTO `{$this->_table}`({$keys}) VALUES({$values})";
        mysqli_query($this->_db, $sql);
        return mysqli_insert_id($this->_db);
    }

    /**
     * 更新数据
     * @param $data
     * @return bool|mysqli|null
     */
    public function update($data)
    {
        $link = $this->_db;
        if (!$link) return $link;
        if (is_array($data)) {
            $dataStr = '';
            foreach ($data as $key => $value) {
                $dataStr .= "`" . $key . "`='" . $value . "',";
            }
            $dataStr = rtrim($dataStr, ',');
        }
        $sql = "UPDATE `{$this->_table}` SET {$dataStr} {$this->_where} {$this->_order} {$this->_limit}";
        return $this->execute($sql);
    }

    /**
     * 删除数据
     * @return bool|mysqli|null
     */
    public function delete()
    {
        $link = $this->_db;
        if (!$link) return $link;
        $sql = "DELETE FROM `{$this->_table}` {$this->_where}";
        return $this->execute($sql);
    }

    /**
     * 异常信息输出
     * @param $var
     */
    private function ShowException($var)
    {
        if (is_bool($var)) {
            var_dump($var);
        } else if (is_null($var)) {
            var_dump(NULL);
        } else {
            echo "<pre style='position:relative;z-index:1000;padding:10px;border-radius:5px;background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;opacity:0.9;'>" . print_r($var, true) . "</pre>";
        }
    }
}