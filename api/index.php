<?php

use Unit\Database\DBconnect;
use Unit\Service\Parameters;
use Unit\Service\ServiceVerify;

require __DIR__ . "/vendor/autoload.php";   //Composer
require_once "config.php";  //設定檔

$req = new Parameters;
$rsp = new Parameters;

try {
    $req->parse(file_get_contents("php://input"));  //解析Client端需求
    $rsp->setReqParameter($req);    //正常模式開啟debug

    $VerifySrv = new ServiceVerify; //驗證Client端資料正確性
    $cls = $VerifySrv->run($req);   //執行驗證後的類別

    if (!is_null($cls)) {
        try {
            $rs = $cls->run($req);
            if ($rs) {
                $res = $cls->getResponse();
                $rsp->add("Status", "0");
                $rsp->add("Response", $res->toArray());
            } else {
                $rsp->add("Status", "-4");  //DB資料來回中有錯誤
                $err = $cls->getError();
                $rsp->AddDebugInfo($err);
            }
        } catch (Exception $ex) {
            $rsp->add("Status", "-3");
            $rsp->AddDebugInfo($ex);
        }
    } else {
        $rsp->add("Status", "-2");  //驗證失敗
        $err = $VerifySrv->getError();
        $rsp->AddDebugInfo($err);
    }
} catch (Exception $ex) {
    $rsp->add("Status", "-1");  //json解析失敗
    $rsp->AddDebugInfo($ex);
}

//結束關閉DB
$db = DBconnect::getinstance();
$db->close();

$rsp = $rsp->toJson();
print_r($rsp);
