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


/**
 * A utility class used for defining driver-specific processors
 *
 * @package MODX\Revolution
 */
abstract class modDriverSpecificProcessor extends modProcessor
{
    public static function getInstance(modX &$modx, $className, $properties = [])
    {
        $className .= '_' . $modx->getOption('dbtype');
        /** @var modProcessor $processor */
        $processor = new $className($modx, $properties);

        return $processor;
    }
}
