<?php
declare(strict_types=1);

namespace Mmal\OpenapiValidator;

use Mmal\OpenapiValidator\DataValidator\DataValidatorInterface;
use Mmal\OpenapiValidator\DataValidator\JsonGuardDataValidator;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Validator
{
    /** @var Spec */
    private $spec;

    /**
     * @throws ParseException
     */
    public function __construct(string $schema)
    {
        $parsedSchema = Yaml::parse($schema);
        $this->spec = Spec::fromArray($parsedSchema);
    }


    public function validate(string $operationId, int $statusCode, array $responseData)
    {
        $schema = $this->spec
            ->getOperationById($operationId)
            ->getSchemaByResponse($statusCode);

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
