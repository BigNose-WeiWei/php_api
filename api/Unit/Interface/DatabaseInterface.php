<?php

namespace Unit\Interface;

use Exception;

interface DatabaseInterface
{
    /**
     * 連線狀態
     */
    function Isconnected(): bool;

    /**
     * DB連線
     */
    function connect(String $dbhost, int $dbport, String $dbname, String $dbuser, String $dbpassword);

    /**
     * 查詢
     */
    function query_fetch(String $sql = "", ?array $set = null);

    /**
     * 新增
     */
    function insert(String $sql = "", ?array $set = null): bool;

    /**
     * 修改
     */
    function update(String $sql = "", ?array $set = null): bool;

    /**
     * 刪除
     */
    function delete(String $sql = "", ?array $set = null): bool;

    /**
     * 執行
     */
    function execute(String $sql = "", ?array $set = null);

    /**
     * 關閉DB
     */
    function close();

    function getError(): Exception;
}
