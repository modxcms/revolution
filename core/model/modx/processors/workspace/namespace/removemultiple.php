<?php
/**
 * Removes namespaces.
 *
 * @param string $name The name of the namespace.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.workspace.namespace
 */
if (!$modx->hasPermission('namespaces')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace','namespace');

if (empty($scriptProperties['namespaces'])) {
    return $modx->error->failure($modx->lexicon('namespace_err_ns'));
}
$namespaceIds = explode(',',$scriptProperties['namespaces']);

foreach ($namespaceIds as $namespaceId) {
    /** @var modNamespace $namespace */
    $namespace = $modx->getObject('modNamespace',$namespaceId);
    if (empty($namespace)) { continue; }

    if ($namespace->get('name') == 'core') continue;

    if ($namespace->remove() == false) {
        $modx->log(modX::LOG_LEVEL_ERROR,$modx->lexicon('namespace_err_remove'));
        continue;
    }

    /* log manager action */
    $modx->logManagerAction('namespace_remove','modNamespace',$namespace->get('name'));
}

return $modx->error->success();