<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Property\ArrayProperty;
use Mmal\OpenapiValidator\Property\ScalarProperty;
use Mmal\OpenapiValidator\Reference\ReferenceResolver;

class SchemaFactory
{
    /** @var ReferenceResolver */
    private $referenceResolver;

    /**
     */
    public function __construct(ReferenceResolver $referenceResolver)
    {
        $this->referenceResolver = $referenceResolver;
    }


    public function fromArray(array $data, string $name = null)
    {
        if (isset($data['allOf'])) {
            $schemas = [];
            foreach ($data['allOf'] as $row) {
                $schemas[] = $this->fromArray($row);
            }

            return new AllOfSchema($schemas, $name ?? '');
        }
        if (isset($data['anyOf'])) {
            $schemas = [];
            foreach ($data['anyOf'] as $row) {
                $schemas[] = $this->fromArray($row);
            }

            return new AnyOfSchema($schemas, $name ?? '');
        }
        if (isset($data['$ref'])) {
            $data = $this->referenceResolver->resolve($data['$ref']);
        }
        if ($data['type'] === 'object') {
            $properties = [];
            foreach ($data['properties'] as $nameOfProperty => $property) {
                $properties[] = $this->fromArray($property, $nameOfProperty);
            }

            return new ObjectSchema($properties, $data['required'] ?? [], $name ?? '', $data['nullable'] ?? false);
        }
        if ($data['type'] === 'array') {
            return new ArrayProperty($name ?? '', $this->fromArray($data['items']));
        }

        return new ScalarProperty($data['type'], $name ?? '', $data['nullable'] ?? false);
    }
}
