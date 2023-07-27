<?php

namespace Unit\Error;

use Exception;

class ErrorEx extends Exception
{
    function __construct(String $message = "", int $code = 0, String $file = "", String $line = "")
    {
        $this->message = $message;
        $this->code = $code;
        $this->file = $file;
        $this->line = $line;
    }
}
