<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator;


class Operation implements OperationInterface
{
    /**
     * @var string
     */
    private $operationId;

    private $responses;

    public function getOperationId(): string
    {
        return $this->operationId;
    }
}