<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Exception\InvalidSchemaException;
use Mmal\OpenapiValidator\Exception\OperationNotFoundException;
use Mmal\OpenapiValidator\Reference\ReferenceResolver;
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
        if (!isset($this->operations[$operationId])) {
            throw new OperationNotFoundException(sprintf(
                'Operation %s not found',
                $operationId
            ));
        }

        return $this->operations[$operationId];
    }

    public static function fromArray(array $data)
    {
        $operations = [];
        $refResolver = new ReferenceResolver([]);
        if (isset($data['components']['schemas'])) {
            foreach ($data['components']['schemas'] as $name => $schema) {
                $refResolver->addRef('#/components/schemas/'.$name, $schema);
            }
        }
        if (isset($data['components']['responses'])) {
            foreach ($data['components']['responses'] as $name => $schema) {
                $refResolver->addRef('#/components/responses/'.$name, $schema);
            }
        }
        if (!isset($data['paths'])) {
            throw new InvalidSchemaException('Schema is missing paths, check schema is correct');
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
        if (!isset($operation['operationId'])) {
            throw new InvalidSchemaException('Missing operationId');
        }
        if (!isset($operation['responses'])) {
            throw new InvalidSchemaException(sprintf(
                'Operation %s must have responses',
                $operation['operationId']
            ));
        }
        foreach ($operation['responses'] as $statusCode => $response) {
            $responseSchemaRaw = null;
            if (isset($response['$ref'])) {
                $response = $referenceResolver->resolve($response['$ref']);
            }
            $allowNoResponse = $statusCode == 204;

            if (!isset($response['content']['application/json']['schema'])) {
                if ($allowNoResponse) {
                    $responseSchemaRaw = [];
                } else {
                    throw new InvalidSchemaException(
                        sprintf(
                            'Response %s for operation %s has no schema',
                            $statusCode,
                            $operation['operationId']
                        )
                    );
                }

            } else {
                $responseSchemaRaw = $response['content']['application/json']['schema'];
            }

            $responses[] = new Response(
                (int)$statusCode,
                self::getSchema(
                //@todo can be something else than application/json
                    $responseSchemaRaw,
                    $referenceResolver
                )
            );
        }

        return new Operation($operation['operationId'], $responses);
    }

    protected static function getSchema(array $data, ReferenceResolver $referenceResolver): SchemaInterface
    {
        return (new SchemaFactory($referenceResolver))->fromArray($data);
    }
}
