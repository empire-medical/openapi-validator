<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Exception\InvalidSchemaException;
use Mmal\OpenapiValidator\Exception\OperationNotFoundException;
use Mmal\OpenapiValidator\Reference\ReferenceResolver;
use Mmal\OpenapiValidator\Response\DefaultResponse;
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

    public function getOperations()
    {
        return $this->operations;
    }

    /**
     * @throws InvalidSchemaException
     */
    public static function fromArray(array $data): Spec
    {
        $refResolver = ReferenceResolver::fromData($data);
        $operations = [];

        if (!isset($data['paths'])) {
            throw new InvalidSchemaException('Schema is missing paths, check schema is correct');
        }
        foreach ($data['paths'] as $urlTemplate => $methods) {
            foreach ($methods as $method => $operation) {
                //@todo write tests
                if (
                !in_array(
                    strtolower($method),
                    ['get', 'post', 'put', 'delete', 'options', 'head', 'patch', 'trace']
                )
                ) {
                    continue;
                }
                $operations[] = self::makeOperation(
                    $urlTemplate,
                    $method,
                    $operation,
                    $refResolver
                );
            }
        }

        return new self($operations);
    }

    protected static function makeOperation(
        string $urlTemplate,
        string $method,
        array $operation,
        ReferenceResolver $referenceResolver
    ): Operation {
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

        $defaultResponse = null;
        foreach ($operation['responses'] as $statusCode => $response) {
            $responseSchemaRaw = null;
            if (isset($response['$ref'])) {
                $response = $referenceResolver->resolve($response['$ref']);
            }

            if (!isset($response['content']) || empty($response['content'])) {
                //@todo validate empty actually empty + tests - raise error if response is not empty
                $allowNoResponse = in_array($statusCode, [201, 202, 204, 301, 303, 304]);
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
                $responseSchemaRaw = $response['content'];
            }

            $schemas = [];
            foreach ($responseSchemaRaw as $contentType => $rawSchema) {
                if (!isset($rawSchema['schema'])) {
                    throw new InvalidSchemaException(
                        sprintf(
                            'Response %s for operation %s has no schema',
                            $statusCode,
                            $operation['operationId']
                        )
                    );
                }
                $schemas[$contentType] = self::getSchema(
                    $rawSchema['schema'],
                    $referenceResolver
                );
            }

            if ($statusCode == 'default') {
                $defaultResponse = new DefaultResponse($schemas);
            } else {
                $responses[] = new Response(
                    $statusCode,
                    $schemas
                );
            }
        }

        return new Operation($urlTemplate, $method, $operation['operationId'], $responses, $defaultResponse);
    }

    protected static function getSchema(array $data, ReferenceResolver $referenceResolver): SchemaInterface
    {
        return (new SchemaFactory($referenceResolver))->fromArray($data);
    }
}
