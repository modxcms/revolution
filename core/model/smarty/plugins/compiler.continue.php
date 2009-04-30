<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {continue} compiler function plugin
 *
 * Type:     compiler function<br>
 * Name:     continue<br>
 * Purpose:  mimics continue in a for loop
 * @author Shaun McCormick <splittingred at gmail dot com>
 * @param string containing var-attribute and value-attribute
 * @param Smarty_Compiler
 */
function smarty_compiler_continue($tag_attrs, &$compiler)
{
    return "\ncontinue;";
}

/* vim: set expandtab: */

?>