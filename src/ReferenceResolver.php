<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;


class ReferenceResolver
{
    /** @var array */
    private $schemas = [];

    /**
     */
    public function __construct(array $schemas)
    {
        foreach ($schemas as $ref => $schema) {
            $this->schemas[$ref] = $schema;
        }
    }

    public function addRef(string $ref, array $schema)
    {
        $this->schemas[$ref] = $schema;
    }

    public function resolve(string $ref): array
    {
        return $this->schemas[$ref];
    }
}