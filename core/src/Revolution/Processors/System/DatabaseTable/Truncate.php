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
 * Truncate a database table
 */
class Truncate extends TruncateAbstract
{

    /**
     * @var TruncateAbstract
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

        $this->concreteProcessor = self::getInstance($modx, Truncate::class, $properties);

        return $this->concreteProcessor;
    }

    /**
     * Truncate a database table
     * @param string $table
     * @return boolean
     */
    public function truncate($table)
    {
        return $this->concreteProcessor->truncate($table);
    }
}
