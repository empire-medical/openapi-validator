<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\OperationFinder;


use Mmal\OpenapiValidator\OperationInterface;

class IdBasedOperationFinder implements OperationFinder
{
    /**
     * @var OperationInterface[]|array
     */
    private $operations;

    /** @var string */
    private $operationId;

    /**
     */
    public function __construct($operations, string $operationId)
    {
        $this->operations = $operations;
        $this->operationId = $operationId;
    }

    public function find(): OperationInterface
    {
        if (!isset($this->operations[$this->operationId])) {
            throw new UnableToFindOperationException(sprintf(
                'Operation %s not found',
                $this->operationId
            ));
        }

        return $this->operations[$this->operationId];
    }

}