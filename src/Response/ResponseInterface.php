<?php

namespace Mmal\OpenapiValidator\Response;

use Mmal\OpenapiValidator\SchemaInterface;

interface ResponseInterface
{
    public function doesSupportStatusCode(int $statusCode): bool;

    public function getSchema(string $contentType): SchemaInterface;

    public function toArray(): array;
}
