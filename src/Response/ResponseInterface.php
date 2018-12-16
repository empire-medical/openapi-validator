<?php

namespace Mmal\OpenapiValidator\Response;

use Mmal\OpenapiValidator\SchemaInterface;

interface ResponseInterface
{
    public function getStatusCode(): int;

    public function getSchema(string $contentType): SchemaInterface;
}
