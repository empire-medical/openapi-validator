<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Property\StringProperty;

class SchemaFactory
{
    public function fromArray(array $data, string $name = null)
    {
        if ($data['type'] == 'object') {
            $properties = [];
            foreach ($data['properties'] as $nameOfProperty => $property) {
                $properties[] = $this->fromArray($property, $nameOfProperty);
            }

            return new ObjectSchema($properties, $data['required'] ?? []);
        }
        if ($data['type'] == 'string') {
            return new StringProperty($name, $data['nullable'] ?? false);
        }
    }
}
