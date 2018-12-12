<?php

namespace Mmal\OpenapiValidator\DataValidator;

use Mmal\OpenapiValidator\Error\ErrorInterface;
use Mmal\OpenapiValidator\SchemaInterface;

interface DataValidatorInterface
{
    public function validate($actualData, SchemaInterface $schema): ErrorInterface;
}
