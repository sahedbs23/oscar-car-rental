<?php

namespace App\Contracts;

interface ResponseInterface
{
    /**
     * Sends the response to the output buffer.
     *
     * @param bool $send_headers Whether or not to send the defined HTTP headers
     * @return  void
     */
    public function send(bool $send_headers = false): void;
}