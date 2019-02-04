<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Response;

use Mmal\OpenapiValidator\EmptySchema;
use Mmal\OpenapiValidator\SchemaInterface;

class Response implements ResponseInterface
{
    const RANGE_PATTERN = '|[0-9]xx|';
    /** @var int|string */
    private $statusCode;

    /** @var SchemaInterface[]|array */
    protected $schemas;

    /**
     */
    public function __construct($statusCode, array $schemas)
    {
        if (
            !is_numeric($statusCode) &&
            !preg_match(self::RANGE_PATTERN, $statusCode)
        ) {
            throw new \InvalidArgumentException(sprintf(
                'Provided status code %s is neither a valid http code or range of codes',
                $statusCode
            ));
        }
        $this->statusCode = $statusCode;
        $this->schemas = $schemas;
    }

    public function toArray(): array
    {
        return [
            'status_code' => $this->statusCode,
        ];
    }

    public function doesSupportStatusCode(int $statusCode): bool
    {
        if (is_numeric($this->statusCode)) {
            return (int)$this->statusCode === $statusCode;
        }
        if (preg_match(self::RANGE_PATTERN, $this->statusCode)) {
            $firstDigit = $this->statusCode[0];

            return $statusCode >= ($firstDigit * 100) && $statusCode <= ($firstDigit * 100 + 99);
        }

        return false;
    }

    public function getSchema(string $contentType): SchemaInterface
    {
        if (!isset($this->schemas[$contentType])) {
            if ($this->statusCode == 204) {
                return new EmptySchema();
            }
            throw new \InvalidArgumentException(sprintf('No defined schema for content-type %s', $contentType));
        }

        return $this->schemas[$contentType];
    }
}
