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
 * @package setup
 */
include_once strtr(realpath(MODX_CORE_PATH . 'model/smarty/Smarty.class.php'),'\\','/');
require_once strtr(realpath(MODX_SETUP_PATH . 'includes/parser/modinstallparser.class.php'),'\\','/');
/**
 * An extension of the Smarty class for use with modX.
 *
 * Automatically sets appropriate configuration variables for Smarty in
 * the MODX context.
 * @package setup
 */
class modInstallSmarty extends Smarty implements modInstallParser {
    public $smarty = null;
    public $_blocks;
    public $_derived;

    function __construct(array $params= array ()) {
        parent :: __construct();

        /* Set up configuration variables for Smarty. */
        $this->template_dir = MODX_SETUP_PATH . 'templates/';
        $this->compile_dir  = MODX_CORE_PATH . 'cache/' . (MODX_CONFIG_KEY == 'config' ? '' : MODX_CONFIG_KEY . '/') . 'setup/smarty/';
        $this->config_dir   = MODX_CORE_PATH . 'model/smarty/configs';
        $this->plugins_dir  = array(
            MODX_CORE_PATH . 'model/smarty/plugins',
            MODX_CORE_PATH . 'model/smarty/modx',
        );
        $this->caching = false;

        foreach ($params as $paramKey => $paramValue) {
            $this->$paramKey= $paramValue;
        }

        if (!is_dir($this->compile_dir) || !is_writable($this->compile_dir)) $this->writeTree($this->compile_dir, '0777');

        $this->set('app_name','MODX');

        $this->_blocks = array();
        $this->_derived = null;
    }


    public function render($tpl) {
        return $this->fetch($tpl);
    }

    
    public function set($key,$value) {
        return $this->assign($key,$value);
    }


    /**
     * Recursively writes a directory tree of files to the filesystem
     *
     * @access public
     * @param string $dirname The directory to write
     * @return boolean Returns true if the directory was successfully written.
     */
    public function writeTree($dirname) {
        $written= false;
        if (!empty ($dirname)) {
            $dirname= strtr(trim($dirname), '\\', '/');
            if ($dirname{strlen($dirname) - 1} == '/') $dirname = substr($dirname, 0, strlen($dirname) - 1);
            if (is_dir($dirname) || (is_writable(dirname($dirname)) && mkdir($dirname))) {
                $written= true;
            } elseif (!$this->writeTree(dirname($dirname))) {
                $written= false;
            } else {
                $written= mkdir($dirname);
            }
        }
        return $written;
    }
}