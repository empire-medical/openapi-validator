<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Property\ArrayProperty;
use Mmal\OpenapiValidator\Property\ScalarProperty;

class SchemaFactory
{
    public function fromArray(array $data, string $name = null)
    {
        if ($data['type'] === 'object') {
            $properties = [];
            foreach ($data['properties'] as $nameOfProperty => $property) {
                $properties[] = $this->fromArray($property, $nameOfProperty);
            }

            return new ObjectSchema($properties, $data['required'] ?? [], $name ?? '');
        }
        if ($data['type'] === 'array') {

            return new ArrayProperty($name ?? '', $this->fromArray($data['items']));
        }

        return new ScalarProperty($data['type'], $name ?? '', $data['nullable'] ?? false);
    }
}
