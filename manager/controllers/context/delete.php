<?php
/**
 * Loads the context delete processor, via the controller.
 * This should be deprecated - use ajax connectors instead.
 *
 * @package modx
 * @subpackage manager.context
 * @deprecated
 */
if (!$modx->hasPermission('delete_context')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('context/remove.php');