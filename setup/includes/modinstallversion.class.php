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
    public $results = array();
    public $version = '';

    function __construct(modInstallRunner &$runner) {
        $this->install =& $runner->install;
        $this->runner =& $runner;
        $this->_getVersion();
    }

    /**
     * Creates tables for installation
     *
     * @access public
     * @param string|array $class A class name or array of class names
     * @return boolean True if successful
     */
    public function createTable($class) {
        if (is_array($class)) {
            $dbcreated = true;
            foreach ($class as $c) {
                $dbcreated = $this->createTable($c);
            }
            return $dbcreated;
        } else {
            if (!$dbcreated = $this->install->xpdo->manager->createObjectContainer($class)) {
                $this->runner->addResult(modInstallRunner::RESULT_FAILURE,'<p class="notok">' . $this->install->lexicon('table_err_create',array('class' => $class)) . '</p>');
                return false;
            } else {
                $this->runner->addResult(modInstallRunner::RESULT_SUCCESS,'<p class="ok">' . $this->install->lexicon('table_created',array('class' => $class)) . '</p>');
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
    public function install() {
        $this->results = array();

        $connected = $this->install->xpdo->connect();
        if ($connected) {
            $this->install->xpdo->getManager();
            $this->install->lexicon->load('upgrades');
            $scripts = $this->_getUpgradeScripts();
            $driver =& $this->install->driver;
            $xpdo =& $this->install->xpdo;
            $modx =& $this->install->xpdo;

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
     * @param string $class The class being operated on.
     * @param string $description The description of the operation.
     * @param string|callable $callable A callable function or string representing a SQL command.
     * @param array $params Optional parameters to be passed to a callable function.
     * @return boolean True if successful
     */
    public function processResults($class,$description,$callable,array $params=array()) {
        $result = false;
        if (is_callable($callable)) {
            $result = call_user_func_array($callable, $params);
        } elseif (is_string($callable)) {
            $result = $this->install->xpdo->exec($callable);
        }
        if ($result === false) {
            $this->runner->addResult(modInstallRunner::RESULT_WARNING,'<p class="notok">'.$this->install->lexicon('err_update_table',array('class' => $class)).'<br /><small>' . nl2br(print_r($this->install->xpdo->errorInfo(), true)) . '</small></p>');
            return false;
        } else {
            $this->runner->addResult(modInstallRunner::RESULT_SUCCESS,'<p class="ok">'.$this->install->lexicon('table_updated',array('class' => $class)).'<br /><small>' . $description . '</small></p>');
            return true;
        }
    }


    /**
     * Gets an array list of all applicable upgrade scripts
     *
     * @access private
     * @return array An array of script filenames to load
     */
    private function _getUpgradeScripts() {
        $scripts = array();
        $path = dirname(__FILE__).'/upgrades/'.$this->install->settings->get('database_type','mysql').'/';
        $sc = '';
        if (is_dir($path)) {
            $dir = dir($path);
            while (false !== ($script = $dir->read())) {
                if (is_dir($path.$script)) continue;
                $sc = str_replace('.php','',$script);

                if (version_compare($this->version,$sc,'<=')) {
                    $scripts[] = $path.$sc.'.php';
                }
            }
        }
        return $scripts;
    }

    /**
     * Grabs the version from the installation
     *
     * @access private
     * @return string The full version of the MODX installation
     */
    private function _getVersion() {
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