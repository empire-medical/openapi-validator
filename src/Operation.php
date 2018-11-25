<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Response\ResponseInterface;

class Operation implements OperationInterface
{
    /**
     * @var string
     */
    private $operationId;

    /** @var array|ResponseInterface[] */
    private $responses;

    /**
     */
    public function __construct(string $operationId, array $responses)
    {
        $this->operationId = $operationId;
        foreach ($responses as $respons) {
            $this->responses[$respons->getStatusCode()] = $respons;
        }
    }

    public function getSchemaByResponse(int $statusCode): SchemaInterface
    {
        $response = $this->responses[$statusCode];

        return $response->getSchema();
    }

    public function getOperationId(): string
    {
        return $this->operationId;
    }
}
