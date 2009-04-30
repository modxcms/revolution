<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {block}{/block} block plugin
 *
 * Type:     block function<br>
 * Name:     block<br>
 * Purpose:  process block for template inheritance
 * @author Matthias Kestenholz <mk@spinlock.ch>, Moritz Zumb�hl <mail@momoetomo.ch>
 * @param string contents of the block
 * @param Smarty clever simulation of a method
 * @return string string $content re-formatted
 */
function smarty_block_modblock($params, $content, &$smarty, &$repeat)
{
	if ($content === NULL || !isset ($params['name'])) return;
	$name = $params['name'];
//	$ss = $smarty->get_template_vars('_modx_smarty_instance');
	if (!isset($smarty->_blocks[$name]))
		$smarty->_blocks[$name] = $content;
	return $smarty->_blocks[$name];
}

/* vim: set expandtab: */

?>
