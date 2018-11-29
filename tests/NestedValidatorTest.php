<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;


use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class NestedValidatorTest extends TestCase
{
    public function testNestedObjectNotRequired()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooksWithReference', 200, ['name' => 'test']);

        $this->assertFalse($error->hasErrors());
    }

    public function testNestedObjectNotRequiredInvalidField()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooksWithReference', 200, ['name' => 'test', 'nestedObj' => ['tag' => 1]]);

        $this->assertTrue($error->hasErrors());
    }


    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/reference-example-spec.yaml');
        return new Validator($schema);
    }
}