<?php

namespace Unit\Interface;

use Exception;
use Unit\Service\Parameters;

interface ServiceProviderInterface
{
    function run(Parameters $params);

    function getResponse(): Parameters;

    function getError(): Exception;
}
