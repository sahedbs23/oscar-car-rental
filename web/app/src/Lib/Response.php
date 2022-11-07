<?php

namespace App\Lib;

use App\Contracts\ResponseInterface;

class Response implements ResponseInterface
{
    public const HTTP_OK = 200;

    public const HTTP_CREATED = 201;

    public const HTTP_NOT_FOUND = 404;

    public const BAD_REQUEST = 400;

    public const METHOD_NOT_ALLOWED = 405;

    public const HTTP_SERVER_ERROR = 500;

    /**
     * @var string[]
     */
    public static array $statusTexts = [
        200 => 'OK',
        201 => 'Created',
        400 => 'Bad Request',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error'
    ];

    private array $headers = [];

    private mixed $content;

    private int $statusCode;

    private float $version;

    /**
     * Response Content
     *
     * @param string|null $content
     * @param int $status HTTP status code.
     * @param array $headers HTTP headers.
     */
    public function __construct(string $content = null, int $status = 200, array $headers = [])
    {
        $this->setHeaders($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.0');
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string|null
     */
    public function getContent(): string|null
    {
        return $this->content;
    }

    /**
     * @return float
     */
    public function getProtocolVersion(): float
    {
        return $this->version;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param float $protocolVersion
     * @return  Response
     */
    public function setProtocolVersion(float $protocolVersion): Response
    {
        $this->version = $protocolVersion;
        return $this;
    }

    /**
     * @param mixed|string $content
     * @return Response
     */
    public function setContent(mixed $content): Response
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param int $statusCode
     * @return Response
     */
    public function setStatusCode(int $statusCode): Response
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Adds multiple headers to the queue
     *
     * @param array $headers Assoc array with header name / value combinations
     * @param string|bool $replace Whether to replace existing value for the header
     * @return  Response
     */
    public function setHeaders(array $headers, bool $replace = true): self
    {
        foreach ($headers as $key => $value) {
            $this->setHeader($key, $value, $replace);
        }

        return $this;
    }

    /**
     * Adds a header
     *
     * @param string $name The header name
     * @param string $value The header value
     * @param string|bool $replace Whether to replace existing value for the header
     * @return  Response
     */
    public function setHeader(string $name, string $value, bool $replace = true): self
    {
        if ($replace) {
            $this->headers[$name] = $value;
        } else {
            $this->headers[] = array($name, $value);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function send(bool $sendHeaders = true): void
    {
        $content = $this->__toString();

        if ($sendHeaders) {
            $this->sendHeaders();
        }

        if ($this->content !== null) {
            echo $content;
        }
    }

    /**
     * Sends the headers if they haven't already been sent.  Returns whether
     * they were sent or not.
     *
     * @return  bool
     */
    public function sendHeaders(): bool
    {
        if (!headers_sent()) {
            header($this->stringifyStatus());
            foreach ($this->headers as $name => $value) {
                // Create the header
                $value = $this->stringifyHeader($name, $value);

                // Send it
                if ($value) {
                    header($value, true);
                }
            }
            return true;
        }
        return false;
    }

    /**
     * @param $key
     * @param $value
     * @return string|null
     */
    public function stringifyHeader($key, $value):string|null
    {
        is_string($key) and $value = "{$key}: {$value}";

        return $value ?? null;
    }

    /**
     * @return string
     */
    public function stringifyStatus():string
    {
        return 'HTTP/' . $this->getProtocolVersion() . ' ' . $this->getStatusCode(
            ) . ' ' . static::$statusTexts[$this->getStatusCode()];
    }

    /**
     * object to string conversion.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->content;
    }
}