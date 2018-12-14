<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Property;


class UnknownTypeProperty extends AbstractProperty
{

    /**
     */
    public function __construct(bool $nullable)
    {
        $this->nullable = $nullable;
    }

    public function toArray(): array
    {
        if ($this->nullable === true) {
            return [
                'nullable' => true,
            ];
        }

        return [];
    }

    public function applyDiscriminatorData($actualData)
    {
    }

}