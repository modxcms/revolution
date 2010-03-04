<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages.rest
 */
if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace');

/* get provider */
$provider = $modx->getOption('provider',$scriptProperties,false);
if (empty($provider)) return $modx->error->failure($modx->lexicon('provider_err_ns'));
$provider = $modx->getObject('transport.modTransportProvider',$provider);
if (empty($provider)) return $modx->error->failure($modx->lexicon('provider_err_nf'));

/* get version */
$modx->getVersionData();
$productVersion = $modx->version['code_name'].'-'.$modx->version['full_version'];

/* get client */
$loaded = $provider->getClient();
if (!$loaded) return $modx->error->failure($modx->lexicon('provider_err_no_client'));

/* load appropriate processor */
$id = $modx->getOption('id',$scriptProperties,'n_root_0');
$ar = explode('_',$id);
$type = $ar[1];
$pk = $ar[2];
$list = array();
switch ($type) {
    case 'repository':
        $list = include dirname(__FILE__).'/getnodes.repository.php';
        break;
    case 'tag':
        $list = array();
        break;
    case 'root':
    default:
        $list = include dirname(__FILE__).'/getnodes.root.php';
        break;
}

return $this->toJSON($list);
