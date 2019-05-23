<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution;

use ReflectionClass;

/**
 * A utility class used for defining driver-specific processors
 * @package MODX\Revolution
 */
abstract class modDriverSpecificProcessor extends modProcessor
{
    /**
     * @param modX $modx
     * @param string $className
     * @param array $properties
     * @return modProcessor
     * @throws \ReflectionException
     */
    public static function getInstance(modX &$modx, $className, $properties = [])
    {
        $class = new ReflectionClass($className);
        $namespace = $class->getNamespaceName();
        $className = implode('\\', [$namespace, $modx->getOption('dbtype'), $class->getShortName()]);

        /** @var modProcessor $processor */
        $processor = new $className($modx, $properties);

        return $processor;
    }
}
