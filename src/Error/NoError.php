<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Error;

class NoError implements ErrorInterface
{
    public function hasErrors(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return '';
    }
}
