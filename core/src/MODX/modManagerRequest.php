<?php

namespace MODX;

use xPDO\Cache\xPDOCacheManager;
use xPDO\xPDO;
use MODX\Error\modError;

/**
 * Encapsulates the interaction of MODX manager with an HTTP request.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modManagerRequest extends modRequest
{
    /**
     * @var string The action to load.
     * @access public
     */
    public $action = null;
    /**
     * @deprecated 2.0.0 Use $modx->error instead.
     * @var modError The error handler for the request.
     * @access public
     */
    public $error = null;
    /**
     * @var string The REQUEST parameter to load actions by.
     * @access public
     */
    public $actionVar = 'a';
    /**
     * @var mixed The default action to load from.
     * @access public
     */
    public $defaultAction = 'welcome';

    public $namespace = 'core';
    public $namespaceVar = 'namespace';


    /**
     * Instantiates a modManagerRequest object.
     *
     * @param modX $modx
     */
    function __construct(modX & $modx)
    {
        parent:: __construct($modx);
        $this->initialize();
    }


    /**
     * Initializes the manager request.
     *
     * @access public
     * @return boolean True if successful.
     */
    public function initialize()
    {
        $this->sanitizeRequest();

        if (!defined('MODX_INCLUDES_PATH')) {
            define('MODX_INCLUDES_PATH', $this->modx->getOption('manager_path') . 'includes/');
        }

        /* load smarty template engine */
        $theme = $this->modx->getOption('manager_theme', null, 'default');
        $templatePath = $this->modx->getOption('manager_path') . 'templates/' . $theme . '/';
        if (!file_exists($templatePath)) { /* fallback to default */
            $templatePath = $this->modx->getOption('manager_path') . 'templates/default/';
        }
        $this->modx->getService('smarty', 'smarty.modSmarty', '', [
            'template_dir' => $templatePath,
        ]);
        /* load context-specific cache dir */
        $this->modx->smarty->setCachePath($this->modx->context->get('key') . '/smarty/' . $theme . '/');

        $this->modx->smarty->assign('_config', $this->modx->config);
        $this->modx->smarty->assignByRef('modx', $this->modx);

        if (!array_key_exists('a', $_REQUEST)) {
            $_REQUEST[$this->actionVar] = $this->modx->getOption('welcome_action', null, $this->defaultAction);
            $_REQUEST[$this->namespaceVar] = $this->modx->getOption('welcome_namespace', null, 'core');
        }

        /* send the charset header */
        header('Content-Type: text/html; charset=' . $this->modx->getOption('modx_charset'));

        /*
         * TODO: implement destroy active sessions if installing
         * TODO: implement regen session if not destroyed from install
         */

        /* include version info */
        if ($this->modx->version === null) $this->modx->getVersionData();

        $ml = $this->modx->getOption('manager_language', $_SESSION, $this->modx->getOption('cultureKey', null, 'en'));
        if (!empty($ml)) {
            $this->modx->setOption('cultureKey', $ml);
        }

        /* load default core cache file of lexicon strings */
        $this->modx->lexicon->load('core:default');

        return true;
    }


    /**
     * The primary MODX manager request handler (a.k.a. controller).
     *
     * @access public
     */
    public function handleRequest()
    {
        /* Load error handling class */
        $this->loadErrorHandler();
        $this->modx->invokeEvent('OnHandleRequest');

        /* save page to manager object. allow custom actionVar choice for extending classes. */
        $this->action = !empty($_REQUEST[$this->actionVar]) ? trim($_REQUEST[$this->actionVar]) : $this->defaultAction;
        $this->action = preg_replace("/[^A-Za-z0-9_\-\/]/", '', $this->action);
        $this->action = trim(trim(str_replace('//', '', $this->action), '/'));
        $this->namespace = !empty($_REQUEST[$this->namespaceVar]) ? trim($_REQUEST[$this->namespaceVar]) : 'core';
        $this->namespace = preg_replace("/[^A-Za-z0-9_\-\/]/", '', $this->namespace);
        $this->namespace = trim(trim(str_replace('//', '', $this->namespace), '/'));

        /* invoke OnManagerPageInit event */
        $this->modx->invokeEvent('OnManagerPageInit', [
            'action' => $this->action,
            'namespace' => $this->namespace,
        ]);

        $this->prepareResponse();
    }


    /**
     * This implementation adds register logging capabilities via $_POST vars
     * when the error handler is loaded.
     *
     * @param string $class
     */
    public function loadErrorHandler($class = 'modError')
    {
        parent::loadErrorHandler($class);
        $data = array_merge($_POST, [
            'register_class' => 'registry.modFileRegister',
        ]);
        $this->registerLogging($data);
    }


    /**
     * Loads the actionMap, and generates a cache file if necessary.
     *
     * @access public
     * @return boolean True if successful.
     */
    public function loadActionMap()
    {
        $loaded = false;
        $cacheKey = $this->modx->context->get('key') . '/actions';
        $map = $this->modx->cacheManager->get($cacheKey, [
            xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_action_map_key', null, 'action_map'),
            xPDO::OPT_CACHE_HANDLER => $this->modx->getOption('cache_action_map_handler', null, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer)$this->modx->getOption('cache_action_map_format', null, $this->modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ]);
        if (!$map) {
            $map = $this->modx->cacheManager->generateActionMap($cacheKey);
        }
        if ($map) {
            $this->modx->actionMap = $map;
            $loaded = true;
        }

        return $loaded;
    }


    /**
     * Prepares the MODX response to a mgr request that is being handled.
     *
     * @access public
     *
     * @param array $options An array of options
     */
    public function prepareResponse(array $options = [])
    {
        $this->modx->getResponse('modManagerResponse')
            ->outputContent($options);
    }
}
