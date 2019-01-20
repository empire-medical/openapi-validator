<?php

namespace Mmal\OpenapiValidator;

interface OperationInterface
{
    public function getOperationId(): string;

    public function getSchemaByResponse(int $statusCode, string $contentType): SchemaInterface;

    public function getUrlTemplate(): string;

    public function getMethod(): string;
}
