<?php
declare(strict_types=1);

namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class NullableValidatorTest extends BaseTestCase
{
    public function testAcceptNull()
    {
        $validator = $this->getTestedClass();

        $errors = $validator->validate('getBooks', 200, [
            'name' => null
        ]);

        $this->assertFalse($errors->hasErrors());
    }

    public function testNullIsStillRequired()
    {
        $validator = $this->getTestedClass();

        $errors = $validator->validate('getBooks', 200, [
        ]);

        $this->assertTrue($errors->hasErrors());
    }

    public function testNullableObject()
    {
        $validator = $this->getTestedClass();

        $errors = $validator->validate('getBooksObj', 200, [
            'nestedObj' => null
        ]);

        $this->assertFalse($errors->hasErrors());
    }

    public function testNullableArray()
    {
        $validator = $this->getTestedClass();

        $errors = $validator->validate('getBooksArray', 200, [
            'nestedArray' => null
        ]);

        $this->assertFalse($errors->hasErrors());
    }

    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/nullable-example-spec.yaml');
        return $this->getInstance($schema);
    }
}
