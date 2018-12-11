<?php

namespace Mmal\OpenapiValidator;

interface SchemaInterface
{
    public function toArray(): array;

    public function applyDiscriminatorData($actualData);

    public function makeNullable();
}
