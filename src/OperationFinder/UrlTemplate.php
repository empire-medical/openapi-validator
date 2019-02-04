<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\OperationFinder;

class UrlTemplate
{
    /** @var string */
    private $template;

    /**
     */
    public function __construct(string $template)
    {
        $array = explode('?', $template);
        $this->template = trim(array_shift($array), '/');
    }

    public function matches(string $requestPath): bool
    {
        $requestPath = trim($requestPath, '/');
        $array = explode('?', $requestPath);
        $requestPathCleaned = array_shift($array);
        $templateParts = explode('/', $this->template);
        $requestParts = explode('/', $requestPathCleaned);

        if (count($templateParts) !== count($requestParts)) {
            return false;
        }

        foreach ($requestParts as $partIndex => $requestPart) {
            if (!isset($templateParts[$partIndex])) {
                return false;
            }
            $templatePart = $templateParts[$partIndex];
            if (
                preg_match('|^{[^}]+}$|', $templatePart)
            ) {
                continue;
            }

            if ($requestPart !== $templatePart) {
                return false;
            }
            continue;
        }

        return true;
    }
}
