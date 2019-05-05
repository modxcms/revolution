<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\DatabaseTable\sqlsrv;

/**
 * @package MODX\Revolution\Processors\System\DatabaseTable\sqlsrv
 */
class Optimize extends \MODX\Revolution\Processors\System\DatabaseTable\OptimizeAbstract
{
    /**
     * @param string $table
     * @return bool
     */
    public function optimize($table)
    {
        $sql = 'ALTER INDEX ALL ON ' . $this->modx->escape($table) . ' REBUILD';
        return $this->modx->exec($sql) !== false;
    }
}
