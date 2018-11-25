<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Response;

use Mmal\OpenapiValidator\SchemaInterface;

class Response implements ResponseInterface
{
    /** @var int */
    private $statusCode;

    /** @var SchemaInterface */
    private $schema;

    /**
     */
    public function __construct(int $statusCode, SchemaInterface $schema)
    {
        $this->statusCode = $statusCode;
        $this->schema = $schema;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getSchema(): SchemaInterface
    {
        return $this->schema;
    }
}
