<?php
/*
 * MODX Revolution
 *
 * Copyright 2006-2012 by MODX, LLC.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */
/**
 * @package modx
 * @subpackage setup
 */
abstract class modConfigReader {
    /** @var modInstall $install */
    public $install;
    /** @var xPDO $xpdo */
    public $xpdo;
    /** @var array $config */
    public $config = array();

    function __construct(modInstall $install,array $config = array()) {
        $this->install =& $install;
        $this->xpdo =& $install->xpdo;
        $this->config = array_merge(array(

        ),$config);
    }

    /**
     * Read an existing configuration file
     * @abstract
     * @param array $config
     */
    abstract public function read(array $config = array());

    /**
     * Load defaults for a configuration file if one does not exist; used in new installations
     * @param array $config
     * @return array
     */
    public function loadDefaults(array $config = array()) {
        $this->getHttpHost();

        $this->config = array_merge($this->config,array(
            'database_type' => isset ($_POST['databasetype']) ? $_POST['databasetype'] : 'mysql',
            'database_server' => isset ($_POST['databasehost']) ? $_POST['databasehost'] : 'localhost',
            'database_connection_charset' => 'utf8',
            'database_charset' => 'utf8',
            'dbase' => trim((isset ($_POST['database_name']) ? $_POST['database_name'] : 'modx'), '`[]'),
            'database_user' => isset ($_POST['databaseloginname']) ? $_POST['databaseloginname'] : '',
            'database_password' => isset ($_POST['databaseloginpassword']) ? $_POST['databaseloginpassword'] : '',
            'table_prefix' => isset ($_POST['tableprefix']) ? $_POST['tableprefix'] : 'modx_',
            'site_sessionname' => 'SN' . uniqid(''),
            'cache_disabled' => !empty($_POST['cache_disabled']) ? 'true' : 'false',
            'inplace' => isset ($_POST['inplace']) ? 1 : 0,
            'unpacked' => isset ($_POST['unpacked']) ? 1 : 0,
            'config_options' => array(),
            'driver_options' => array(),
        ),$config);
        return $this->config;
    }

    /**
     * Get the HTTP host for the server
     */
    public function getHttpHost() {
        if (php_sapi_name() != 'cli') {
            $this->config['https_port'] = isset ($_POST['httpsport']) ? $_POST['httpsport'] : '443';
            $isSecureRequest = ((isset ($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') || $_SERVER['SERVER_PORT'] == $this->config['https_port']);
            $this->config['http_host']= $_SERVER['HTTP_HOST'];
            if ($_SERVER['SERVER_PORT'] != 80) {
                $this->config['http_host']= str_replace(':' . $_SERVER['SERVER_PORT'], '', $this->config['http_host']); /* remove port from HTTP_HOST */
            }
            $this->config['http_host'] .= ($_SERVER['SERVER_PORT'] == 80 || $isSecureRequest) ? '' : ':' . $_SERVER['SERVER_PORT'];
        } else {
            $this->config['http_host'] = 'localhost';
            $this->config['https_port'] = 443;
        }
    }
}