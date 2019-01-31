<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Response;


class DefaultResponse extends Response
{
    /**
     */
    public function __construct(array $schemas)
    {
        $this->schemas = $schemas;
    }

    public function doesSupportStatusCode(int $statusCode): bool
    {
        return true;
    }

}