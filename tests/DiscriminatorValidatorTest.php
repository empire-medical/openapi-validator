<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class DiscriminatorValidatorTest extends BaseTestCase
{
    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/discriminator-example-spec.yaml');
        return $this->getInstance($schema);
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

    public function testValidNestedDiscrimanted()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooksNested', 200, [
            'class' => 'foo',
            'subObj' => [
                'type' => 'obj1',
                'tag' => 'foo',
            ]
        ]);

        $this->assertFalse($error->hasErrors());
    }

    public function testInValidNestedDiscrimanted()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooksNested', 200, [
            'class' => 'foo',
            'subObj' => [
                'type' => 'obj1',
                'tag' => 4
            ]
        ]);

        $this->assertTrue($error->hasErrors());
    }

    public function testValidNestedDiscrimantedOtherObject()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooksNested', 200, [
            'class' => 'bar',
            'count' => 4
        ]);

        $this->assertFalse($error->hasErrors());
    }
}
