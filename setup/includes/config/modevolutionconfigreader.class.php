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
require_once strtr(realpath(MODX_SETUP_PATH.'includes/config/modconfigreader.class.php'),'\\','/');
/**
 * Reads from a Revolution config file
 *
 * @package modx
 * @subpackage setup
 */
class modEvolutionConfigReader extends modConfigReader {
    public function read(array $config = array()) {
        global $database_dsn, $database_type, $database_server, $dbase, $database_user, $database_password,
               $database_connection_charset, $table_prefix, $config_options, $driver_options;
        $database_connection_charset = 'utf8';

        /* get http host */
        $this->getHttpHost();

        @ob_start();
        $included = @ include MODX_INSTALL_PATH . 'manager/includes/config.inc.php';
        @ob_end_clean();
        if ($included && isset ($dbase)) {
            $config_options = is_array($config_options) ? $config_options : array();
            $driver_options = is_array($driver_options) ? $driver_options : array();

            $this->config = array_merge(array(
                'database_type' => !empty($database_type) ? $database_type : 'mysql',
                'database_server' => !empty($database_server) ? $database_server : 'localhost',
                'dbase' => trim($dbase, '`[]'),
                'database_user' => !empty($database_user) ? $database_user : '',
                'database_password' => isset($database_password) ? $database_password : '',
                'database_connection_charset' => $database_connection_charset,
                'database_charset' => $database_connection_charset,
                'table_prefix' => isset($table_prefix) ? $table_prefix : 'modx',
                'https_port' => isset ($https_port) ? $https_port : '443',
                'http_host' => defined('MODX_HTTP_HOST') ? MODX_HTTP_HOST : $this->config['http_host'],
                'site_sessionname' => isset ($site_sessionname) ? $site_sessionname : 'SN' . uniqid(''),
                'cache_disabled' => isset ($cache_disabled) && $cache_disabled ? 'true' : 'false',
                'inplace' => isset ($_POST['inplace']) ? 1 : 0,
                'unpacked' => isset ($_POST['unpacked']) ? 1 : 0,
                'config_options' => $config_options,
                'driver_options' => $driver_options,
            ),$this->config,$config);
        }
        return $this->config;
    }

}