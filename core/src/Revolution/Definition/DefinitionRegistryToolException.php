<?php

namespace MODX\Revolution\Definition;

use RuntimeException;

class DefinitionRegistryToolException extends RuntimeException
{
    private string $errorCode;

    public function __construct(string $message, int $exitStatus, string $errorCode)
    {
        parent::__construct($message, $exitStatus);
        $this->errorCode = $errorCode;
    }

    public function getExitStatus(): int
    {
        return $this->getCode();
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
