<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;


use Mmal\OpenapiValidator\Reference\MissingReferenceException;
use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class ReferenceValidatorTest extends TestCase
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

    public function testReferenceInsideReferenceIsValid()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('referenceInReference', 200, [
            'nestedObj' => [
                'nested' => [
                    'tag' => 'foo'
                ]
            ]
        ]);

        $this->assertFalse($error->hasErrors());
    }

    public function testReferenceInsideReferenceIsInValid()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('referenceInReference', 200, [
            'nestedObj' => [
                'nested' => [
                    'tag' => 1
                ]
            ]
        ]);

        $this->assertTrue($error->hasErrors());
    }

    public function testReferenceInsideReferenceIsMissingField()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('referenceInReference', 200, [
            'nestedObj' => [
                'nested' => [
                ]
            ]
        ]);

        $this->assertTrue($error->hasErrors());
    }

    public function testReferenceInsideReferenceIsMissing()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('referenceInReference', 200, [
            'nestedObj' => []
        ]);

        $this->assertTrue($error->hasErrors());
    }

    public function testSchemaReferenceValid()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('schemaAsReference', 200, [
            'tag' => 'foor'
        ]);

        $this->assertFalse($error->hasErrors());
    }

    public function testSchemaReferenceInValid()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('schemaAsReference', 200, [
            'tag' => 1
        ]);

        $this->assertTrue($error->hasErrors());
    }

    public function testMissingRef()
    {
        $this->expectException(MissingReferenceException::class);

        $schema = file_get_contents(__DIR__.'/specs/reference-missing-spec.yaml');
        $validator =  new Validator($schema);

        $error = $validator->validate('missingRef', 200, [
            'tag' => 1
        ]);
    }

    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/reference-example-spec.yaml');
        return new Validator($schema);
    }
}