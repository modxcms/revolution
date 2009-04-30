<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {extends} plugin
 *
 * Type:     function<br>
 * Name:     extends<br>
 * Purpose:  template inheritance
 * @author Matthias Kestenholz <mk@spinlock.ch>, Moritz Zumb�hl <mail@momoetomo.ch>
 * @param array
 * @param Smarty
 * @return string|null 
 */
function smarty_function_extends($params, &$smarty)
{
//    $ss = $smarty->get_template_vars('_modx_smarty_instance');
	if (isset($params['template'])) {
		$smarty->_derived = $params['template'];
	} else {
		$smarty->_derived = $params['file'];
	}
//    echo ('<pre>' . print_r($smarty, 1) . '</pre>');
}

/* vim: set expandtab: */

?>