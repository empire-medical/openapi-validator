<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Property\PropertyInterface;

class ObjectSchema implements PropertyInterface
{
    /** @var array|PropertyInterface[] */
    private $properties = [];

    /** @var array|string[] */
    private $required = [];

    /** @var string */
    private $name;

    /** @var bool */
    private $nullable;

    public function getName(): string
    {
        return $this->name;
    }

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
        if ($this->nullable === true) {
            $types[] = 'null';
        }

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

    public function makeNullable()
    {
        $this->nullable = true;
    }
}
