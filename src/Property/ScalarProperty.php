<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Property;

class ScalarProperty extends AbstractProperty
{
    /** @var mixed */
    private $example;

    /** @var string */
    private $type;

    /**
     */
    public function __construct(string $type, string $name, bool $nullable = false)
    {
        $this->type = $type;
        $this->name = $name;
        $this->nullable = $nullable;
    }

    public function setExample($example)
    {
        $this->example = $example;
    }

    public function toArray(): array
    {
        $types = [$this->type];
        $types = $this->normalizeNullable($types);

        return [
            'type' => $types,
            'example' => $this->example,
            'nullable' => $this->nullable,
        ];
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function applyDiscriminatorData($actualData)
    {
    }
}
