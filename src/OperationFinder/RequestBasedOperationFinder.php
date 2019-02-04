<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\OperationFinder;

use Mmal\OpenapiValidator\Operation;
use Mmal\OpenapiValidator\OperationInterface;

class RequestBasedOperationFinder implements OperationFinder
{
    /** @var string */
    private $requestUrl;

    /** @var string */
    private $requestMethod;

    /** @var OperationInterface[]|array */
    private $operations;

    /**
     */
    public function __construct(string $requestUrl, string $requestMethod, $operations)
    {
        $this->requestUrl = $requestUrl;
        $this->requestMethod = $requestMethod;
        $this->operations = $operations;
    }

    /**
     * @return OperationInterface
     * @throws UnableToFindOperationException
     */
    public function find(): OperationInterface
    {
        $operationsMatchingUrlTemplate = array_filter($this->operations, function (Operation $operation) {
            $operationUrlTemplate = new UrlTemplate($operation->getUrlTemplate());

            return $operationUrlTemplate->matches($this->requestUrl) &&
                strtolower($operation->getMethod()) === strtolower($this->requestMethod);
        });

        if (empty($operationsMatchingUrlTemplate)) {
            throw new UnableToFindOperationException(sprintf(
                'Operation not found by %s %s, known operations: %s',
                $this->requestMethod,
                $this->requestUrl,
                implode(PHP_EOL, array_map(function (Operation $operation) {
                    $data = [
                        'urlTemplate' => $operation->getUrlTemplate(),
                        'method' => $operation->getMethod(),
                        'id' => $operation->getOperationId(),
                    ];

                    return json_encode($data).PHP_EOL;
                }, $this->operations))
            ));
        }

        return array_shift($operationsMatchingUrlTemplate);
    }
}
