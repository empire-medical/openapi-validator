<?php
declare(strict_types=1);

namespace Mmal\OpenapiValidator\Property;

interface PropertyInterface
{
    public function getName(): string;

    public function toArray(): array;
}