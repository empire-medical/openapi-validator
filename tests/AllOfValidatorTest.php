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

    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/allof-example-spec.yaml');
        return new Validator($schema);
    }
}