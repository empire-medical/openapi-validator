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

        $actualDataStd = json_decode(json_encode($actualData));
        $schemaStd = json_decode(json_encode($schema->toArray()));
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
