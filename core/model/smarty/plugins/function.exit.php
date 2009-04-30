<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {exit} function plugin
 *
 * Type:     function<br>
 * Name:     exit<br>
 * Purpose:  Mimic exit
 * @author   Shaun McCormick <splittingred at gmail dot com>
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_exit($params, &$smarty)
{
    exit();
}

/* vim: set expandtab: */

?>
