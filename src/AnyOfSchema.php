<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;


class AnyOfSchema extends AllOfSchema
{
    public function toArray(): array
    {
        return [
            'anyOf' => array_map(function (SchemaInterface $schema) {
                return $schema->toArray();
            }, $this->innerSchemas),
        ];
    }

}