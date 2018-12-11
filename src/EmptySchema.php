<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;


class EmptySchema implements SchemaInterface
{
    public function toArray(): array
    {
        return [];
    }

    public function applyDiscriminatorData($actualData)
    {
    }

}