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
class modRevolutionConfigReader extends modConfigReader {
    public function read(array $config = array()) {
        global $database_dsn, $database_type, $database_server, $dbase, $database_user, $database_password,
               $database_connection_charset, $table_prefix, $config_options, $driver_options;
        $database_connection_charset = 'utf8';

        /* get http host */
        $this->getHttpHost();

        @ob_start();
        $included = @ include MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
        @ob_end_clean();
        if ($included && isset ($dbase)) {
            $this->config['mgr_path'] = MODX_MANAGER_PATH;
            $this->config['connectors_path'] = MODX_CONNECTORS_PATH;
            $this->config['web_path'] = MODX_BASE_PATH;
            $this->config['context_mgr_path'] = MODX_MANAGER_PATH;
            $this->config['context_connectors_path'] = MODX_CONNECTORS_PATH;
            $this->config['context_web_path'] = MODX_BASE_PATH;

            $this->config['mgr_url'] = MODX_MANAGER_URL;
            $this->config['connectors_url'] = MODX_CONNECTORS_URL;
            $this->config['web_url'] = MODX_BASE_URL;
            $this->config['context_mgr_url'] = MODX_MANAGER_URL;
            $this->config['context_connectors_url'] = MODX_CONNECTORS_URL;
            $this->config['context_web_url'] = MODX_BASE_URL;

            $this->config['core_path'] = MODX_CORE_PATH;
            $this->config['processors_path'] = MODX_CORE_PATH.'model/modx/processors/';
            $this->config['assets_path'] = MODX_ASSETS_PATH;
            $this->config['assets_url'] = MODX_ASSETS_URL;

            $config_options = is_array($config_options) ? $config_options : array();
            $driver_options = is_array($driver_options) ? $driver_options : array();

            $this->config = array_merge(array(
                'database_type' => $database_type,
                'database_server' => $database_server,
                'dbase' => trim($dbase, '`[]'),
                'database_user' => $database_user,
                'database_password' => $database_password,
                'database_connection_charset' => $database_connection_charset,
                'database_charset' => $database_connection_charset,
                'table_prefix' => $table_prefix,
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