<?php

namespace Unit\Basal;

use Exception;
use Unit\Database\DBconnect;
use Unit\Interface\ServiceProviderInterface;
use Unit\Service\Parameters;

class Query implements ServiceProviderInterface
{
    private ?Parameters $rsp = null;
    private ?Exception $error = null;

    function __construct()
    {
        $this->rsp = new Parameters;
    }

    function run(Parameters $params)
    {
        $db = DBconnect::getinstance();

        //確認是否連線成功
        if (!$db->Isconnected()) {
            $this->error = $db->getConnectError();
            return false;
        }

        $set = [];
        //取得Client端條件
        if (!is_null($params->get("id"))) {
            $set["id"] = $params->get("id");
        }

        $result = $db->query_fetch("SELECT id,name,status FROM occ_file WHERE id=:id", $set);

        //DB回傳資料是否正常
        if (!is_null($result)) {
            $this->rsp->add("Result", $result);
            return true;
        }
        $this->error = $db->getError();
        return false;
    }

    function getResponse(): Parameters
    {
        return $this->rsp;
    }

    function getError(): Exception
    {
        return $this->error;
    }
}
