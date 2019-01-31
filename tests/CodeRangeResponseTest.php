<?php
declare(strict_types=1);

namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\Validator;
use PHPUnit\Framework\TestCase;

class CodeRangeResponseTest extends BaseTestCase
{
    public function testShouldHandleCodeRanges()
    {
        $validator = $this->getTestedClass();

        $data = [
            'name' => 'some book'
        ];

        $errors = $validator->validate('getBooks', 210, $data);

        $this->assertFalse($errors->hasErrors());
    }

    protected function getTestedClass(): Validator
    {
        $schema = file_get_contents(__DIR__.'/specs/range-code-response-spec.yaml');

        return $this->getInstance($schema);
    }
}
