<?php

namespace Mmal\OpenapiValidator\Error;

interface ErrorInterface
{
    public function hasErrors(): bool;

    public function __toString(): string;
}
