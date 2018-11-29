<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Property;


use Mmal\OpenapiValidator\SchemaInterface;

class ArrayProperty implements PropertyInterface
{
    /** @var string */
    private $name;

    /** @var SchemaInterface */
    private $items;

    /**
     */
    public function __construct(string $name, SchemaInterface $items)
    {
        $this->name = $name;
        $this->items = $items;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'items' => $this->items->toArray()
        ];
    }

}