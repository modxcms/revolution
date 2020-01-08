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
 * Gets a list of database tables
 */
class GetList extends GetListAbstract
{

    /**
     * @var GetListAbstract
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

        $this->concreteProcessor = self::getInstance($modx, GetList::class, $properties);

        return $this->concreteProcessor;
    }

    /**
     * @return array
     */
    public function getTables()
    {
        return $this->concreteProcessor->getTables();
    }
}
