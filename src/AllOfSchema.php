<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;


use Mmal\OpenapiValidator\Property\PropertyInterface;

class AllOfSchema implements PropertyInterface
{
    /** @var array|PropertyInterface[] */
    private $innerSchemas = [];

    /** @var string */
    private $name;

    public function getName(): string
    {
        return $this->name;
    }

    /**
     */
    public function __construct(array $innerSchemas, string $name = '')
    {
        $this->innerSchemas = $innerSchemas;
        $this->name = $name;
    }

    public function toArray(): array
    {
        return [
            'allOf' => array_map(function (SchemaInterface $schema) {
                return $schema->toArray();
            }, $this->innerSchemas),
        ];
    }
}