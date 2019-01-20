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
                $operation->getMethod() === $this->requestMethod;
        });

        if (empty($operationsMatchingUrlTemplate)) {
            throw new UnableToFindOperationException('');
        }

        return array_shift($operationsMatchingUrlTemplate);
    }
}
