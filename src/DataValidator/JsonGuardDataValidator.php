<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\DataValidator;

use Mmal\OpenapiValidator\Error\Error;
use Mmal\OpenapiValidator\Error\ErrorCollection;
use Mmal\OpenapiValidator\Error\ErrorInterface;
use Mmal\OpenapiValidator\Error\NoError;
use Mmal\OpenapiValidator\SchemaInterface;

class JsonGuardDataValidator implements DataValidatorInterface
{
    public function validate(array $actualData, SchemaInterface $schema): ErrorInterface
    {
        $validator = new \JsonSchema\Validator();

        $encodedActualData = json_encode($actualData);
        if (!is_string($encodedActualData)) {
            throw new \Exception('Invalid data provided');
        }
        $actualDataStd = json_decode($encodedActualData);
        $encodedSchema = json_encode($schema->toArray());
        if (!is_string($encodedSchema)) {
            throw new \Exception('Invalid schema provided');
        }
        $schemaStd = json_decode($encodedSchema);
        $validator->validate(
            $actualDataStd,
            $schemaStd
        );

        if ($validator->isValid()) {
            return new NoError();
        }
        $jsonGuardErrors = $validator->getErrors();
        $collection = new ErrorCollection();
        foreach ($jsonGuardErrors as $jsonGuardError) {
            $collection->addError(new Error(
                $jsonGuardError['message'],
                $schema,
                $actualData
            ));
        }

        return $collection;
    }
}
