<?php
declare(strict_types=1);

namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class NoResponseValidatorTest extends TestCase
{
    public function testShouldReturnErrorOnInvalidSchema()
    {
        $validator = $this->getTestedClass();

        $data = [];

        $errors = $validator->validate('getBooks', 204, $data);

        $this->assertFalse($errors->hasErrors());
    }


    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/no-schema-example-spec.yaml');
        return new Validator($schema);
    }
}
