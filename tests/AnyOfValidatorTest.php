<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\Exception\MissingReferenceException;
use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class AnyOfValidatorTest extends BaseTestCase
{
    public function testIsValidAgainstOne()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, [
            'name' => 'foo'
        ]);

        $this->assertFalse($error->hasErrors());
    }

    public function testIsValidAgainstAnother()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, [
            'name' => 123
        ]);

        $this->assertFalse($error->hasErrors());
    }

    public function testIsNotValid()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooks', 200, [
            'name' => 123.345
        ]);

        $this->assertTrue($error->hasErrors());
    }

    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/anyof-example-spec.yaml');
        return $this->getInstance($schema);
    }
}
