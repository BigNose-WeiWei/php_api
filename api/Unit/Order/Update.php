<?php

namespace Unit\Order;

use Exception;
use Unit\Database\DBconnect;
use Unit\Interface\ServiceProviderInterface;
use Unit\Service\Parameters;

class Update implements ServiceProviderInterface
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

        $result = $db->update("UPDATE occ_file SET status = 'false' Where id=:id", $set);

        //DB回傳資料是否正常
        if ($result) {
            $this->rsp->add("Result", $result);
            return true;
        } else {
            $this->error = $db->getError();
            return false;
        }
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
