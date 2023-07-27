<?php

namespace Unit\Service;

use Exception;
use Unit\Error\ErrorEx;
use Unit\Interface\ServiceProviderInterface;

class ServiceVerify implements ServiceProviderInterface
{
    private ?Exception $error = null;
    private Parameters $rsp;

    function run(Parameters $params)
    {
        //print_r($_SERVER);

        //認證Token
        if (TOKEN !== ($_SERVER['HTTP_TOKEN'] ?? null)) {
            $this->error = new ErrorEx("Token is Error", -1);
            return null;
        }

        //限制POST
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            $this->error = new ErrorEx("Request Only POST", -2);
            return null;
        }

        //確認Class是否存在
        $strcls = str_replace(".", "\\", $params->get("Service"));
        if (class_exists($strcls)) {
            $cls = new $strcls;
            return $cls;
        } else {
            $this->error = new ErrorEx("Class is not befound", -3);
            return null;
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
