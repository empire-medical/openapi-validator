<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Property\AbstractProperty;
use Mmal\OpenapiValidator\Property\PropertyInterface;

class AnyOfSchema extends AbstractProperty
{
    /** @var array|PropertyInterface[] */
    protected $innerSchemas = [];

    /** @var string */
    private $discriminatorField;

    /** @var SchemaInterface[] */
    private $discriminatorMapping = [];

    /** @var mixed */
    private $discriminatorValue;

    /**
     */
    public function __construct(
        array $innerSchemas,
        string $name,
        $discriminatorField,
        array $discriminatorMapping
    ) {
        $this->innerSchemas = $innerSchemas;
        $this->name = $name;
        $this->discriminatorField = $discriminatorField;
        $this->discriminatorMapping = $discriminatorMapping;
    }

    public function toArray(): array
    {
        if (
            $this->discriminatorValue !== null
        ) {
            if (isset($this->discriminatorMapping[$this->discriminatorValue])) {
                $schema = $this->discriminatorMapping[$this->discriminatorValue];
                return $schema->toArray();
            } else {
                //@todo handle error
            }
        } else {
            return [
                $this->getKeyword() => array_map(function (SchemaInterface $schema) {
                    return $schema->toArray();
                }, $this->innerSchemas),
            ];
        }
    }

    public function applyDiscriminatorData($actualData)
    {
        if ($this->hasDiscriminatorMapping()) {
            if (isset($actualData[$this->discriminatorField])) {
                $this->discriminatorValue = $actualData[$this->discriminatorField];
            } else {
                //@todo handle error
            }
        }
    }

    /**
     * @return bool
     */
    protected function hasDiscriminatorMapping(): bool
    {
        return $this->discriminatorField !== null &&
            !empty($this->discriminatorMapping);
    }

    /**
     * @return string
     */
    protected function getKeyword(): string
    {
        return 'anyOf';
    }
}
