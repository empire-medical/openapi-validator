<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests;

use Mmal\OpenapiValidator\ObjectSchema;
use Mmal\OpenapiValidator\OperationInterface;
use Mmal\OpenapiValidator\SchemaInterface;
use Mmal\OpenapiValidator\Spec;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class SpecTest extends BaseTestCase
{
    public function testCanInstantiate()
    {
        $schema = $this->getSchema();
        $parsedSchema = Yaml::parse($schema);

        $spec = Spec::fromArray($parsedSchema);

        $this->assertInstanceOf(Spec::class, $spec);
    }

    public function testGetOperationById()
    {
        $schema = $this->getSchema();
        $parsedSchema = Yaml::parse($schema);

        $spec = Spec::fromArray($parsedSchema);

        $operation = $spec->getOperationById('getBooks');

        $this->assertInstanceOf(OperationInterface::class, $operation);
    }

    public function testGetSchemaByOperationIdAndResponseCode()
    {
        $schema = $this->getSchema();
        $parsedSchema = Yaml::parse($schema);

        $spec = Spec::fromArray($parsedSchema);

        $schema = $spec
            ->getOperationById('getBooks')
            ->getSchemaByResponse(200);

        $this->assertInstanceOf(ObjectSchema::class, $schema);
    }

    /**
     * @return bool|string
     */
    protected function getSchema(): string
    {
        return file_get_contents(__DIR__.'/specs/simple-example-spec.yaml');
    }
}
