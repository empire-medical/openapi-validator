<?php
declare(strict_types=1);

namespace Mmal\OpenapiValidator\Property;

use Mmal\OpenapiValidator\SchemaInterface;

interface PropertyInterface extends SchemaInterface
{
    public function getName(): string;
}
