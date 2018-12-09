<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;


use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class DiscriminatorValidatorTest extends TestCase
{
    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/discriminator-example-spec.yaml');
        return new Validator($schema);
    }

    public function testValidObject1()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, [
            'type' => 'obj1',
            'tag' => 'foo',
        ]);

        $this->assertFalse($error->hasErrors());
    }

    public function testValidObject2()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, [
            'type' => 'obj2',
            'name' => 'foo',
        ]);

        $this->assertFalse($error->hasErrors());
    }

    public function testInvalidTypeDoesNotMatch()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, [
            'type' => 'obj2',
            'tag' => 'foo',
        ]);

        $this->assertTrue($error->hasErrors());
    }

    public function testInvalidTypeDoesNotMatch2()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, [
            'type' => 'obj1',
            'name' => 'foo',
        ]);

        $this->assertTrue($error->hasErrors());
    }
}