<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Response;

use Mmal\OpenapiValidator\EmptySchema;
use Mmal\OpenapiValidator\SchemaInterface;

class Response implements ResponseInterface
{
    /** @var int */
    private $statusCode;

    /** @var SchemaInterface[]|array */
    private $schemas;

    /**
     */
    public function __construct(int $statusCode, array $schemas)
    {
        $this->statusCode = $statusCode;
        $this->schemas = $schemas;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getSchema(string $contentType): SchemaInterface
    {
        if (!isset($this->schemas[$contentType])) {
            if ($this->statusCode == 204) {
                return new EmptySchema();
            }
            throw new \InvalidArgumentException(sprintf('No defined schema for content-type %s', $contentType));
        }

        return $this->schemas[$contentType];
    }
}
