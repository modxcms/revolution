<?php
/**
 * Loads the Import Resources page
 *
 * @package modx
 * @subpackage manager.system.import
 */
if (!$modx->hasPermission('import_static')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('system/import/index.tpl');