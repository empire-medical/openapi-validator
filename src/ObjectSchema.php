<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Property\AbstractProperty;
use Mmal\OpenapiValidator\Property\PropertyInterface;

class ObjectSchema extends AbstractProperty
{
    /** @var array|PropertyInterface[] */
    private $properties = [];

    /** @var array|string[] */
    private $required = [];

    /**
     */
    public function __construct(array $properties, array $required, string $name = '', bool $nullable)
    {
        $this->properties = $properties;
        $this->required = $required;
        $this->name = $name;
        $this->nullable = $nullable;
    }

    public function toArray(): array
    {
        $properties = [];
        foreach ($this->properties as $property) {
            $properties[$property->getName()] = $property->toArray();
        }
        $types = ['object'];
        $types = $this->normalizeNullable($types);

        return [
            'type' => $types,
            'properties' => $properties,
            'required' => $this->required,
        ];
    }

    public function applyDiscriminatorData($actualData)
    {
        foreach ($this->properties as $property) {
            if (isset($actualData[$property->getName()])) {
                $property->applyDiscriminatorData($actualData[$property->getName()]);
            }
        }
    }
}
