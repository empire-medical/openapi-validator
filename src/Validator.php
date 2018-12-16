<?php
declare(strict_types=1);

namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\DataValidator\DataValidatorInterface;
use Mmal\OpenapiValidator\DataValidator\JsonGuardDataValidator;

class Validator
{
    /** @var Spec */
    private $spec;

    public function __construct(array $parsedSchema)
    {
        $this->spec = Spec::fromArray($parsedSchema);
    }


    public function validate(string $operationId, int $statusCode, $responseData, string $contentType = 'application/json')
    {
        $schema = $this->spec
            ->getOperationById($operationId)
            ->getSchemaByResponse($statusCode, $contentType);

        $schema->applyDiscriminatorData($responseData);

        $dataValidator = $this->getDataValidator();

        return $dataValidator->validate($responseData, $schema);
    }

    /**
     * @return DataValidatorInterface
     */
    protected function getDataValidator(): DataValidatorInterface
    {
        return new JsonGuardDataValidator();
    }
}
