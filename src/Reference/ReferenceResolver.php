<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Reference;


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

    /**
     * @throws MissingReferenceException
     */
    public function addRef(string $ref, array $schema)
    {
        $this->schemas[$ref] = $schema;
    }

    public function resolve(string $ref): array
    {
        if (!array_key_exists($ref, $this->schemas)) {
            throw MissingReferenceException::fromRef($ref);
        }
        return $this->schemas[$ref];
    }
}