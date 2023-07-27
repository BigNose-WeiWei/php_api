<?php

namespace Unit\Database;

use Exception;

class DBconnect
{
    private static ?object $instance = null;
    private ?object $db = null;
    private ?Exception $error = null;

    function __construct()
    {
        $this->db = new Mysql_PDO;

        if (!$this->db->Isconnected()) {
            $this->connect();
        }
    }

    /**
     * 實例化
     */
    static function getinstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new DBconnect;
        }

        return self::$instance;
    }

    /**
     * DB連線
     */
    function connect()
    {
        $this->db->connect(DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASSWORD);
    }

    /**
     * DB連線狀態
     */
    function Isconnected(): bool
    {
        return $this->db->Isconnected();
    }

    /**
     * 查詢
     */
    function query_fetch(String $sql = "", ?array $set)
    {
        $result = $this->db->query_fetch($sql, $set);

        if (is_null($result)) {
            $this->error = $this->db->getError();
            return null;
        }

        return $result;
    }

    /**
     * 新增
     */
    function insert(String $sql = "", ?array $set): bool
    {
        $result = $this->db->insert($sql, $set);

        if (!$result) {
            $this->error = $this->db->getError();
            return false;
        }

        return $result;
    }

    /**
     * 修改
     */
    function update(String $sql = "", ?array $set): bool
    {
        $result = $this->db->update($sql, $set);

        if (!$result) {
            $this->error = $this->db->getError();
            return false;
        }

        return $result;
    }

    /**
     * 刪除
     */
    function delete(String $sql = "", ?array $set)
    {
        $result = $this->db->delete($sql, $set);

        if (!$result) {
            $this->error = $this->db->getError();
            return false;
        }

        return $result;
    }

    /**
     * 關閉DB
     */
    function close()
    {
        $this->db->close();
    }

    function getConnectError()
    {
        return $this->db->getError();
    }

    function getError(): Exception
    {
        return $this->error;
    }
}
