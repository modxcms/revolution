<?php

namespace MODX\Processors\System\DatabaseTable;

use MODX\Processors\modDriverSpecificProcessor;

/**
 * Optimize a database table
 *
 * @package modx
 * @subpackage processors.system.databasetable
 */
class Optimize extends modDriverSpecificProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('database');
    }


    public function getLanguageTopics()
    {
        return ['system_info'];
    }


    public function process()
    {
        $table = $this->getProperty('t', false);
        if (empty($table)) return $this->failure($this->modx->lexicon('optimize_table_err'));

        if (!$this->optimize($table)) {
            $output = $this->failure($this->modx->lexicon('optimize_table_err'));
        } else {
            $this->logManagerAction();
            $output = $this->success();
        }

        return $output;
    }


    /**
     * Optimize a database table
     *
     * @param string $table
     *
     * @return boolean
     */
    public function optimize($table)
    {
        return true;
    }


    /**
     * Log a manager action showing the optimized table
     *
     * @return void
     */
    public function logManagerAction()
    {
        if ($this->modx->error->status) {
            $this->modx->logManagerAction('database_optimize', 'table', $this->getProperty('t'));
        }

    }
}