<?php

namespace MODX\Revolution\Definition;

/**
 * Adds disk listener provenance while retaining the original failure as the previous exception.
 */
class DiskListenerExecutionException extends \RuntimeException
{
}
