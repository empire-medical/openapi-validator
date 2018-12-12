<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;


use Mmal\OpenapiValidator\Exception\OperationNotFoundException;
use Mmal\OpenapiValidator\Exception\ResponseNotFoundException;
use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class ErrorHandlingTest extends BaseTestCase
{
    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/simple-example-spec.yaml');
        return $this->getInstance($schema);
    }

    public function testErrorOnOperationNotFound()
    {
        $this->expectException(OperationNotFoundException::class);

        $validator = $this->getTestedClass();

        $validator->validate('getFoo', 200, []);
    }

    public function testErrorOnResponseNotFound()
    {
        $this->expectException(ResponseNotFoundException::class);

        $validator = $this->getTestedClass();

        $validator->validate('getBooks', 400, []);
    }
}