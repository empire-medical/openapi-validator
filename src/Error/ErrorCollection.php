<?php
declare(strict_types=1);

namespace Mmal\OpenapiValidator\Error;

class ErrorCollection implements ErrorInterface
{
    /** @var array|ErrorInterface[] */
    private $errors = [];

    /** @var string */
    private $operation;

    public function getOperation(): string
    {
        return $this->operation;
    }

    public function setOperation(string $operation)
    {
        $this->operation = $operation;
    }

    public function addError(ErrorInterface $error)
    {
        $this->errors[] = $error;
    }

    public function __toString(): string
    {
        return implode(PHP_EOL, $this->errors);
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }
}
