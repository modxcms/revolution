<?php
/**
 * @package setup
 */
$navbar= '
<button name="cmdnext" onclick="return doAction(\'welcome\');" >'.$install->lexicon['next'].'</button>
';
$this->parser->assign('navbar', $navbar);

$this->parser->display('welcome.tpl');