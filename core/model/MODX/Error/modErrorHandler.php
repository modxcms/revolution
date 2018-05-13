<?php

namespace MODX\Error;

use MODX\MODX;

/**
 * The base PHP error handler class for the MODX framework.
 *
 * @package modx
 * @subpackage error
 */
class modErrorHandler
{
    /**
     * @var MODX A reference to the MODX instance.
     */
    public $modx = null;

    /**
     * @var array A stack of errors.
     */
    public $stack = null;


    /**
     * Construct a new modErrorHandler instance.
     *
     * @param MODX &$modx A reference to the MODX instance.
     * @param array $stack A stack of errors that can be passed in.  Send a non-array value to
     * prevent any errors from being recorded in the stack.
     */
    function __construct(MODX &$modx, array $stack = [])
    {
        $this->modx = &$modx;
        $this->stack = $stack;
    }


    /**
     * Handles any recoverable PHP errors or calls to trigger_error().
     *
     * @param integer $errno An integer number indicating the type of error.
     * @param string $errstr A description of the error.
     * @param string $errfile The filename in which the error occured.
     * @param integer $errline The line number in the file where the error occured.
     * @param array $errcontext A copy of all variables and their values available at the time the
     * error occured and in the scope of the script being executed.
     *
     * @return boolean True if the error was handled or false if the default PHP error handler
     * should be invoked to handle it.
     */
    public function handleError($errno, $errstr, $errfile = null, $errline = null, $errcontext = null)
    {
        if (error_reporting() == 0) {
            return false;
        }
        $error = [
            'errno' => $errno,
            'errstr' => $errstr,
            'errfile' => $errfile,
            'errline' => $errline,
        ];
        switch ($errno) {
            case E_USER_ERROR:
                $handled = true;
                $errmsg = 'User error: ' . $errstr;
                $this->modx->log(MODX::LOG_LEVEL_ERROR, $errmsg, '', '', $errfile, $errline);
                break;
            case E_WARNING:
                $handled = true;
                $errmsg = 'PHP warning: ' . $errstr;
                $this->modx->log(MODX::LOG_LEVEL_ERROR, $errmsg, '', '', $errfile, $errline);
                break;
            case E_USER_WARNING:
                $handled = true;
                $errmsg = 'User warning: ' . $errstr;
                $this->modx->log(MODX::LOG_LEVEL_ERROR, $errmsg, '', '', $errfile, $errline);
                break;
            case E_NOTICE:
                $handled = true;
                $errmsg = 'PHP notice: ' . $errstr;
                $this->modx->log(MODX::LOG_LEVEL_WARN, $errmsg, '', '', $errfile, $errline);
                break;
            case E_USER_NOTICE:
                $handled = true;
                $errmsg = 'User notice: ' . $errstr;
                $this->modx->log(MODX::LOG_LEVEL_WARN, $errmsg, '', '', $errfile, $errline);
                break;
            case E_STRICT:
                $handled = true;
                $errmsg = 'E_STRICT information: ' . $errstr;
                $this->modx->log(MODX::LOG_LEVEL_INFO, $errmsg, '', '', $errfile, $errline);

                return $handled;
                break;
            case E_RECOVERABLE_ERROR:
                $handled = true;
                $errmsg = 'Recoverable error: ' . $errstr;
                $this->modx->log(MODX::LOG_LEVEL_ERROR, $errmsg, '', '', $errfile, $errline);
                break;
            case E_DEPRECATED:
                $handled = true;
                $errmsg = 'PHP deprecated: ' . $errstr;
                $this->modx->log(MODX::LOG_LEVEL_WARN, $errmsg, '', '', $errfile, $errline);
                break;
            case E_USER_DEPRECATED:
                $handled = true;
                $errmsg = 'User deprecated: ' . $errstr;
                $this->modx->log(MODX::LOG_LEVEL_WARN, $errmsg, '', '', $errfile, $errline);
                break;
            default:
                $handled = false;
                $errmsg = 'Un-recoverable error ' . $errno . ': ' . $errstr;
                $this->modx->log(MODX::LOG_LEVEL_ERROR, $errmsg, '', '', $errfile, $errline);
                break;
        }
        if (is_array($this->stack)) array_push($this->stack, $error);

        return $handled;
    }


    /**
     * Converts an error to a readable string.
     *
     * @access public
     *
     * @param mixed $error The error to convert.
     *
     * @return string The parsed error string.
     */
    public function toString($error)
    {
        $string = '';
        if (is_array($error)) {
            $string = '<pre>' . print_r($error, true) . '</pre>';
        }

        return $string;
    }
}
