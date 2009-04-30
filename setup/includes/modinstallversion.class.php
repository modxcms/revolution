<?php
/*
 * MODx Revolution
 *
 * Copyright 2006, 2007, 2008 by the MODx Team.
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
 * modInstallVersion
 *
 * @package setup
 */
/**
 * Handles version-specific upgrades for Revolution
 *
 * @package setup
 */
class modInstallVersion {
    var $results = array();

    function modInstallVersion(&$install) {
        $this->__construct($install);
    }
    function __construct(&$install) {
        $this->install = $install;
        $this->_getVersion();
    }

    /**
     * Creates tables for installation
     *
     * @access public
     * @param string/array $class A class name or array of class names
     * @return boolean True if successful
     */
    function createTable($class) {
        if (is_array($class)) {
            $dbcreated = true;
            foreach ($class as $c) {
                $dbcreated = $this->createTable($c);
            }
            return $dbcreated;
        } else {
            if (!$dbcreated = $this->install->xpdo->manager->createObjectContainer($class)) {
                $this->results[] = array (
                    'class' => 'failed',
                    'msg' => '<p class="notok">' . sprintf($this->install->lexicon['table_err_create'],$class) . '</p>'
                );
                return false;
            } else {
                $this->results[] = array (
                    'class' => 'success',
                    'msg' => '<p class="ok">' . sprintf($this->install->lexicon['table_created'],$class) . '</p>'
                );
                return true;
            }
        }
    }

    /**
     * Run the version-specific install scripts
     *
     * @access public
     * @return array An array of results
     */
    function install() {
        $this->results = array();

        $this->install->xpdo->setPackage('modx', MODX_CORE_PATH . 'model/');
        $connected = $this->install->xpdo->connect();
        if ($connected) {
            $this->install->xpdo->getManager();
            $scripts = $this->_getUpgradeScripts();

            foreach ($scripts as $script) {
                if (file_exists($script)) {
                    include_once $script;
                }
            }
        }

        return $this->results;
    }

    /**
     * Process a SQL command
     *
     * @access public
     * @return boolean True if successful
     */
    function processResults($class,$description,$sql) {
        if (!$this->install->xpdo->exec($sql)) {
            $this->results[] = array (
                'class' => 'warning',
                'msg' => '<p class="notok">Error upgrading table for class ' . $class . '<br /><small>' . nl2br(print_r($this->install->xpdo->errorInfo(), true)) . '</small></p>'
            );
            return false;
        } else {
            $this->results[] = array (
                'class' => 'success',
                'msg' => '<p class="ok">Successfully upgraded table for class ' . $class . '<br /><small>' . $description . '</small></p>'
            );
            return true;
        }
    }


    /**
     * Gets an array list of all applicable upgrade scripts
     *
     * @access private
     * @return array An array of script filenames to load
     */
    function _getUpgradeScripts() {
        $scripts = array();
        $path = dirname(__FILE__).'/upgrades/';
        $sc = '';
        $dir = dir($path);
        while (false !== ($script = $dir->read())) {
            if (is_dir($path.$script)) continue;
            $sc = str_replace('.php','',$script);

            if (version_compare($this->version,$sc,'<=')) {
                $scripts[] = $path.$sc.'.php';
            }
        }
        return $scripts;
    }

    /**
     * Grabs the version from the installation
     *
     * @access private
     * @return string The full version of the MODx installation
     */
    function _getVersion() {
        $installVersion = '2.0.0-alpha-1';
        if ($settings_version = $this->install->xpdo->getObject('modSystemSetting', array(
                'key' => 'settings_version'
            ))) {
            $installVersion = $settings_version->get('value');
        }
        $this->version = $installVersion;
        return $this->version;
    }
}