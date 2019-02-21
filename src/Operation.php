<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\Exception\ResponseNotFoundException;
use Mmal\OpenapiValidator\Response\DefaultResponse;
use Mmal\OpenapiValidator\Response\Response;
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

    /** @var DefaultResponse */
    private $defaultResponse;

    /**
     */
    public function __construct(
        string $urlTemplate,
        string $method,
        string $operationId,
        array $responses,
        ResponseInterface $defualtResponse = null
    ) {
        $this->urlTemplate = $urlTemplate;
        $this->method = $method;
        $this->operationId = $operationId;
        foreach ($responses as $respons) {
            $this->responses[] = $respons;
        }
        $this->defaultResponse = $defualtResponse;
    }

    public function getSchemaByResponse(int $statusCode, string $contentType): SchemaInterface
    {
        $responseForStatusCode = array_filter($this->responses, function (Response $response) use ($statusCode) {
            return $response->doesSupportStatusCode($statusCode);
        });
        if (empty($responseForStatusCode)) {
            if ($this->defaultResponse instanceof DefaultResponse) {
                return $this->defaultResponse->getSchema($contentType);
            } else {
                throw new ResponseNotFoundException(
                    sprintf(
                        'Response not found by %s status code and content type %s, known responses for operation %s: %s',
                        $statusCode,
                        $contentType,
                        $this->operationId,
                        json_encode(array_map(function (ResponseInterface $response) {
                            return $response->toArray();
                        }, $this->responses))
                    )
                );
            }
        }
        $response = array_shift($responseForStatusCode);

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
