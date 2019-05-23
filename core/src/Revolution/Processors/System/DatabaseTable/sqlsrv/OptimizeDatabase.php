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

use MODX\Revolution\Processors\System\DatabaseTable\OptimizeDatabaseAbstract;
use xPDO\Om\xPDOCriteria;

/**
 * @package MODX\Revolution\Processors\System\DatabaseTable\sqlsrv
 */
class OptimizeDatabase extends OptimizeDatabaseAbstract
{
    /**
     * @return bool
     */
    public function optimize()
    {
        $sql = file_get_contents(__DIR__ . '/defragment-indexes.sql');
        $sql = str_replace('[[+dbname]]', $this->modx->escape($this->modx->config['database']), $sql);
        $c = new xPDOCriteria($this->modx, $sql);
        if ($c->stmt->execute() === false) {
            return false;
        }
        $er = $c->stmt->errorInfo();
        if ($er) {
            $sqlstate_class = substr($er[0], 0, 2);
            switch ($sqlstate_class) {
                case '00':
                    // success
                    return true;
                    break;
                case '01':
                    // success with warning
                    return $er[2];
                    break;
                case '02':
                    // no data found
                default:
                    // error
                    return $er[2];
            }
        }
        return true;
    }
}
