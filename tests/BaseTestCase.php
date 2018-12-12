<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;


use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class BaseTestCase extends TestCase
{

    /**
     * @param $schema
     * @return Validator
     */
    protected function getInstance($schema): Validator
    {
        return new Validator(Yaml::parse($schema));
    }
}