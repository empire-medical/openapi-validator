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
        foreach ($data['paths'] as $urlTemplate => $methods) {
            foreach ($methods as $method => $operation) {
                $operations[] = self::makeOperation($operation);
            }
        }
        return new self($operations);
    }

    /**
     * @param array $operation
     * @return Operation
     */
    protected static function makeOperation(array $operation): Operation
    {
        $responses = [];
        foreach ($operation['responses'] as $statusCode => $response) {
            $responses[] = new Response((int)$statusCode, self::getSchema($response['content']['application/json']['schema']));
        }
        return new Operation($operation['operationId'], $responses);
    }

    protected static function getSchema(array $data): SchemaInterface
    {
        return (new SchemaFactory())->fromArray($data);
    }
}
