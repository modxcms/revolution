<?php
/**
 * modConnectorRequest
 *
 * @package modx
 */
require_once MODX_CORE_PATH . 'model/modx/modmanagerrequest.class.php';
/**
 * This is the Connector Request handler for MODX.
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
    public $location;

    /**
     * Initializes the connector request, loading the proper context, culture and lexicon; also loads the action map
     * @return bool
     */
    public function initialize() {
        if ($this->modx && is_object($this->modx->context) && $this->modx->context instanceof modContext) {
            $ctx = $this->modx->context->get('key');
            if (!empty($ctx) && $ctx == 'mgr') {
                $ml = $this->modx->getOption('manager_language',null,$this->modx->getOption('cultureKey',null,'en'));
                if (!empty($ml)) {
                    $this->modx->setOption('cultureKey',$ml);
                }
            }
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
     * @param array $options An array of request options
     * @return boolean
     */
    public function handleRequest(array $options = array()) {
        if (isset($options['action']) && !is_string($options['action'])) return false;
        if ((!isset($options['action']) || $options['action'] == '') && isset($_REQUEST['action'])) {
            $options['action'] = $_REQUEST['action'];
        }
        $options['action'] = strtolower($options['action']);

        $this->loadErrorHandler();

        /* Cleanup action and store. */
        $this->prepareResponse($options);

        return true;
    }

    /**
     * Prepares the output with the specified processor.
     *
     * @param array $options An array of options
     */
    public function prepareResponse(array $options = array()) {
        $procDir = !empty($options['processors_path']) ? $options['processors_path'] : '';
        $this->setDirectory($procDir);
        $this->modx->response->outputContent($options);
    }

    /**
     * Sets the directory to load the processors from
     *
     * @param string $dir The directory to load from
     */
    public function setDirectory($dir = '') {
        if (!$this->modx->getResponse('modConnectorResponse')) {
            $this->modx->log(modX::LOG_LEVEL_FATAL, 'Could not load response class: modConnectorResponse');
        }
        $this->modx->response->setDirectory($dir);
    }
}