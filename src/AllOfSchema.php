<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Property\PropertyInterface;

class AllOfSchema implements PropertyInterface
{
    /** @var array|PropertyInterface[] */
    protected $innerSchemas = [];

    /** @var string */
    private $name;

    /** @var bool */
    private $nullable = false;

    public function getName(): string
    {
        return $this->name;
    }

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
            'allOf' => array_map(function (SchemaInterface $schema) {
                return $schema->toArray();
            }, $this->innerSchemas),
        ];
    }

    public function applyDiscriminatorData($actualData)
    {
    }

    public function makeNullable()
    {
        $this->nullable = true;
    }
}
