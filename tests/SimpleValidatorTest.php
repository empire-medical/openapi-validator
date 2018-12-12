<?php
declare(strict_types=1);

namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class SimpleValidatorTest extends TestCase
{
    public function testShouldReturnErrorOnInvalidSchema()
    {
        $validator = $this->getTestedClass();

        $data = [];

        $errors = $validator->validate('getBooks', 200, $data);

        $this->assertTrue($errors->hasErrors());
    }

    public function testMissingRequiredField()
    {
        $validator = $this->getTestedClass();
        $error = $validator->validate('getBooks', 200, ['foo' => 'bar']);

        $this->assertTrue($error->hasErrors());
    }

    public function testEmptyRequiredField()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, ['name' => null]);

        $this->assertTrue($error->hasErrors());
    }

    public function testRequiredFieldIsOfWrongType()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, ['name' => 123]);

        $this->assertTrue($error->hasErrors());
    }

    public function testNotRequiredFieldIsNotRequired()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, ['name' => 'foobar']);

        $this->assertFalse($error->hasErrors());
    }

    public function testNotRequiredFieldIsOfWrongType()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, ['name' => 'test', 'tag' => 123]);

        $this->assertTrue($error->hasErrors());
    }

    public function testHandleNullResponses()
    {
        $validator = $this->getTestedClass();

        $errors = $validator->validate('getBooks', 200, null);

        $this->assertTrue($errors->hasErrors());
    }

    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/simple-example-spec.yaml');
        return new Validator($schema);
    }
}
