<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Property\ArrayProperty;
use Mmal\OpenapiValidator\Property\ScalarProperty;
use Mmal\OpenapiValidator\Property\UnknownTypeProperty;
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
        if (empty($data)) {
            return new EmptySchema();
        }
        if (isset($data['allOf'])) {
            $schemas = [];
            foreach ($data['allOf'] as $row) {
                $schemas[] = $this->fromArray($row);
            }

            return new AllOfSchema(
                $schemas,
                $name ?? '',
                $data['nullable'] ?? false
            );
        }
        if (isset($data['oneOf'])) {
            $schemas = [];
            foreach ($data['oneOf'] as $row) {
                $schemas[] = $this->fromArray($row);
            }

            $discriminatorMapping = $data['discriminator']['mapping'] ?? [];
            $discriminatorMappingDereferenced = [];
            foreach ($discriminatorMapping as $key => $ref) {
                $discriminatorMappingDereferenced[$key] = $this->fromArray($this->referenceResolver->resolve($ref));
            }

            return new OneOfSchema(
                $schemas,
                $name ?? '',
                $data['discriminator']['propertyName'] ?? null,
                $discriminatorMappingDereferenced
            );
        }
        if (isset($data['anyOf'])) {
            $schemas = [];
            foreach ($data['anyOf'] as $row) {
                $schemas[] = $this->fromArray($row);
            }

            $discriminatorMapping = $data['discriminator']['mapping'] ?? [];
            $discriminatorMappingDereferenced = [];
            foreach ($discriminatorMapping as $key => $ref) {
                $discriminatorMappingDereferenced[$key] = $this->fromArray($this->referenceResolver->resolve($ref));
            }

            return new AnyOfSchema(
                $schemas,
                $name ?? '',
                $data['discriminator']['propertyName'] ?? null,
                $discriminatorMappingDereferenced
            );
        }
        if (isset($data['$ref'])) {
            $data = $this->referenceResolver->resolve($data['$ref']);
        }
        if (isset($data['type'])) {
            if ($data['type'] === 'object') {
                $properties = [];
                if (isset($data['properties'])) {
                    foreach ($data['properties'] as $nameOfProperty => $property) {
                        $properties[] = $this->fromArray($property, $nameOfProperty);
                    }
                }

                return new ObjectSchema($properties, $data['required'] ?? [], $name ?? '', $data['nullable'] ?? false);
            }
            if ($data['type'] === 'array') {
                return new ArrayProperty($name ?? '', $this->fromArray($data['items']), $data['nullable'] ?? false);
            }

            return new ScalarProperty($data['type'], $name ?? '', $data['nullable'] ?? false);
        }

        return new UnknownTypeProperty($data['nullable'] ?? false);
    }
}
