<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Exception\ResponseNotFoundException;
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

    public function getSchemaByResponse(int $statusCode, string $contentType): SchemaInterface
    {
        if (!isset($this->responses[$statusCode])) {
            throw new ResponseNotFoundException(sprintf(
                    'Response not found by %s status code and content type %s',
                    $statusCode,
                    $contentType)
            );
        }
        $response = $this->responses[$statusCode];

        return $response->getSchema($contentType);
    }

    public function getOperationId(): string
    {
        return $this->operationId;
    }
}
