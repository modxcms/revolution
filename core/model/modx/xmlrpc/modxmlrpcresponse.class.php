<?php
/**
 * @package modx
 * @subpackage xmlrpc
 */
require_once MODX_CORE_PATH . 'model/modx/xmlrpc/xmlrpc.inc';
require_once MODX_CORE_PATH . 'model/modx/xmlrpc/xmlrpcs.inc';
require_once MODX_CORE_PATH . 'model/modx/xmlrpc/xmlrpc_wrappers.inc';
require_once MODX_CORE_PATH . 'model/modx/modresponse.class.php';

/**
 * Handles any XML-RPC resources and their response
 *
 * @package modx
 * @subpackage xmlrpc
 */
class modXMLRPCResponse extends modResponse {
    /**
     * The XML-RPC server attached to this response
     * @var xmlrpc_server
     * @access public
     */
    var $server= null;
    /**
     * A collection of services attached to this response
     * @var array
     * @access public
     */
    var $services= array ();

    function modXMLRPCResponse(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        parent :: __construct($modx);
    }

    /**
     * Output the content of the resource
     *
     * @access public
     * @param boolean $noEvent Unused.
     */
    function outputContent($options= array()) {
        if (!isset($options['rpc_type'])) $options['rpc_type']= 'XML';

        $resourceClass = 'mod' . $options['rpc_type'] . 'RPCResource';
        if (!is_a($this->modx->resource, $resourceClass)) {
            $this->modx->log(MODX_LOG_LEVEL_FATAL, 'Could not load ' . $options['rpc_type'] . '-RPC Server class.');
        }

        $this->modx->resource->process();
        $this->modx->resource->_output= $this->modx->resource->_content;

        /* collect any uncached element tags in the content and process them */
        $this->modx->getParser();
        $maxIterations= intval($this->modx->getOption('parser_max_iterations',null,10));
        $this->modx->parser->processElementTags('', $this->modx->resource->_output, true, false, '[[', ']]', array(), $maxIterations);
        $this->modx->parser->processElementTags('', $this->modx->resource->_output, true, true, '[[', ']]', array(), $maxIterations);

        if (!$this->getServer()) {
            $this->modx->log(MODX_LOG_LEVEL_FATAL, 'Could not load ' . $options['rpc_type'] . '-RPC Server.');
        }

        $this->server->service();
        @ ob_end_flush();
        while (@ ob_end_clean()) {}
        exit();
    }

    /**
     * Gets the XML-RPC server for this response
     *
     * @access public
     * @param boolean $execute Whether or not to execute the server as well as
     * load it
     * @return boolean True if the server initialized an instance correctly
     */
    function getServer($execute= false) {
        if ($this->server === null || !is_a($this->server, 'xmlrpc_server')) {
            $this->server= new xmlrpc_server($this->services, $execute);
        }
        return is_a($this->server, 'xmlrpc_server');
    }

    /**
     * Registers a service to this response
     *
     * @access public
     * @param string $key The name of the service
     * @param string $signature The signature of the service
     */
    function registerService($key, $signature) {
        $this->services[$key]= $signature;
    }

    /**
     * Unregisters a service from this response
     *
     * @access public
     * @param string $key The name of the service
     */
    function unregisterService($key) {
        unset($this->services[$key]);
    }
}