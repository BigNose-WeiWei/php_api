<?php

namespace Unit\Service;

use Exception;

class Parameters
{
    private $elements = [];
    private ?Parameters $req = null;
    private bool $debug = false;

    /**
     * 取得Client需求
     */
    function parse($params)
    {
        $this->elements = json_decode($params, true);   //解碼Json
        //print_r($this->elements);

        $err = json_last_error();   //取得解碼失敗訊息
        if ($err != JSON_ERROR_NONE) {
            switch ($err) {
                case JSON_ERROR_DEPTH:
                    $msg = "Maximum stack depth exceeded";
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $msg = "Invalid or malformed JSON";
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $msg = "Control character error";
                    break;
                case JSON_ERROR_SYNTAX:
                    $msg = "Syntax error";
                    break;
                case JSON_ERROR_UTF8:
                    $msg = "Malformed UTF-8 characters";
                    break;
                default:
                    $msg = "Unknown error";
                    break;
            }
            throw new Exception($msg, $err);    //觸發catch
        }
    }

    /**
     * 新增
     */
    function add($key, $value)
    {
        $this->elements[$key] = $value;
    }

    /**
     * 取得Key
     */
    function get($key)
    {
        return $this->elements[$key] ?? null;
    }

    /**
     *  取得All
     */
    function toArray()
    {
        return $this->elements;
    }

    /**
     * 轉Json
     */
    function toJson()
    {
        return json_encode($this->elements);
    }

    function setReqParameter(Parameters $req)
    {
        $this->req = $req;
        $this->isDebug();
    }

    /**
     * 啟用Debug
     */
    function isDebug(): bool
    {
        if (!$this->debug) {
            $this->debug = $this->req?->get("debug") ?? true; //強制啟動 ?-> php 8.0後可用
        }
        return $this->debug;
    }

    /**
     * 取得錯誤訊息
     */
    function AddDebugInfo(?Exception $ex)
    {
        $this->DebugInfo($ex, false);
    }

    /**
     * 自訂錯誤訊息
     */
    function DebugInfo(?Exception $ex, bool $IsException)
    {
        if ($this->isDebug()) {
            $result = [];

            $err = array(
                "Code" => $ex->getCode(),
                "Message" => $ex->getMessage(),
                "File" => $ex->getFile(),
                "Line" => $ex->getLine()
            );

            if ($IsException) {
                $result["Exception"] = $err;
            } else {
                $result["Error"] = $err;
            }

            $this->add("DebugInfo", $result);
        }
    }
}
