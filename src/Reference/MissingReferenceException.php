<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Reference;

class MissingReferenceException extends \Exception
{
    public static function fromRef(string $missingRef)
    {
        return new self("Could not resolve reference $missingRef , check Your schema!");
    }
}
