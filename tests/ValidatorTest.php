<?php
declare(strict_types=1);

namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testShouldReturnErrorOnInvalidSchema()
    {
        $schema = file_get_contents(__DIR__ . '/example-spec.yaml');

        $data = [];

        $validator = new Validator($schema);

        $errors = $validator->validate('getBooks', 200, $data);

        $this->assertTrue($errors->hasErrors());
    }
}
