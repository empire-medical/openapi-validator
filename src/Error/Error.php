<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Error;

use Mmal\OpenapiValidator\SchemaInterface;

class Error implements ErrorInterface
{
    /** @var string */
    private $message;

    /** @var SchemaInterface */
    private $expectedSchema;

    /** @var array */
    private $actualData;

    /**
     */
    public function __construct(string $message, SchemaInterface $expectedSchema, $actualData)
    {
        $this->message = $message;
        $this->expectedSchema = $expectedSchema;
        $this->actualData = $actualData;
    }


    public function hasErrors(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return $this->message.PHP_EOL.
            'expected schema: '.PHP_EOL.
            json_encode($this->expectedSchema->toArray()).PHP_EOL.
            'given: '.PHP_EOL.
            json_encode($this->actualData).PHP_EOL;
    }
}
