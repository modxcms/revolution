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

use MODX\Revolution\Processors\System\DatabaseTable\OptimizeDatabaseAbstract;
use PDO;
use PDOStatement;

/**
 * @package MODX\Revolution\Processors\System\DatabaseTable\mysql
 */
class OptimizeDatabase extends OptimizeDatabaseAbstract
{
    /**
     * @return bool
     */
    public function optimize()
    {
        $stmt = $this->modx->query('SHOW TABLES');
        if ($stmt && $stmt instanceof PDOStatement) {
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                if (!empty($row[0])) {
                    $sql = 'OPTIMIZE TABLE ' . $this->modx->escape($this->modx->getOption('dbname')) . '.' . $this->modx->escape($row[0]);
                    $this->modx->query($sql);
                }
            }
            $stmt->closeCursor();
        } else {
            return false;
        }
        return true;
    }
}
