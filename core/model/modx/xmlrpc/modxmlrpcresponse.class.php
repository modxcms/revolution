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
    public $server= null;
    /**
     * A collection of services attached to this response
     * @var array
     * @access public
     */
    public $services= array ();

    /**
     * Output the content of the resource
     *
     * @param array $options An array of options for the output
     */
    public function outputContent(array $options= array()) {
        if (empty($options['rpc_type'])) $options['rpc_type']= 'XML';

        $resourceClass = 'mod' . $options['rpc_type'] . 'RPCResource';
        if (!($this->modx->resource instanceof $resourceClass)) {
            $this->modx->log(modX::LOG_LEVEL_FATAL, 'Could not load ' . $options['rpc_type'] . '-RPC Server class.');
        }

        $this->modx->resource->process();
        $this->modx->resource->_output= $this->modx->resource->_content;

        /* collect any uncached element tags in the content and process them */
        $this->modx->getParser();
        $maxIterations= intval($this->modx->getOption('parser_max_iterations',null,10));
        $this->modx->parser->processElementTags('', $this->modx->resource->_output, true, false, '[[', ']]', array(), $maxIterations);
        $this->modx->parser->processElementTags('', $this->modx->resource->_output, true, true, '[[', ']]', array(), $maxIterations);

        if (!$this->getServer()) {
            $this->modx->log(modX::LOG_LEVEL_FATAL, 'Could not load ' . $options['rpc_type'] . '-RPC Server.');
        }

        $this->server->service();
        ob_get_level() && @ob_end_flush();
        while (ob_get_level() && @ob_end_clean()) {}
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
    public function getServer($execute= false) {
        if ($this->server === null || !($this->server instanceof xmlrpc_server)) {
            $this->server= new xmlrpc_server($this->services, $execute);
        }
        return $this->server instanceof xmlrpc_server;
    }

    /**
     * Registers a service to this response
     *
     * @access public
     * @param string $key The name of the service
     * @param string $signature The signature of the service
     */
    public function registerService($key, $signature) {
        $this->services[$key]= $signature;
    }

    /**
     * Unregisters a service from this response
     *
     * @access public
     * @param string $key The name of the service
     */
    public function unregisterService($key) {
        unset($this->services[$key]);
    }
}