<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Property;


abstract class AbstractProperty implements PropertyInterface
{
    /** @var string */
    protected $name;

    /** @var bool */
    protected $nullable;

    public function makeNullable()
    {
        $this->nullable = true;
    }

    public function getName(): string
    {
        return $this->name ? $this->name : '';
    }

    protected function normalizeNullable(array $types): array
    {
        if ($this->nullable === true) {
            $types[] = 'null';
        }

        return $types;
    }
}