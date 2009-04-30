<?php
/**
 * Initializes the browser into the manager context
 *
 * @package modx
 * @subpackage manager.browser
 */
@include(dirname(dirname(dirname(__FILE__))) . '/config.core.php');
if (!defined('MODX_CORE_PATH')) define('MODX_CORE_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/core/');
if (!include_once(MODX_CORE_PATH . 'model/modx/modx.class.php')) die();

/* instantiate the modX class with the appropriate configuration */
$modx= new modX();

/* set debugging/logging options */
$modx->setDebug(E_ALL & ~E_NOTICE);
$modx->setLogLevel(MODX_LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');

/* initialize the proper context */
$modx->initialize(isset ($_POST['login_context']) ? $_POST['login_context'] : 'mgr');

/* handle the request */
$modx->getRequest();

$modx->getService('smarty', 'smarty.modSmarty', '', array ('template_dir' => $modx->config['manager_path'] . 'templates/' . $modx->config['manager_theme']));
$modx->smarty->assign('_config',$modx->config);
$modx->smarty->assign('_lang',$modx->lexicon->fetch());
$modx->smarty->assign_by_ref('modx',$modx);