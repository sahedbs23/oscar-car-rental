<?php

namespace App\Exceptions;

use App\Lib\Response;
use \Throwable;

class ValidationExceptions extends \Exception
{
    /**
     * @param array $messages
     * @param $code
     * @param Throwable|null $previous
     * @throws \JsonException
     */
    public function __construct(array $messages, $code = 404, ?Throwable $previous = null)
    {
        $message = json_encode($messages, JSON_THROW_ON_ERROR);
        parent::__construct($message, $code, $previous);
    }
}