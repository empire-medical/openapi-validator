<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Property;

use Mmal\OpenapiValidator\SchemaInterface;

class ArrayProperty extends AbstractProperty
{
    /** @var SchemaInterface */
    private $items;

    /**
     */
    public function __construct(string $name, SchemaInterface $items, bool $nullable = false)
    {
        $this->name = $name;
        $this->items = $items;
        $this->nullable = $nullable;
    }

    public function toArray(): array
    {
        $types = ['array'];
        $types = $this->normalizeNullable($types);

        return [
            'type' => $types,
            'items' => $this->items->toArray(),
            'nullable' => $this->nullable
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
