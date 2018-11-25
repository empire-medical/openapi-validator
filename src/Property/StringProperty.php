<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Property;

class StringProperty implements PropertyInterface
{
    /** @var string */
    private $name;

    /** @var mixed */
    private $example;

    /** @var bool */
    private $nullable = false;

    /**
     */
    public function __construct(string $name, bool $nullable = false)
    {
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
        return [
            'type' => 'string',
            'example' => $this->example,
            'nullable' => $this->nullable
        ];
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
}
