<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Property\AbstractProperty;
use Mmal\OpenapiValidator\Property\PropertyInterface;

class OneOfSchema extends AbstractProperty
{
    /** @var array|PropertyInterface[] */
    protected $innerSchemas = [];

    /**
     */
    public function __construct(
        array $innerSchemas,
        string $name = '',
        bool $nullable = false
    ) {
        $this->innerSchemas = $innerSchemas;
        $this->name = $name;
        $this->nullable = $nullable;
        if($this->nullable === true) {
            foreach ($this->innerSchemas as $schema) {
                $schema->makeNullable();
            }
        }
    }

    public function toArray(): array
    {
        return [
            'oneOf' => array_map(function (SchemaInterface $schema) {
                return $schema->toArray();
            }, $this->innerSchemas),
        ];
    }

    public function applyDiscriminatorData($actualData)
    {
    }
}
