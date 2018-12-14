<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\Exception\MissingReferenceException;
use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class OneOfValidatorTest extends BaseTestCase
{
    public function testIsValidAgainstOne()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, [
            'name' => 'foo'
        ]);

        $this->assertFalse($error->hasErrors());
    }

    public function testIsValidAgainstOther()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, [
            'foo' => 1
        ]);

        $this->assertFalse($error->hasErrors());
    }

    public function testInvalidAgainstBoth()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, [
            'name' => 'foo',
            'foo' => 1
        ]);

        $this->assertTrue($error->hasErrors());
    }

    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/oneof-example-spec.yaml');
        return $this->getInstance($schema);
    }
}
