<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Property;

class ScalarProperty extends AbstractProperty
{
    /** @var mixed */
    private $example;

    /** @var string */
    private $type;

    /** @var string|null */
    private $format;

    /**
     */
    public function __construct(string $type, string $name, bool $nullable = false, string $format = null)
    {
        $this->type = $type;
        $this->name = $name;
        $this->nullable = $nullable;
        $this->format = $format;
    }

    public function setExample($example)
    {
        $this->example = $example;
    }

    public function toArray(): array
    {
        $types = [$this->type];
        $types = $this->normalizeNullable($types);

        $data = [
            'type' => $types,
            'example' => $this->example,
            'nullable' => $this->nullable,
        ];

        if ($this->format) {
            $data['format'] = $this->format;
        }

        return $data;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function applyDiscriminatorData($actualData)
    {
    }
}
