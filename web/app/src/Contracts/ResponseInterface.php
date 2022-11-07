<?php

namespace App\Contracts;

interface ResponseInterface
{
    /**
     * Sends the response to the output buffer.
     *
     * @param bool $sendHeaders Whether to send the defined HTTP headers
     * @return  void
     */
    public function send(bool $sendHeaders = true): void;
}