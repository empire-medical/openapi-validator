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

    /** @var string */
    private $urlTemplate;

    /** @var string */
    private $method;

    /**
     */
    public function __construct(
        string $urlTemplate,
        string $method,
        string $operationId,
        array $responses
    ) {
        $this->urlTemplate = $urlTemplate;
        $this->method = $method;
        $this->operationId = $operationId;
        foreach ($responses as $respons) {
            $this->responses[$respons->getStatusCode()] = $respons;
        }
    }

    public function getSchemaByResponse(int $statusCode, string $contentType): SchemaInterface
    {
        if (!isset($this->responses[$statusCode])) {
            throw new ResponseNotFoundException(
                sprintf(
                    'Response not found by %s status code and content type %s',
                    $statusCode,
                    $contentType
            )
            );
        }
        $response = $this->responses[$statusCode];

        return $response->getSchema($contentType);
    }

    public function getOperationId(): string
    {
        return $this->operationId;
    }

    public function getUrlTemplate(): string
    {
        return $this->urlTemplate;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
