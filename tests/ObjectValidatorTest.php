<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class ObjectValidatorTest extends TestCase
{
    public function testNestedObjectNotRequired()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, ['name' => 'test']);

        $this->assertFalse($error->hasErrors());
    }

    public function testNestedObjectRequiredInvalidField()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooksWithRequiredNestedObj', 200, ['name' => 'test', 'nestedObj' => ['tag' => 1]]);

        $this->assertTrue($error->hasErrors());
    }

    public function testNestedObjectNotRequiredInvalidField()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, ['name' => 'test', 'nestedObj' => ['tag' => 1]]);

        $this->assertTrue($error->hasErrors());
    }

    public function testNestedObjectRequiredMissingField()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooksWithRequiredNestedObj', 200, ['name' => 'test', 'nestedObj' => []]);

        $this->assertTrue($error->hasErrors());
    }

    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/object-example-spec.yaml');
        return new Validator($schema);
    }
}
