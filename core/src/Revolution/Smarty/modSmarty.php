<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Smarty;


use Exception;
use MODX\Revolution\modX;
use Smarty;
use xPDO\xPDO;

/**
 * An extension of the Smarty class for use with modX.
 *
 * Automatically sets appropriate configuration variables for Smarty in
 * the MODX context.
 *
 * @package MODX\Revolution\Smarty
 */
class modSmarty extends Smarty
{
    /**
     * A reference to the modX instance
     *
     * @var modX
     */
    public $modx = null;
    /**
     * A reference to the Smarty instance
     *
     * @var Smarty
     */
    public $smarty;
    /**
     * Any custom blocks loaded
     *
     * @var array
     */
    public $_blocks;
    /**
     * The derived block loaded
     *
     * @var mixed
     */
    public $_derived;

    /**
     * @param modX  $modx   A reference to the modX object
     * @param array $params An array of configuration parameters
     */
    public function __construct(modX $modx, array $params = [])
    {
        parent:: __construct();
        $this->modx = &$modx;

        /* set up configuration variables for Smarty. */
        $this->template_dir = $modx->getOption('manager_path') . 'templates/';
        $this->compile_dir = $modx->getOption(xPDO::OPT_CACHE_PATH) . 'mgr/smarty/';
        $this->config_dir = $modx->getOption('core_path') . 'model/smarty/configs';
        $this->plugins_dir = [
            $this->modx->getOption('core_path') . 'vendor/smarty/smarty/libs/plugins',
        ];
        $this->caching = false;

        foreach ($params as $paramKey => $paramValue) {
            $this->$paramKey = $paramValue;
        }

        if (!is_dir($this->compile_dir)) {
            $this->modx->getCacheManager();
            $this->modx->cacheManager->writeTree($this->compile_dir);
        }

        $this->muteUndefinedOrNullWarnings();
        $this->assign('app_name', 'MODX');

        $this->_blocks = [];
        $this->_derived = null;
    }

    /**
     * Sets the cache path for this Smarty instance
     *
     * @access public
     *
     * @param string $path The path to set. Defaults to '', which in turn
     *                     defaults to $this->modx->cachePath.
     */
    public function setCachePath($path = '')
    {
        $path = $this->modx->getOption(xPDO::OPT_CACHE_PATH) . $path;
        if (!is_dir($path)) {
            $this->modx->getCacheManager();
            $this->modx->cacheManager->writeTree($path);
        }
        $this->compile_dir = $path;
    }

    /**
     * Sets the template path for this Smarty instance
     *
     * @access public
     *
     * @param string $path The path to set.
     *
     * @return boolean True if successful
     */
    public function setTemplatePath($path = '')
    {
        if (empty($path)) {
            return false;
        } elseif (!is_array($path)) {
            $path = [$path];
        }

        $this->setTemplateDir($path);

        return true;
    }

    /**
     * Display a template by echoing the output of a Smarty::fetch().
     *
     * @param string|object $template   the resource handle of the template file or template object
     * @param mixed         $cache_id   cache id to be used with this template
     * @param mixed         $compile_id compile id to be used with this template
     * @param object        $parent     next higher level of Smarty variables
     */
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        try {
            echo $this->fetch($template, $cache_id, $compile_id, $parent);
        } catch (Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $e->getMessage());
        }
    }

    /**
     * @param null $template
     * @param null $cache_id
     * @param null $compile_id
     * @param null $parent
     *
     * @return string
     * @throws Exception
     */
    public function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        $output = '';
        try {
            $output = parent::fetch($template, $cache_id, $compile_id, $parent);
        } catch (Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $e->getMessage());
        }

        return $output;
    }
}
