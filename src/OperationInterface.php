<?php

namespace Mmal\OpenapiValidator;

interface OperationInterface
{
    public function getOperationId(): string;

    public function getSchemaByResponse(int $statusCode): SchemaInterface;
}
