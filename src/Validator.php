<?php
declare(strict_types=1);

namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\DataValidator\DataValidatorInterface;
use Mmal\OpenapiValidator\DataValidator\JsonGuardDataValidator;
use Mmal\OpenapiValidator\OperationFinder\IdBasedOperationFinder;
use Mmal\OpenapiValidator\OperationFinder\OperationFinder;
use Mmal\OpenapiValidator\OperationFinder\RequestBasedOperationFinder;

class Validator
{
    /** @var Spec */
    private $spec;

    public function __construct(array $parsedSchema)
    {
        $this->spec = Spec::fromArray($parsedSchema);
    }


    public function validate(
        string $operationId,
        int $statusCode,
        $responseData,
        string $contentType = 'application/json'
    ) {
        $locator = new IdBasedOperationFinder($this->spec->getOperations(), $operationId);

        return $this->doValidate($statusCode, $responseData, $contentType, $locator);
    }

    public function validateBasedOnRequest(
        string $requestPath,
        string $requestMethod,
        int $statusCode,
        $responseData,
        string $contentType = 'application/json'
    ) {
        $locator = new RequestBasedOperationFinder(
            $requestPath,
            $requestMethod,
            $this->spec->getOperations()
        );

        return $this->doValidate($statusCode, $responseData, $contentType, $locator);
    }

    /**
     * @return DataValidatorInterface
     */
    protected function getDataValidator(): DataValidatorInterface
    {
        return new JsonGuardDataValidator();
    }


    protected function doValidate(
        int $statusCode,
        $responseData,
        string $contentType,
        OperationFinder $locator
    ): Error\ErrorInterface {
        $operation = $locator
            ->find();
        $schema = $operation
            ->getSchemaByResponse($statusCode, $contentType);

        $schema->applyDiscriminatorData($responseData);

        $dataValidator = $this->getDataValidator();

        $error =  $dataValidator->validate($responseData, $schema);
        $error->setOperation($operation->getOperationId());

        return $error;
    }
}
