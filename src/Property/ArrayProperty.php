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

    /** @var bool */
    private $nullable;

    /**
     */
    public function __construct(string $name, SchemaInterface $items, bool $nullable = false)
    {
        $this->name = $name;
        $this->items = $items;
        $this->nullable = $nullable;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        $types = ['array'];
        if ($this->nullable === true) {
            $types[] = 'null';
        }
        return [
            'type' => $types,
            'items' => $this->items->toArray()
        ];
    }

    public function applyDiscriminatorData($actualData)
    {
        $singleItem = current($actualData);
        if(isset($singleItem[$this->name])) {
            $this->items->applyDiscriminatorData(current($singleItem[$this->name]));
        }
    }
}
