<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\DataValidator\JsonGuardDataValidator;
use Mmal\OpenapiValidator\ObjectSchema;
use Mmal\OpenapiValidator\Property\StringProperty;
use PHPUnit\Framework\TestCase;

class JsonGuardDataValidatorTest extends TestCase
{
    public function testMissingRequiredField()
    {
        $validator = new JsonGuardDataValidator();
        $schema1 = new ObjectSchema(
            [
                new StringProperty('name', false),
            ],
            ['name']
        );
        $error = $validator->validate([], $schema1);

        $this->assertTrue($error->hasErrors());
    }

    public function testEmptyRequiredField()
    {
        $validator = new JsonGuardDataValidator();
        $schema1 = new ObjectSchema(
            [
                new StringProperty('name', false),
            ],
            ['name']
        );
        $error = $validator->validate(['name' => null], $schema1);

        $this->assertTrue($error->hasErrors());
    }

    public function testRequiredFieldIsOfWrongType()
    {
        $validator = new JsonGuardDataValidator();
        $schema1 = new ObjectSchema(
            [
                new StringProperty('name', false),
            ],
            ['name']
        );
        $error = $validator->validate(['name' => 123], $schema1);

        $this->assertTrue($error->hasErrors());
    }

    public function testNotRequiredFieldIsNotRequired()
    {
        $validator = new JsonGuardDataValidator();
        $schema1 = new ObjectSchema(
            [
                new StringProperty('name', false),
                new StringProperty('tag', false)
            ],
            ['name']
        );
        $error = $validator->validate(['name' => 'foobar'], $schema1);

        $this->assertFalse($error->hasErrors());
    }

    public function testNotRequiredFieldIsOfWrongType()
    {
        $validator = new JsonGuardDataValidator();
        $schema1 = new ObjectSchema(
            [
                new StringProperty('name', false),
                new StringProperty('tag', false)
            ],
            ['name']
        );
        $error = $validator->validate(['name' => 'test', 'tag' => 123], $schema1);

        $this->assertTrue($error->hasErrors());
    }
}