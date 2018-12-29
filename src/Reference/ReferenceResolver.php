<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Reference;

use Mmal\OpenapiValidator\Exception\MissingReferenceException;

class ReferenceResolver
{
    /** @var array */
    private $schemas = [];

    private $refCounters = [];

    /**
     */
    public function __construct(array $schemas)
    {
        foreach ($schemas as $ref => $schema) {
            $this->schemas[$ref] = $schema;
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function addRef(string $ref, array $schema)
    {
        if (isset($this->schemas[$ref])) {
            throw new \InvalidArgumentException(sprintf('Reference already registered %s', $ref));
        }
        $this->schemas[$ref] = $schema;
    }

    /**
     * @throws MissingReferenceException
     */
    public function resolve(string $ref): array
    {
        if (isset($this->refCounters[$ref]) && $this->refCounters[$ref] > 100) {
            return [];
        }

        if (!array_key_exists($ref, $this->schemas)) {
            throw MissingReferenceException::fromRef($ref);
        }

        if (!isset($this->refCounters[$ref])) {
            $this->refCounters[$ref] = 0;
        }
        $this->refCounters[$ref]++;

        return $this->schemas[$ref];
    }

    static public function fromData($data): ReferenceResolver
    {
        $refResolver = new self([]);
        $componentFields = ['schemas', 'responses'];

        foreach($componentFields as $field) {
            if (isset($data['components'][$field])) {
                foreach ($data['components'][$field] as $name => $schema) {
                    $refResolver->addRef('#/components/'.$field.'/'.$name, $schema);
                }
            }
        }

        return $refResolver;
    }
}
