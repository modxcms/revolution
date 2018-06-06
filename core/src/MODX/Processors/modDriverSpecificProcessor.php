<?php

namespace MODX\Processors;

use MODX\MODX;

/**
 * A utility class used for defining driver-specific processors
 *
 * @package modx
 */
abstract class modDriverSpecificProcessor extends modProcessor
{
    public static function getInstance(MODX &$modx, $className, $properties = [])
    {
        $className .= '_' . $modx->getOption('dbtype');
        /** @var modProcessor $processor */
        $processor = new $className($modx, $properties);

        return $processor;
    }
}