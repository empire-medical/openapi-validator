<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Property\PropertyInterface;

class ObjectSchema implements PropertyInterface
{
    /** @var array|PropertyInterface[] */
    private $properties = [];

    /** @var array|string[]  */
    private $required = [];

    /** @var string */
    private $name;

    public function getName(): string
    {
        return $this->name;
    }

    /**
     */
    public function __construct(array $properties, array $required, string $name = '')
    {
        $this->properties = $properties;
        $this->required = $required;
        $this->name = $name;
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
