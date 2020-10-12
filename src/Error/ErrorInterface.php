<?php

namespace Mmal\OpenapiValidator\Error;

interface ErrorInterface
{
    public function hasErrors(): bool;

    public function __toString(): string;

    public function getOperation(): string ;

    public function setOperation(string $operation);
}
