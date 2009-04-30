<?php
/**
 * Loads the workspace package builder
 *
 * @package modx
 * @subpackage manager.workspaces
 */
if (!$modx->hasPermission('package_builder')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('workspaces/builder/index.tpl');