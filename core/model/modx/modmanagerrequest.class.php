<?php
/**
 * modManagerRequest
 *
 * @package modx
 */
require_once MODX_CORE_PATH . 'model/modx/modrequest.class.php';
/**
 * Encapsulates the interaction of MODx manager with an HTTP request.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modManagerRequest extends modRequest {
    /**
     * @var string The action to load.
     * @access public
     */
    public $action= null;
    /**
     * @deprecated 2.0.0 Use $modx->error instead.
     * @var modError The error handler for the request.
     * @access public
     */
    public $error= null;
    /**
     * @var string The REQUEST parameter to load actions by.
     * @access public
     */
    public $actionVar = 'a';
    /**
     * @var mixed The default action to load from.
     * @access public
     */
    public $defaultAction = 0;

    /**
     * Instantiates a modManagerRequest object.
     *
     * {@inheritdoc}
     */
    function __construct(modX & $modx) {
        parent :: __construct($modx);
        $this->initialize();
    }

    /**
     * Initializes the manager request.
     *
     * @access public
     * @return boolean True if successful.
     */
    public function initialize() {
        if (!defined('MODX_INCLUDES_PATH')) {
            define('MODX_INCLUDES_PATH',$this->modx->getOption('manager_path').'includes/');
        }

        /* load smarty template engine */
        $theme = $this->modx->getOption('manager_theme',null,'default');
        $templatePath = $this->modx->getOption('manager_path') . 'templates/' . $theme . '/';
        if (!file_exists($templatePath)) { /* fallback to default */
            $templatePath = $this->modx->getOption('manager_path') . 'templates/default/';
        }
        $this->modx->getService('smarty', 'smarty.modSmarty', '', array(
            'template_dir' => $templatePath,
        ));
        /* load context-specific cache dir */
        $this->modx->smarty->setCachePath($this->modx->context->get('key').'/smarty/'.$theme.'/');

        $this->modx->smarty->assign('_config',$this->modx->config);
        $this->modx->smarty->assign_by_ref('modx',$this->modx);

        /* send anti caching headers */
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        /* send the charset header */
        header('Content-Type: text/html; charset='.$this->modx->getOption('modx_charset'));

        /*
         * TODO: implement destroy active sessions if installing
         * TODO: implement regen session if not destroyed from install
         */

        /* include version info */
        if ($this->modx->version === null) $this->modx->getVersionData();

        /* if not validated, load login page */
        $modx = & $this->modx;

        if ($this->modx->getOption('manager_language')) {
            $this->modx->setOption('cultureKey',$this->modx->getOption('manager_language'));
        }

        /* load default core cache file of lexicon strings */
        $this->modx->lexicon->load('core:default');

        if (!isset($this->modx->user) || !$this->modx->user->isAuthenticated('mgr')) {
            $theme = $this->modx->getOption('manager_theme',null,'default');
            include_once $this->modx->getOption('manager_path') . 'controllers/'.$theme.'/security/login.php';
            exit();
        } else {
            /* log user action */
            if (getenv('HTTP_CLIENT_IP')) {
                $ip= getenv('HTTP_CLIENT_IP');
            } else {
                if (getenv('HTTP_X_FORWARDED_FOR')) {
                    $ip= getenv('HTTP_X_FORWARDED_FOR');
                } else {
                    if (getenv('REMOTE_ADDR')) {
                        $ip= getenv('REMOTE_ADDR');
                    } else {
                        $ip= 'UNKNOWN';
                    }
                }
            }
            $_SESSION['ip']= $ip;
            $itemid= isset ($_REQUEST[$this->modx->getOption('request_param_id')]) ? $_REQUEST[$this->modx->getOption('request_param_id')] : 0;
            $lasthittime= time();
            $a= isset ($_REQUEST['a']) ? $_REQUEST['a'] : '';
            if ($a != 1 && $a != 0) {
                $itemid= intval($itemid);
                $activeUserTbl= $this->modx->getTableName('modActiveUser');
                $sql= "REPLACE INTO {$activeUserTbl} (internalKey, username, lasthit, action, id, ip) values(" . $this->modx->getLoginUserID('mgr') . ", '{$_SESSION['mgrShortname']}', '{$lasthittime}', '{$a}', {$itemid}, '{$ip}')";
                if (!$rs= $this->modx->exec($sql)) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, 'Error logging active user information! SQL: ' . $sql . "\n" . print_r($this->modx->errorInfo(), 1));
                }
            }
        }

        if ($this->modx->actionMap === null || !is_array($this->modx->actionMap)) {
            $this->loadActionMap();
        }

        return true;
    }

    /**
     * The primary MODx manager request handler (a.k.a. controller).
     *
     * @access public
     * @return boolean True if a request is handled without interruption.
     */
    public function handleRequest() {
        /* Load error handling class */
        $this->loadErrorHandler();

        $this->modx->invokeEvent('OnHandleRequest');

        /* save page to manager object. allow custom actionVar choice for extending classes. */
        $this->action = isset($_REQUEST[$this->actionVar]) ? $_REQUEST[$this->actionVar] : $this->defaultAction;

        /* invoke OnManagerPageInit event */
        $this->modx->invokeEvent('OnManagerPageInit',array('action' => $this->action));
        $this->prepareResponse();
    }

    /**
     * {@inheritdoc}
     *
     * This implementation adds register logging capabilities via $_POST vars
     * when the error handler is loaded.
     */
    public function loadErrorHandler($class = 'modError') {
        parent :: loadErrorHandler($class);
        $this->registerLogging($_POST);
    }

    /**
     * Loads the actionMap, and generates a cache file if necessary.
     *
     * @access public
     * @return boolean True if successful.
     */
    public function loadActionMap() {
        $loaded = false;
        $cacheKey= $this->modx->context->get('key') . '/actions';
        $map = $this->modx->cacheManager->get($cacheKey);
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
     * Prepares the MODx response to a mgr request that is being handled.
     *
     * @access public
     * @param array $options An array of options
     * @return boolean True if the response is properly prepared.
     */
    public function prepareResponse(array $options = array()) {
        if (!$this->modx->getResponse('modManagerResponse')) {
            $this->modx->log(modX::LOG_LEVEL_FATAL, 'Could not load response class.');
        }
        $this->modx->response->outputContent($options);
    }
}