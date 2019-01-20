<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\Exception\MissingReferenceException;
use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class CyclicReferenceTest extends BaseTestCase
{
    public function testDoesNotThrowNestingException()
    {
        $validator = $this->getTestedClass();

        $this->assertInstanceOf(Validator::class, $validator);
    }

    public function testValidNestedResponse()
    {
        $validator = $this->getTestedClass();

        $this->assertFalse($validator->validate(
            'getBooks',
            200,
            [
                'name' => 'foo',
                'parent' => [
                    'name' => 'foo2',
                    'parent' => [
                        'name' => 'foo3',
                        'parent' => [
                            'name' => 'foo4'
                        ]
                    ]
                ]
            ]
        )->hasErrors());
    }

    public function testInValidNestedResponse()
    {
        $validator = $this->getTestedClass();

        $this->assertTrue($validator->validate(
            'getBooks',
            200,
            [
                'name' => 'foo',
                'parent' => [
                    'name' => 'foo2',
                    'parent' => [
                        'name' => 'foo3',
                        'parent' => [
                        ]
                    ]
                ]
            ]
        )->hasErrors());
    }

    public function testInValidNestedResponseType()
    {
        $validator = $this->getTestedClass();

        $this->assertTrue($validator->validate(
            'getBooks',
            200,
            [
                'name' => 'foo',
                'parent' => [
                    'name' => 'foo2',
                    'parent' => [
                        'name' => 'foo3',
                        'parent' => [
                            'name' => 4
                        ]
                    ]
                ]
            ]
        )->hasErrors());
    }

    public function testValidNestedResponseNoParent()
    {
        $validator = $this->getTestedClass();

        $this->assertFalse($validator->validate(
            'getBooks',
            200,
            [
                'name' => 'foo'
            ]
        )->hasErrors());
    }

    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/cyclic_reference_spec.yaml');

        return $this->getInstance($schema);
    }
}
