<?php

namespace App\Contracts;

interface ResponseInterface
{
    /**
     * Sends the response to the output buffer.
     *
     * @param bool $send_headers Whether to send the defined HTTP headers
     * @return  void
     */
    public function send(bool $send_headers = true): void;
}