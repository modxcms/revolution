<?php
/**
 * @package modx
 * @subpackage processors.resource
 */
if ($modx->error->hasError()) return $modx->error->failure($modx->lexicon('correct_errors'));

/* Now save data */
unset($scriptProperties['variablesmodified']);
$resource->fromArray($scriptProperties);
$resource->set('editedby', $modx->user->get('id'));
$resource->set('editedon', time(), 'integer');

/* invoke OnBeforeDocFormSave event, and allow non-empty responses to prevent save */
$OnBeforeDocFormSave = $modx->invokeEvent('OnBeforeDocFormSave',array(
    'mode' => modSystemEvent::MODE_UPD,
    'id' => $resource->get('id'),
    'resource' => &$resource,
));
if (is_array($OnBeforeDocFormSave)) {
    $canSave = false;
    foreach ($OnBeforeDocFormSave as $msg) {
        if (!empty($msg)) {
            $canSave .= $msg."\n";
        }
    }
} else {
    $canSave = $OnBeforeDocFormSave;
}
if (!empty($canSave)) {
    return $modx->error->failure($canSave);
}

/* save resource */
if ($resource->save() == false) {
    return $modx->error->failure($modx->lexicon('document_err_save'));
}

/* Save resource groups */
if (isset($scriptProperties['resource_groups'])) {
    $resourceGroups = $modx->fromJSON($scriptProperties['resource_groups']);
    if (is_array($resourceGroups)) {
        foreach ($resourceGroups as $id => $resourceGroupAccess) {
            /* prevent adding records for non-existing groups */
            $resourceGroup = $modx->getObject('modResourceGroup',$resourceGroupAccess['id']);
            if (empty($resourceGroup)) continue;

            if ($resourceGroupAccess['access']) {
                $resourceGroupResource = $modx->getObject('modResourceGroupResource',array(
                    'document_group' => $resourceGroupAccess['id'],
                    'document' => $resource->get('id'),
                ));
                if (empty($resourceGroupResource)) {
                    $resourceGroupResource = $modx->newObject('modResourceGroupResource');
                }
                $resourceGroupResource->set('document_group',$resourceGroupAccess['id']);
                $resourceGroupResource->set('document',$resource->get('id'));
                $resourceGroupResource->save();
            } else {
                $resourceGroupResource = $modx->getObject('modResourceGroupResource',array(
                    'document_group' => $resourceGroupAccess['id'],
                    'document' => $resource->get('id'),
                ));
                if ($resourceGroupResource && $resourceGroupResource instanceof modResourceGroupResource) {
                    $resourceGroupResource->remove();
                }
            }
        }
    }
}
/* save TVs */
if (!empty($scriptProperties['tvs'])) {
    $tmplvars = array ();
    $c = $modx->newQuery('modTemplateVar');
    $c->setClassAlias('tv');
    $c->innerJoin('modTemplateVarTemplate', 'tvtpl', array(
        'tvtpl.tmplvarid = tv.id',
        'tvtpl.templateid' => $resource->get('template'),
    ));
    $c->leftJoin('modTemplateVarResource', 'tvc', array(
        'tvc.tmplvarid = tv.id',
        'tvc.contentid' => $resource->get('id'),
    ));
    switch ($modx->getOption('dbtype')) {
        case 'sqlite':
        case 'mysql':
            $if = 'IF';
            break;
        case 'sqlsrv':
            $if = 'IIF';
            break;
    }
    $c->select(array(
        'DISTINCT tv.*',
        "{$if}(tvc.value != '',tvc.value,tv.default_text) AS value"
    ));
    $c->sortby('tv.rank');

    $tvs = $modx->getCollection('modTemplateVar',$c);
    foreach ($tvs as $tv) {
        /* set value of TV */
        $value = isset($scriptProperties['tv'.$tv->get('id')]) ? $scriptProperties['tv'.$tv->get('id')] : $tv->get('default_text');

        /* validation for different types */
        switch ($tv->get('type')) {
            case 'url':
                if ($scriptProperties['tv'.$tv->get('id').'_prefix'] != '--') {
                    $value = str_replace(array('ftp://','http://'),'', $value);
                    $value = $scriptProperties['tv'.$tv->get('id').'_prefix'].$value;
                }
                break;
            case 'date':
                $value = empty($value) ? '' : strftime('%Y-%m-%d %H:%M:%S',strtotime($value));
                break;
            /* ensure tag types trim whitespace from tags */
            case 'tag':
            case 'autotag':
                $tags = explode(',',$value);
                $newTags = array();
                foreach ($tags as $tag) {
                    $newTags[] = trim($tag);
                }
                $value = implode(',',$newTags);
                break;
            default:
                /* handles checkboxes & multiple selects elements */
                if (is_array($value)) {
                    $featureInsert = array();
                    while (list($featureValue, $featureItem) = each($value)) {
                        $featureInsert[count($featureInsert)] = $featureItem;
                    }
                    $value = implode('||',$featureInsert);
                }
                break;
        }

        /* if different than default and set, set TVR record */
        $default = $tv->processBindings($tv->get('default_text'),$resource->get('id'));
        if (strcmp($value,$default) != 0) {

            /* update the existing record */
            $tvc = $modx->getObject('modTemplateVarResource',array(
                'tmplvarid' => $tv->get('id'),
                'contentid' => $resource->get('id'),
            ));
            if ($tvc == null) {
                /* add a new record */
                $tvc = $modx->newObject('modTemplateVarResource');
                $tvc->set('tmplvarid',$tv->get('id'));
                $tvc->set('contentid',$resource->get('id'));
            }
            $tvc->set('value',$value);
            $tvc->save();

        /* if equal to default value, erase TVR record */
        } else {
            $tvc = $modx->getObject('modTemplateVarResource',array(
                'tmplvarid' => $tv->get('id'),
                'contentid' => $resource->get('id'),
            ));
            if ($tvc != null) $tvc->remove();
        }
    }
}
/* end save TVs */

/* fire delete/undelete events */
if (isset($resourceUndeleted) && !empty($resourceUndeleted)) {
    $modx->invokeEvent('OnResourceUndelete',array(
        'id' => $resource->get('id'),
        'resource' => &$resource,
    ));
}
if (isset($resourceDeleted) && !empty($resourceDeleted)) {
    $modx->invokeEvent('OnResourceDelete',array(
        'id' => $resource->get('id'),
        'resource' => &$resource,
    ));
}


/* invoke OnDocFormSave event */
$modx->invokeEvent('OnDocFormSave',array(
    'mode' => modSystemEvent::MODE_UPD,
    'id' => $resource->get('id'),
    'resource' => & $resource
));

/* log manager action */
$modx->logManagerAction('save_resource','modResource',$resource->get('id'));

$resource->removeLock();

if (!empty($scriptProperties['syncsite']) || !empty($scriptProperties['clearCache'])) {
    /* empty cache */
    $modx->cacheManager->refresh(array(
        'db' => array(),
        'auto_publish' => array('contexts' => array($resource->get('context_key'))),
        'context_settings' => array('contexts' => array($resource->get('context_key'))),
        'resource' => array('contexts' => array($resource->get('context_key'))),
    ));
}

$returnArray = $resource->get(array_diff(array_keys($resource->_fields), array('content','ta','introtext','description','link_attributes','pagetitle','longtitle','menutitle')));
foreach ($returnArray as $k => $v) {
    if (strpos($k,'tv') === 0) {
        unset($returnArray[$k]);
    }
}
$returnArray['class_key'] = $resource->get('class_key');
$workingContext->prepare(true);
$returnArray['preview_url'] = $modx->makeUrl($resource->get('id'), '', '', 'full');
return $modx->error->success('',$returnArray);