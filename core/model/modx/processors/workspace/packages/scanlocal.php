<?php
/**
 * Scans for local packages to add to the workspace.
 *
 * @param integer $workspace The workspace to add to. Defaults to 1.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace');

/* get workspace */
if (!isset($scriptProperties['workspace'])) $scriptProperties['workspace'] = 1;
$workspace = $modx->getObject('modWorkspace',$scriptProperties['workspace']);
if (empty($workspace)) return $modx->error->failure($modx->lexicon('workspace_err_nf'));

$packages = array();

/* scan packages/ directory */
$fullpath = $modx->getOption('core_path').'packages/';
$odir = dir($fullpath);
while(false !== ($name = $odir->read())) {
	if(in_array($name,array('.','..','.svn','.git','_notes'))) continue;

	$fullname = $fullpath.'/'.$name;
	/* dont add in unreadable files or directories */
	if(!is_readable($fullname) || is_dir($fullname)) continue;

	/* must be a .transport.zip file */
	if (strlen($name) < 14 || substr($name,strlen($name)-14,strlen($name)) != '.transport.zip') continue;
	$pname = substr($name,0,strlen($name)-14);

	/* must have a name and version at least */
	$p = explode('-',$pname);
	if (count($p) < 2) continue;

	$packages[] = $pname;
}

/* foreach package that was found, add an object */
foreach ($packages as $signature) {
	$package = $modx->getObject('transport.modTransportPackage',array(
		'signature' => $signature,
	));
	if (!empty($package)) continue;

	$package = $modx->newObject('transport.modTransportPackage');
	$package->set('signature', $signature);
	$package->set('state', 1);
    $package->set('created',strftime('%Y-%m-%d %H:%M:%S'));
	$package->set('workspace', $workspace->get('id'));

    /* set package version data */
    $sig = explode('-',$signature);
    if (is_array($sig)) {
        $package->set('package_name',$sig[0]);
        if (!empty($sig[1])) {
            $v = explode('.',$sig[1]);
            if (isset($v[0])) $package->set('version_major',$v[0]);
            if (isset($v[1])) $package->set('version_minor',$v[1]);
            if (isset($v[2])) $package->set('version_patch',$v[2]);
        }
        if (!empty($sig[2])) {
            $r = preg_split('/([0-9]+)/',$sig[2],-1,PREG_SPLIT_DELIM_CAPTURE);
            if (is_array($r) && !empty($r)) {
                $package->set('release',$r[0]);
                $package->set('release_index',(isset($r[1]) ? $r[1] : '0'));
            } else {
                $package->set('release',$sig[2]);
            }
        }
    }

	if ($package->save() === false) {
        return $modx->error->failure($modx->lexicon('package_err_create'));
    }
}

return $modx->error->success();
