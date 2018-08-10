<?php
/**
 * @package modx
 * @subpackage jsonrpc
 */

/**
 * JSON extension to the PHP-XMLRPC lib: server components
 *
 * For more info see:
 * http://www.json.org/
 * http://json-rpc.org/
 *
 * @author Gaetano Giunta
 * @version $Id: jsonrpcs.inc,v 1.10 2007/02/15 21:48:38 ggiunta Exp $
 * @copyright (c) 2005 G. Giunta
 *
 * @todo implement dispatching of multicall requests, json way
 * @todo test system.XXX methods, with special care to multicall
 * @todo support for 'ping' calls, i.e. if id is null, echo back nothing
 **/

	// JSON RPC Server class
	// requires: jsonrpc.inc, xmlrpcs.inc, xmlrpc.inc

	// add to list of supported capaibilities the jsonrpc spec
	$GLOBALS['xmlrpcs_capabilities']['json-rpc'] = new xmlrpcval(array(
			'specUrl' => new xmlrpcval('http://json-rpc.org/wiki/specification', 'string'),
			'specVersion' => new xmlrpcval(1, 'int')
		), 'struct');

	// NB: if building jsonrpc-only webservers, you should at least undeclare the xmlrpc capability:
	// unset($GLOBALS['xmlrpcs_capabilities']['xmlrpc']);

	class jsonrpc_server extends xmlrpc_server
	{
		//var $allow_system_funcs = false;
		var $functions_parameters_type='jsonrpcvals';

		function serializeDebug($charset_encoding='')
		{
			$out = '';
			if ($this->debug_info != '')
			{
				$out .= "/* SERVER DEBUG INFO (BASE64 ENCODED):\n".base64_encode($this->debug_info)."\n*/\n";
			}
			if ($GLOBALS['_xmlrpc_debuginfo'] != '')
			{
				$out .= "/* DEBUG INFO:\n\n" . json_encode_entitites($GLOBALS['_xmlrpc_debuginfo'], null, $charset_encoding) . "\n*/\n";
			}
			return $out;
		}

		/**
		* Note: syntax differs from overridden method, by adding an ID param
		* @access private
		*/
		function execute($m, $params=null, $paramtypes=null, $msgID=null)
		{
			if (is_object($m))
			{
				// watch out: if $m is an xmlrpcmsg obj, this will raise a warning: no id memeber...
				$methName = $m->method();
				$msgID = $m->id;
			}
			else
			{
				$methName = $m;
			}
			$sysCall = $this->allow_system_funcs && @ereg("^system\.", $methName);
			$dmap = $sysCall ? $GLOBALS['_xmlrpcs_dmap'] : $this->dmap;

			if(!isset($dmap[$methName]['function']))
			{
				// No such method
				return new jsonrpcresp(0,
					$GLOBALS['xmlrpcerr']['unknown_method'],
					$GLOBALS['xmlrpcstr']['unknown_method']);
			}

			// Check signature
			if(isset($dmap[$methName]['signature']))
			{
				$sig = $dmap[$methName]['signature'];
				if (is_object($m))
				{
					list($ok, $errstr) = $this->verifySignature($m, $sig);
				}
				else
				{
				list($ok, $errstr) = $this->verifySignature($paramtypes, $sig);
				}
				if(!$ok)
				{
					// Didn't match.
					return new jsonrpcresp(
						0,
						$GLOBALS['xmlrpcerr']['incorrect_params'],
						$GLOBALS['xmlrpcstr']['incorrect_params'] . ": ${errstr}"
					);
				}
			}

			$func = $dmap[$methName]['function'];
			// let the 'class::function' syntax be accepted in dispatch maps
			if(is_string($func) && strpos($func, '::'))
			{
				$func = explode('::', $func);
			}
			// verify that function to be invoked is in fact callable
			if(!is_callable($func))
			{
				error_log("XML-RPC: jsonrpc_server::execute: function $func registered as method handler is not callable");
				return new jsonrpcresp(
					0,
					$GLOBALS['xmlrpcerr']['server_error'],
					$GLOBALS['xmlrpcstr']['server_error'] . ": no function matches method"
				);
			}

			// If debug level is 3, we should catch all errors generated during
			// processing of user function, and log them as part of response
			if($this->debug > 2)
			{
				$GLOBALS['_xmlrpcs_prev_ehandler'] = set_error_handler('_xmlrpcs_errorHandler');
			}
			if (is_object($m))
			{
				if($sysCall)
				{
					$r = call_user_func($func, $this, $m);
				}
				else
				{
					$r = call_user_func($func, $m);
				}
				if (!($r instanceof xmlrpcresp))
				{
					error_log("XML-RPC: jsonrpc_server::execute: function $func registered as method handler does not return an xmlrpcresp object");
					if ($r instanceof xmlrpcval) {
						$r = new jsonrpcresp($r);
					}
					else
					{
						$r = new jsonrpcresp(
							0,
							$GLOBALS['xmlrpcerr']['server_error'],
							$GLOBALS['xmlrpcstr']['server_error'] . ": function does not return jsonrpcresp or xmlrpcresp object"
						);
					}
                }
			}
			else
			{
				// call a 'plain php' function
				if($sysCall)
				{
					array_unshift($params, $this);
					$r = call_user_func_array($func,$params);
				}
				else
				{
					// 3rd API convention for method-handling functions: EPI-style
					if ($this->functions_parameters_type == 'epivals')
					{
						$r = call_user_func_array($func, array($methName, $params, $this->user_data));
						// mimic EPI behaviour: if we get an array that looks like an error, make it
						// an eror response
						if (is_array($r) && array_key_exists('faultCode', $r) && array_key_exists('faultString', $r))
						{
							$r = new jsonrpcresp(0, (integer)$r['faultCode'], (string)$r['faultString']);
						}
						else
						{
							// functions using EPI api should NOT return resp objects,
							// so make sure we encode the return type correctly
							$r = new jsonrpcresp(php_xmlrpc_encode($r, array('extension_api')));
						}
					}
					else
					{
						$r = call_user_func_array($func, $params);
					}
				}
				// the return type can be either an xmlrpcresp object or a plain php value...
				if (!($r instanceof xmlrpcresp))
				{
					// what should we assume here about automatic encoding of datetimes
					// and php classes instances???
					$r = new jsonrpcresp(php_jsonrpc_encode($r));
				}
			}
			// here $r is either an xmlrpcresp or jsonrpcresp
			if (!($r instanceof jsonrpcresp))
			{

				// dirty trick: user has given us back an xmlrpc response,
				// since he had an existing xmlrpc server with boatloads of code.
				// Be nice to him, and serialize the xmlrpc stuff into JSON.
				// We also override the content_type of the xmlrpc response,
				// but lack knoweledge of intented response charset...
				$r->content_type = 'application/json';
				$r->payload = serialize_jsonrpcresp($r, $msgID);
			}
			else
			{
				$r->id = $msgID;
			}

			if($this->debug > 2)
			{
				// note: restore the error handler we found before calling the
				// user func, even if it has been changed inside the func itself
				if($GLOBALS['_xmlrpcs_prev_ehandler'])
				{
					set_error_handler($GLOBALS['_xmlrpcs_prev_ehandler']);
				}
				else
				{
					restore_error_handler();
				}
			}
			return $r;
		}

		/**
		* @access private
		*/
		function parseRequest($data, $content_encoding='')
		{
			$GLOBALS['_xh']=array();

			if (!jsonrpc_parse_req($data, $this->functions_parameters_type == 'phpvals' || $this->functions_parameters_type == 'epivals', false, $content_encoding))
			{
				$r = new jsonrpcresp(0,
					$GLOBALS['xmlrpcerr']['invalid_request'],
					$GLOBALS['xmlrpcstr']['invalid_request'] . ' ' . $GLOBALS['_xh']['isf_reason']);
			}
			else
			{
				if ($this->functions_parameters_type == 'phpvals' || $this->functions_parameters_type == 'epivals')
				{
					if($this->debug > 1)
					{
						$this->debugmsg("\n+++PARSED+++\n".var_export($GLOBALS['_xh']['params'], true)."\n+++END+++");
					}
					$r = $this->execute($GLOBALS['_xh']['method'], $GLOBALS['_xh']['params'], $GLOBALS['_xh']['pt'], $GLOBALS['_xh']['id']);
				}
				else
				{
					// build an xmlrpcmsg object with data parsed from xml
					$m = new jsonrpcmsg($GLOBALS['_xh']['method'], 0, $GLOBALS['_xh']['id']);
					// now add parameters in
					/// @todo for more speeed, we could just substitute the array...
					for($i = 0; $i < sizeof($GLOBALS['_xh']['params']); $i++)
					{
						$m->addParam($GLOBALS['_xh']['params'][$i]);
					}

					if($this->debug > 1)
					{
						$this->debugmsg("\n+++PARSED+++\n".var_export($m, true)."\n+++END+++");
					}

					$r = $this->execute($m);
				}
			}
			return $r;
		}

		/**
		* No xml header generated by the server, since we are sending json
		* @access private
		*/
		function xml_header($charset_encoding='')
		{
			return '';
		}

	}
?>
