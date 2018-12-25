<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Removes locks on all objects
 *
 * @package modx
 * @subpackage processors.system
 */
class modSystemInfoProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('view_sysinfo');
    }

    public function process() {
        $data = array();

        $this->modx->getVersionData();
        $serverOffset = floatval($this->modx->getOption('server_offset_time',null,0)) * 3600;

        /* general */
        $data['modx_version'] = $this->modx->version['full_appname'];
        $data['code_name'] = $this->modx->version['code_name'];
        $data['servertime'] = strftime('%I:%M:%S %p', time());
        $data['localtime'] = strftime('%I:%M:%S %p', time()+$serverOffset);
        $data['serveroffset'] = $serverOffset / (60*60);

        /* database info */
        $data['database_type'] = $this->modx->getOption('dbtype');
        $stmt= $this->modx->query("SELECT VERSION()");
        if ($stmt) {
            $result= $stmt->fetch(PDO::FETCH_COLUMN);
            $stmt->closeCursor();
        } else {
            $result='-';
        }
        $data['database_version'] = $result;
        $data['database_charset'] = $this->modx->getOption('charset');
        $data['database_name'] = trim($this->modx->getOption('dbname'), $this->modx->_escapeCharOpen . $this->modx->_escapeCharClose);
        $data['database_server'] = $this->modx->getOption('host');
        $data['now'] = strftime('%b %d, %Y %I:%M %p',time());
        $data['table_prefix'] = $this->modx->getOption('table_prefix');

        return $this->success('',$data);
    }
}
return 'modSystemInfoProcessor';
