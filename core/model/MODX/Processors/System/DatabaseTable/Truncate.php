<?php

namespace MODX\Processors\System\DatabaseTable;

use MODX\Processors\modDriverSpecificProcessor;

/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
class Truncate extends modDriverSpecificProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('database_truncate');
    }


    public function getLanguageTopics()
    {
        return ['system_info'];
    }


    public function process()
    {
        $table = $this->getProperty('t', false);
        if (empty($table)) return $this->failure($this->modx->lexicon('truncate_table_err'));

        if (!$this->truncate($table)) {
            $output = $this->failure($this->modx->lexicon('truncate_table_err'));
        } else {
            $this->logManagerAction();
            $output = $this->success();
        }

        return $output;
    }


    /**
     * Truncate a database table
     *
     * @param string $table
     *
     * @return boolean
     */
    public function truncate($table)
    {
        return true;
    }


    /**
     * Log a manager action showing the truncated table
     *
     * @return void
     */
    public function logManagerAction()
    {
        if ($this->modx->error->status) {
            $this->modx->logManagerAction('database_truncate', 'table', $this->getProperty('t'));
        }

    }
}