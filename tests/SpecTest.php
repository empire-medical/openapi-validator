<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\ObjectSchema;
use Mmal\OpenapiValidator\OperationInterface;
use Mmal\OpenapiValidator\SchemaInterface;
use Mmal\OpenapiValidator\Spec;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class SpecTest extends TestCase
{
    public function testCanInstantiate()
    {
        $schema = file_get_contents(__DIR__ . '/example-spec.yaml');
        $parsedSchema = Yaml::parse($schema);

        $spec = Spec::fromArray($parsedSchema);

        $this->assertInstanceOf(Spec::class, $spec);
    }

    public function testGetOperationById()
    {
        $schema = file_get_contents(__DIR__ . '/example-spec.yaml');
        $parsedSchema = Yaml::parse($schema);

        $spec = Spec::fromArray($parsedSchema);

        $operation = $spec->getOperationById('getBooks');

        $this->assertInstanceOf(OperationInterface::class, $operation);
    }

    public function testGetSchemaByOperationIdAndResponseCode()
    {
        $schema = file_get_contents(__DIR__ . '/example-spec.yaml');
        $parsedSchema = Yaml::parse($schema);

        $spec = Spec::fromArray($parsedSchema);

        $schema = $spec
            ->getOperationById('getBooks')
            ->getSchemaByResponse(200);

        $this->assertInstanceOf(ObjectSchema::class, $schema);
    }
}
