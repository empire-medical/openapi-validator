<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Response\Response;

class Spec
{
    /** @var OperationInterface[]|array */
    private $operations = [];

    /**
     * @param array|OperationInterface[] $operations
     */
    public function __construct(array $operations)
    {
        foreach ($operations as $operation) {
            $this->operations[$operation->getOperationId()] = $operation;
        }
    }

    public function getOperationById(string $operationId): OperationInterface
    {
        return $this->operations[$operationId];
    }

    public static function fromArray(array $data)
    {
        $operations = [];
        $refResolver = new ReferenceResolver([]);
        if(isset($data['components']['schemas'])) {
            foreach($data['components']['schemas'] as $name => $schema) {
                $refResolver->addRef('#/components/schemas/' . $name, $schema);
            }
        }
        foreach ($data['paths'] as $urlTemplate => $methods) {
            foreach ($methods as $method => $operation) {
                $operations[] = self::makeOperation($operation, $refResolver);
            }
        }
        return new self($operations);
    }

    /**
     * @param array $operation
     * @return Operation
     */
    protected static function makeOperation(array $operation, ReferenceResolver $referenceResolver): Operation
    {
        $responses = [];
        foreach ($operation['responses'] as $statusCode => $response) {
            $responses[] = new Response((int)$statusCode, self::getSchema($response['content']['application/json']['schema'], $referenceResolver));
        }
        return new Operation($operation['operationId'], $responses);
    }

    protected static function getSchema(array $data, ReferenceResolver $referenceResolver): SchemaInterface
    {
        return (new SchemaFactory($referenceResolver))->fromArray($data);
    }
}
