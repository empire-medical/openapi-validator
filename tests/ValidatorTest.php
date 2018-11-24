<?php
declare(strict_types=1);

namespace Mmal\OpenapiValidator\Tests;

use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testShouldReturnErrorOnInvalidSchema()
    {
        $schema = file_get_contents(__DIR__ . 'example-spec.yaml');
    }
}