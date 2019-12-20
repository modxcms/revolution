<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\DatabaseTable;

use MODX\Revolution\modX;

/**
 * Optimize a database
 */
class OptimizeDatabase extends OptimizeDatabaseAbstract
{

    /**
     * @var OptimizeDatabaseAbstract
     */
    protected $concreteProcessor;

    /**
     * Creates a Processor object.
     *
     * @param modX $modx A reference to the modX instance
     * @param array $properties An array of properties
     */
    public function __construct(modX $modx, array $properties = [])
    {
        parent::__construct($modx, $properties);

        $this->concreteProcessor = self::getInstance($modx, OptimizeDatabase::class, $properties);

        return $this->concreteProcessor;
    }

    /**
     * Optimize a database table
     * @param $table
     * @return boolean
     */
    public function optimize()
    {
        return $this->concreteProcessor->optimize();
    }
}
