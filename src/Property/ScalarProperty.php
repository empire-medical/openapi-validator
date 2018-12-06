<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Property;

class ScalarProperty implements PropertyInterface
{
    /** @var string */
    private $name;

    /** @var mixed */
    private $example;

    /** @var string */
    private $type;

    /** @var bool */
    private $nullable = false;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        $types = [$this->type];
        if ($this->nullable === true) {
            $types[] = 'null';
        }

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
}
