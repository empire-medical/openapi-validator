<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Error;

use Mmal\OpenapiValidator\SchemaInterface;

class Error implements ErrorInterface
{
    /** @var string */
    private $message;

    /** @var string */
    private $property;

    /** @var string */
    private $constraint;

    /** @var string */
    private $operation;

    /**
     */
    public function __construct(string $message, string $property, string $constraint)
    {
        $this->message = $message;
        $this->property = $property;
        $this->constraint = $constraint;
    }


    public function hasErrors(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return $this->message.PHP_EOL.
            'property: '. $this->property.PHP_EOL.
            'constraint: ' . $this->constraint.PHP_EOL;
    }

    public function getOperation(): string
    {
        return $this->operation;
    }

    public function setOperation(string $operation)
    {
        $this->operation = $operation;
    }
}
