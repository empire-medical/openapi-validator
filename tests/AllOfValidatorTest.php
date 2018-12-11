<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\Reference\MissingReferenceException;
use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class AllOfValidatorTest extends TestCase
{
    public function testHasAllRequiredProperties()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, [
            'name' => 'foo',
            'length' => 10
        ]);

        $this->assertFalse($error->hasErrors());
    }

    public function testMissingSomeProperities()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, [
            'name' => 'foo'
        ]);

        $this->assertTrue($error->hasErrors());
    }

    public function testSomePropertiesAreInvalid()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, [
            'name' => 'foo',
            'length' => '10'
        ]);

        $this->assertTrue($error->hasErrors());
    }

    public function testAllowsNull()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks2', 200, ['foo' => null]);

        $this->assertFalse($error->hasErrors(), $error->__toString());
    }

    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/allof-example-spec.yaml');
        return new Validator($schema);
    }
}
