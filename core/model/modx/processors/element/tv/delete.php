<?php
/**
 * Delete a TV
 *
 * @param integer $id The TV to delete
 *
 * @package modx
 * @subpackage processors.element.tv
 */
$modx->lexicon->load('tv');

if (!$modx->hasPermission('delete_template')) return $modx->error->failure($modx->lexicon('permission_denied'));

$forced = true;

/* get tv */
$tv = $modx->getObject('modTemplateVar',$_POST['id']);
if ($tv == null) return $modx->error->failure($modx->lexicon('tv_err_not_found'));

/* get tv relational tables */
$tv->templates = $tv->getMany('TemplateVarTemplates');
$tv->resources = $tv->getMany('TemplateVarResources');
$tv->resource_groups = $tv->getMany('TemplateVarResourceGroups');

/* check for relations */
if (!$forced) {
	$c = $modx->newQuery('modTemplateVarResource');
	$c->where(array('tmplvarid' => $tv->get('id')));
    $tvds = $modx->getCollection('modTemplateVarResource',$c);

	if (count($tv->resources) > 0) {
        $o = '<p>'.$modx->lexicon('tmplvar_inuse').'</p><ul>';
		foreach ($tv->resources as $tvd) {
            $tvi = $tvd->getOne('TemplateVar');
            if ($tvi == null) {
                $tvd->remove();
                continue;
            }
			$o .= '<li><span style="width: 200px"><a href="index.php?id='.$tvi->get('id').'&a=27">';
            $o .= $tvi->get('pagetitle').'</a></span>';
            $o .= $tvi->get('description') != '' ? ' - '.$tvi->get('description') : '';
            $o .= '</li>';
		}
        $o .= '</ul>';
		return $modx->error->failure($o);
	}
}

/* invoke OnBeforeTVFormDelete event */
$modx->invokeEvent('OnBeforeTVFormDelete',array('id' => $tv->get('id')));

/* delete variable's content values */
foreach ($tv->resources as $tvd) {
	if ($tvd->remove() == false) {
        return $modx->error->failure($modx->lexicon('tvd_err_remove'));
    }
}

/* delete variable's template access */
foreach ($tv->resource_groups as $tvdg) {
	if ($tvdg->remove() == false) {
        return $modx->error->failure($modx->lexicon('tvdg_err_remove'));
    }
}

/* delete variable's access permissions */
foreach ($tv->templates as $tvt) {
	if ($tvt->remove() == false) {
        return $modx->error->failure($modx->lexicon('tvt_err_remove'));
    }
}

/* delete tv */
if ($tv->remove() == false) {
	return $modx->error->failure($modx->lexicon('tv_err_delete'));
}

/* invoke OnTVFormDelete event */
$modx->invokeEvent('OnTVFormDelete',array('id' => $tv->get('id')));

/* log manager action */
$modx->logManagerAction('tv_delete','modTemplateVar',$tv->get('id'));

return $modx->error->success();