<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\DatabaseTable\mysql;

use MODX\Revolution\Processors\System\DatabaseTable\TruncateAbstract;

/**
 * @package MODX\Revolution\Processors\System\DatabaseTable\mysql
 */
class Truncate extends TruncateAbstract
{
    /**
     * @param string $table
     * @return bool
     */
    public function truncate($table)
    {
        $sql = 'TRUNCATE TABLE ' . $this->modx->escape($this->modx->getOption('dbname')) . '.' . $this->modx->escape($table);
        return !($this->modx->exec($sql) === false);
    }
}
