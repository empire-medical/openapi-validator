<?php
declare(strict_types=1);

namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class DefaultResponseTest extends BaseTestCase
{
    public function testShouldAllowDefaultResponse()
    {
        $validator = $this->getTestedClass();

        $data = [
            'error' => 'some error'
        ];

        $errors = $validator->validate('getBooks', 500, $data);

        $this->assertFalse($errors->hasErrors());
    }

    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/default-response-spec.yaml');

        return $this->getInstance($schema);
    }
}
