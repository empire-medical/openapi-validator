<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/array-example-spec.yaml');
        return new Validator($schema);
    }

    public function testArrayScalarItemHasInvalidType()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooksList', 200, ['tags' => [123]]);

        $this->assertTrue($error->hasErrors());
    }

    public function testArrayScalarItemHasValidType()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooksList', 200, ['tags' => ['foo']]);

        $this->assertFalse($error->hasErrors());
    }

    public function testArrayObjectItemHasMissingField()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooksListWithObjects', 200, ['tags' => [['foo' => 'bar']]]);

        $this->assertTrue($error->hasErrors());
    }

    public function testArrayObjectItemFieldHasInvalidType()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooksListWithObjects', 200, ['tags' => [['foo' => 123]]]);

        $this->assertTrue($error->hasErrors());
    }

    public function testArrayOneofObjectItemHasMissingField()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooksListWithObjects', 200, ['tags' => [['name' => 'bar'], ['foo' => 'bar']]]);

        $this->assertTrue($error->hasErrors());
    }

    public function testArrayOneOfObjectItemHasInvalidType()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooksListWithObjects', 200, ['tags' => [['name' => 'bar'], ['name' => 123]]]);

        $this->assertTrue($error->hasErrors());
    }

    public function testArrayManyItemsValid()
    {
        $validator = $this->getTestedClass();

        $error = $validator->validate('getBooksListWithObjects', 200, ['tags' => [['name' => 'bar'], ['name' => 'bar']]]);

        $this->assertFalse($error->hasErrors());
    }
}
