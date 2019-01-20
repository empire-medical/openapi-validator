<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Property\AbstractProperty;
use Mmal\OpenapiValidator\Property\PropertyInterface;

class OneOfSchema extends AnyOfSchema
{
    protected function getKeyword(): string
    {
        return 'oneOf';
    }
}
