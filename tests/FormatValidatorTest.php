<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\Validator;

class FormatValidatorTest extends BaseTestCase
{
    public function testInvalidFormat()
    {
        $validator = $this->getTestedClass();

        $data = ['email' => 'foo'];

        $errors = $validator->validate('getBooks', 200, $data);

        $this->assertTrue($errors->hasErrors());
    }

    public function testValidFormat()
    {
        $validator = $this->getTestedClass();

        $data = ['email' => 'foo@baz.pl'];

        $errors = $validator->validate('getBooks', 200, $data);

        $this->assertFalse($errors->hasErrors());
    }

    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/format-validator-test.yaml');

        return $this->getInstance($schema);
    }
}
