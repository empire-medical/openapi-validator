<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Property\PropertyInterface;

class ObjectSchema implements SchemaInterface
{
    /** @var array|PropertyInterface[] */
    private $properties = [];

    /** @var array|string[]  */
    private $required = [];

    /**
     */
    public function __construct(array $properties, array $required)
    {
        $this->properties = $properties;
        $this->required = $required;
    }

    public function toArray(): array
    {
        $properties = [];
        foreach ($this->properties as $property) {
            $properties[$property->getName()] = $property->toArray();
        }

        return [
            'type' => 'object',
            'properties' => $properties,
            'required' => $this->required
        ];
    }
}
