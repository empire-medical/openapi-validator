<?php
declare(strict_types=1);

namespace Mmal\OpenapiValidator;

use Symfony\Component\Yaml\Yaml;

class Validator
{
    public function validate(string $schema)
    {
        $parsed = Yaml::parse($schema);
    }
}