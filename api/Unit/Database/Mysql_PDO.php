<?php

namespace Unit\Database;

use Exception;
use PDO;
use Unit\Error\ErrorEx;
use Unit\Interface\DatabaseInterface;

class Mysql_PDO implements DatabaseInterface
{
    private bool $connected = false;
    private ?Object $PDO = null;
    private ?Exception $error = null;

    function Isconnected(): bool
    {
        return $this->connected;
    }

    function connect(string $dbhost, int $dbport, string $dbname, string $dbuser, string $dbpassword)
    {
        try {
            //php有無pdo_mysql
            if (!extension_loaded("pdo_mysql")) {
                throw new Exception("Please enable the pdo_mysql extension.", "-1");
            }

            $this->PDO = new PDO("mysql:host=$dbhost:$dbport;dbname=$dbname", $dbuser, $dbpassword);
            $this->connected = true;
        } catch (Exception $ex) {
            $this->error = $ex;
        }
    }

    function query_fetch(string $sql = "", ?array $set = null)
    {
        $db_execute = $this->execute($sql, $set);

        //SQL執行失敗的話跳出
        if (is_null($db_execute)) {
            return null;
        }

        $row = $db_execute->fetchAll(PDO::FETCH_ASSOC);

        //查詢結果為空 報錯跳出
        // if (empty($row)) {
        //     $this->error = new ErrorEx("DB response is null");
        //     return null;
        // }

        return $row;
    }

    function insert(string $sql = "", ?array $set = null): bool
    {
        $db_execute = $this->execute($sql, $set);

        //SQL執行失敗的話跳出
        if (is_null($db_execute)) {
            return false;
        }

        // DELETE、INSERT 或 UPDATE 執行後如果行數是0代表執行失敗
        if ($db_execute->rowCount() == 0) {
            $this->error = new ErrorEx("DB No Change");
            return false;
        }

        return true;
    }

    function update(String $sql = "", ?array $set = null): bool
    {
        $db_execute = $this->execute($sql, $set);

        //SQL執行失敗的話跳出
        if (is_null($db_execute)) {
            return false;
        }

        // DELETE、INSERT 或 UPDATE 執行後如果行數是0代表執行失敗
        if ($db_execute->rowCount() == 0) {
            $this->error = new ErrorEx("DB No Change");
            return false;
        }

        return true;
    }

    function delete(string $sql = "", ?array $set = null): bool
    {
        $db_execute = $this->execute($sql, $set);

        //SQL執行失敗的話跳出
        if (is_null($db_execute)) {
            return false;
        }

        // DELETE、INSERT 或 UPDATE 執行後如果行數是0代表執行失敗
        if ($db_execute->rowCount() == 0) {
            $this->error = new ErrorEx("DB No Change");
            return false;
        }

        return true;
    }

    function execute(string $sql = "", ?array $set = null)
    {
        try {
            $db_prepare = $this->PDO->prepare($sql);

            if (!is_null($set)) {
                $db_prepare->execute($set);
            } else {
                $db_prepare->execute();
            }

            return $db_prepare;
        } catch (Exception $ex) {
            $this->error = new ErrorEx($ex->getMessage() . " SQL: " . $sql . " SET:" . http_build_query($set), $ex->getCode(), $ex->getFile(), $ex->getLine());
            return null;
        }
    }

    function close()
    {
        $this->connected = false;
        $this->PDO = null;
    }

    function getError(): Exception
    {
        return $this->error;
    }
}
