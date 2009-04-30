<?php
/**
 * modConnectorRequest
 *
 * @package modx
 */

/** Make sure the parent class is included. */
require_once MODX_CORE_PATH . 'model/modx/modmanagerrequest.class.php';

/**
 * This is the Connector Request handler for MODx.
 *
 * It serves to redirect connector requests to their appropriate processors,
 * while validating for security.
 *
 * @package modx
 */
class modConnectorRequest extends modManagerRequest {
    /**
     * The base subdirectory location of the requested action.
     * @var string
     * @access public
     */
    var $location;

    function modConnectorRequest(&$modx) {
        $this->__construct($modx);
    }
    /**
     * Construct the object, and make sure the default processor path is set.
     *
     * @param MODx $modx A reference to the MODx instance.
     */
    function __construct(&$modx) {
        parent::__construct($modx);
    }

    function initialize() {
        if (!empty($this->modx->config['manager_language'])) {
            $this->modx->cultureKey= $this->modx->config['manager_language'];
        }

        /* load default core cache file of lexicon strings */
        $this->modx->lexicon->load('core:default');

        if ($this->modx->actionMap === null || !is_array($this->modx->actionMap)) {
            $this->loadActionMap();
        }

        return true;
    }

    /**
     * Handles all requests specified by the action param and prepares for loading.
     *
     * @access public
     * @param string $location The base subdirectory in which to look for the processor.
     * @param string $action The requested processor to load.
     */
    function handleRequest($options = array()) {
        if (isset($options['action']) && !is_string($options['action'])) return false;
        if ((!isset($options['action']) || $options['action'] == '') && isset($_REQUEST['action'])) {
            $options['action'] = $_REQUEST['action'];
        }
        $options['action'] = strtolower($options['action']);

        /* handle stay options */
        if (isset($_POST['modx-ab-stay'])) {
            $_SESSION['modx.stay'] = $_POST['modx-ab-stay'];
        }

        $this->loadErrorHandler();

        /* validate manager session
        if (!isset ($_SESSION['mgrValidated']) && $action != 'login' && $location != 'security') {
            $this->modx->error->failure($this->modx->lexicon('access_denied'));
            exit();
        } */

        /* Cleanup action and store. */
        $this->prepareResponse($options);
    }

    /**
     * Prepares the output with the specified processor.
     *
     * @param array $options An array of options
     */
    function prepareResponse($options = array()) {
        $procDir = !empty($options['processors_path']) ? $options['processors_path'] : '';
        $this->setDirectory($procDir);
        $this->modx->response->outputContent($options);
    }

    /**
     * Sets the directory to load the processors from
     *
     * @param string $dir The directory to load from
     */
    function setDirectory($dir = '') {
        if (!$this->modx->getResponse('modConnectorResponse')) {
            $this->modx->log(MODX_LOG_LEVEL_FATAL, 'Could not load response class: modConnectorResponse');
        }
        $this->modx->response->setDirectory($dir);
    }
}