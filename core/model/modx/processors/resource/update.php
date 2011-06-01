<?php
/**
 * Updates a resource.
 *
 * @param string $pagetitle The page title.
 * @param string $content The HTML content. Used in conjunction with $ta.
 * @param integer $template (optional) The modTemplate to use with this
 * resource. Defaults to 0, or a blank template.
 * @param integer $parent (optional) The parent resource ID. Defaults to 0.
 * @param string $class_key (optional) The class key. Defaults to modDocument.
 * @param integer $menuindex (optional) The menu order. Defaults to 0.
 * @param string $variablesmodified (optional) A collection of modified TVs.
 * Along with $tv1, $tv2, etc.
 * @param string $context_key (optional) The context in which this resource is
 * located. Defaults to web.
 * @param string $alias (optional) The alias for FURLs that this resource is
 * designated to.
 * @param integer $content_type (optional) The content type. Defaults to
 * text/html.
 * @param boolean $published (optional) The published status.
 * @param string $pub_date (optional) The date on which this resource should
 * become published.
 * @param string $unpub_date (optional) The date on which this resource should
 * become unpublished.
 * @param string $publishedon (optional) The date this resource was published.
 * Defaults to time()
 * @param integer $publishedby (optional) The modUser who published this
 * resource. Defaults to the current user.
 * @param json $resource_groups (optional) A JSON array of resource groups to
 * assign this resource to.
 * @param boolean $hidemenu (optional) If true, The resource will not show up in
 * menu builders.
 * @param boolean $isfolder (optional) Whether or not the resource is a
 * container of resources.
 * @param boolean $richtext (optional) If true, MODX will render the available
 * RTE for editing this resource.
 * @param boolean $donthit (optional) (deprecated) If true, MODX will not log
 * visits on this resource.
 * @param boolean $cacheable (optional) If false, the resource will not be
 * cached.
 * @param boolean $searchable (optional) If false, the resource will not appear
 * in searches.
 * @param boolean $syncsite (optional) If false, will not empty the cache on
 * save.
 * @return array
 *
 * @package modx
 * @subpackage processors.resource
 */
$modx->lexicon->load('resource');

/* get resource */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject('modResource',$scriptProperties['id']);
if (empty($resource)) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $scriptProperties['id'])));

/* check permissions */
if (!$modx->hasPermission('save_document') || !$resource->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* add locks */
$locked = $resource->addLock();
if ($locked !== true) {
    if (isset($scriptProperties['steal_lock']) && !empty($scriptProperties['steal_lock'])) {
        if (!$modx->hasPermission('steal_locks') || !$resource->checkPolicy('steal_lock')) {
            return $modx->error->failure($modx->lexicon('access_denied'));
        }
        if ($locked > 0 && $locked != $modx->user->get('id')) {
            $resource->removeLock($locked);
            $locked = $resource->addLock($modx->user->get('id'));
        }
    }
    if ($locked !== true) {
        $lockedBy = intval($locked);
        $user = $modx->getObject('modUser', $lockedBy);
        if ($lockedBy) {
            return $modx->error->failure($modx->lexicon('resource_locked_by', array('id' => $resource->get('id'), 'user' => $user->get('username'))));
        } else {
            $resource->removeLock($locked);
        }
    }
}

/* if using RTE */
if (isset($scriptProperties['ta'])) {
    $scriptProperties['content'] = $scriptProperties['ta'];
}

/* format pagetitle */
if (isset($scriptProperties['pagetitle']) && empty($scriptProperties['reloadOnly'])) {
    if (empty($scriptProperties['pagetitle'])) {
        $scriptProperties['pagetitle'] = $modx->lexicon('resource_untitled');
    }
    $scriptProperties['pagetitle'] = trim($scriptProperties['pagetitle']);
}

/* handle parent */
if (isset($scriptProperties['parent'])) {
    /* handle if parent is a context */
    if (!is_numeric($scriptProperties['parent'])) {
        $ct = $modx->getCount('modContext',$scriptProperties['parent']);
        if ($ct > 0) {
            $scriptProperties['context_key'] = $scriptProperties['parent'];
        }
        $scriptProperties['parent'] = 0;
    }

    /* ensure parent isn't a child of self */
    if ($resource->get('parent') != $scriptProperties['parent']) {
        $children = $modx->getChildIds($resource->get('id'),20,array(
            'context' => $resource->get('context_key'),
        ));
        if (in_array($scriptProperties['parent'],$children)) {
            $modx->error->addField('parent-cmb',$modx->lexicon('resource_err_move_to_child'));
        }
    }

    /* convert parent to int */
    $scriptProperties['parent'] = empty($scriptProperties['parent']) ? 0 : intval($scriptProperties['parent']);
}

/* if parent changed, set context to new parent's context */
$oldparent = null;
$newparent = null;
$oldContext = null;
if ($resource->get('parent') != $scriptProperties['parent']) {
    $oldparent = $modx->getObject('modResource',array('id' => $resource->get('parent')));
    $newparent = $modx->getObject('modResource', $scriptProperties['parent']);
    if ($newparent && $newparent->get('context_key') !== $resource->get('context_key')) {
        $oldContext = $modx->getContext($resource->get('context_key'));
        if ($resource->get('id') == $oldContext->getOption('site_start')) {
            return $modx->error->failure($modx->lexicon('resource_err_move_sitestart'));
        }
        $scriptProperties['context_key'] = $newparent->get('context_key');
    }
}

/* handle checkboxes */
if (isset($scriptProperties['hidemenu'])) {
    $scriptProperties['hidemenu'] = empty($scriptProperties['hidemenu']) || $scriptProperties['hidemenu'] === 'false' ? 0 : 1;
}
if (isset($scriptProperties['isfolder'])) {
    $scriptProperties['isfolder'] = empty($scriptProperties['isfolder']) || $scriptProperties['isfolder'] === 'false' ? 0 : 1;
}
if (isset($scriptProperties['richtext'])) {
    $scriptProperties['richtext'] = empty($scriptProperties['richtext']) || $scriptProperties['richtext'] === 'false' ? 0 : 1;
}
if (isset($scriptProperties['published'])) {
    $scriptProperties['published'] = empty($scriptProperties['published']) || $scriptProperties['published'] === 'false' ? 0 : 1;
}
if (isset($scriptProperties['cacheable'])) {
    $scriptProperties['cacheable'] = empty($scriptProperties['cacheable']) || $scriptProperties['cacheable'] === 'false' ? 0 : 1;
}
if (isset($scriptProperties['searchable'])) {
    $scriptProperties['searchable'] = empty($scriptProperties['searchable']) || $scriptProperties['searchable'] === 'false' ? 0 : 1;
}
if (isset($scriptProperties['syncsite'])) {
    $scriptProperties['syncsite'] = empty($scriptProperties['syncsite']) || $scriptProperties['syncsite'] === 'false' ? 0 : 1;
}
if (isset($scriptProperties['deleted'])) {
    $scriptProperties['deleted'] = empty($scriptProperties['deleted']) || $scriptProperties['deleted'] === 'false' ? 0 : 1;
}
if (isset($scriptProperties['uri_override'])) {
    $scriptProperties['uri_override'] = empty($scriptProperties['uri_override']) || $scriptProperties['uri_override'] === 'false' ? 0 : 1;
}

/* get the targeted working context */
$workingContext = $modx->getContext($scriptProperties['context_key']);

/* friendly url alias checks */
if ($workingContext->getOption('friendly_urls', false) && (empty($scriptProperties['reloadOnly']) || !empty($scriptProperties['pagetitle']))) {
    /* auto assign alias */
    if (empty($scriptProperties['alias']) && $workingContext->getOption('automatic_alias', false)) {
        $scriptProperties['alias'] = $resource->cleanAlias($scriptProperties['pagetitle']);
    }
    if (empty($scriptProperties['alias'])) {
        $modx->error->addField('alias', $modx->lexicon('field_required'));
    }
    $duplicateContext = $workingContext->getOption('global_duplicate_uri_check', false) ? '' : $scriptProperties['context_key'];
    $aliasPath = $resource->getAliasPath($scriptProperties['alias'], $scriptProperties);
    $duplicateId = $resource->isDuplicateAlias($aliasPath, $duplicateContext);
    if (!empty($duplicateId)) {
        $err = $modx->lexicon('duplicate_uri_found', array(
                                                          'id' => $duplicateId,
                                                          'uri' => $aliasPath,
                                                     ));
        $modx->error->addField('uri', $err);
        if (!isset($scriptProperties['uri_override']) || $scriptProperties['uri_override'] !== 1) {
            $modx->error->addField('alias', $err);
        }
    }
}

/* publish and unpublish dates */
$now = time();
if (isset($scriptProperties['pub_date'])) {
    if (empty($scriptProperties['pub_date'])) {
        $scriptProperties['pub_date'] = 0;
    } else {
        $strPubDate = $scriptProperties['pub_date'];
        $scriptProperties['pub_date'] = strtotime($scriptProperties['pub_date']);
        if ($scriptProperties['pub_date'] < $now) {
            $scriptProperties['published'] = 1;
            $scriptProperties['publishedon'] = $strPubDate;
            $scriptProperties['pub_date'] = 0;
        }
        if ($scriptProperties['pub_date'] > $now) {
            $scriptProperties['published'] = 0;
            $scriptProperties['publishedon'] = 0;
        }
    }
}
if (isset($scriptProperties['unpub_date'])) {
    if (empty($scriptProperties['unpub_date'])) {
        $scriptProperties['unpub_date'] = 0;
    } else {
        $scriptProperties['unpub_date'] = strtotime($scriptProperties['unpub_date']);
        if ($scriptProperties['unpub_date'] < $now) {
            $scriptProperties['published'] = 0;
            $scriptProperties['unpub_date'] = 0;
            $scriptProperties['pub_date'] = 0;
            $scriptProperties['publishedon'] = 0;
        }
    }
}

/* set publishedon date if publish change is different */
if (isset($scriptProperties['published']) && $scriptProperties['published'] != $resource->get('published')) {
    if (empty($scriptProperties['published'])) { /* if unpublishing */
        $scriptProperties['publishedon'] = 0;
        $scriptProperties['publishedby'] = 0;
    } else { /* if publishing */
        $scriptProperties['publishedon'] = !empty($scriptProperties['publishedon']) ? strtotime($scriptProperties['publishedon']) : time();
        $scriptProperties['publishedby'] = $modx->user->get('id');
    }
} else { /* if no change, unset publishedon/publishedby */
    if (empty($scriptProperties['published'])) { /* allow changing of publishedon date if resource is published */
        unset($scriptProperties['publishedon']);
    }
    unset($scriptProperties['publishedby']);
}

/* Deny publishing if not permitted */
if (!$modx->hasPermission('publish_document')) {
    $scriptProperties['published'] = $resource->get('published');
    $scriptProperties['publishedon'] = $resource->get('publishedon');
    $scriptProperties['publishedby'] = $resource->get('publishedby');
    $scriptProperties['pub_date'] = $resource->get('pub_date');
    $scriptProperties['unpub_date'] = $resource->get('unpub_date');
}

/* check to prevent unpublishing of site_start */
$oldparent_id = $resource->get('parent');
$siteStart = ($resource->get('id') == $workingContext->getOption('site_start') || $resource->get('id') == $modx->getOption('site_start'));
if ($siteStart && (isset($scriptProperties['published']) && empty($scriptProperties['published']))) {
    return $modx->error->failure($modx->lexicon('resource_err_unpublish_sitestart'));
}
if ($siteStart && (!empty($scriptProperties['pub_date']) || !empty($scriptProperties['unpub_date']))) {
    return $modx->error->failure($modx->lexicon('resource_err_unpublish_sitestart_dates'));
}

/* set deleted status and fire events */
if (isset($scriptProperties['deleted']) && $scriptProperties['deleted'] != $resource->get('deleted')) {
    if ($resource->get('deleted')) { /* undelete */
        if (!$modx->hasPermission('undelete_document')) {
            $scriptProperties['deleted'] = $resource->get('deleted');
        } else {
            $resource->set('deleted',false);
            $resourceUndeleted = true;
        }
    } else { /* delete */
        if (!$modx->hasPermission('delete_document')) {
            $scriptProperties['deleted'] = $resource->get('deleted');
        } else {
            $resource->set('deleted',true);
            $resourceDeleted = true;
        }
    }
}

/* process derivative resource classes */
$resourceClass = !empty($scriptProperties['class_key']) ? $scriptProperties['class_key'] : $resource->get('class_key');
$resourceDir= strtolower(substr($resourceClass, 3));

$delegateProcessor= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateProcessor)) {
    $overridden= include ($delegateProcessor);
    return $overridden;
}

/* if errors, feed back to form */
if ($modx->error->hasError()) return $modx->error->failure($modx->lexicon('correct_errors'));

/* Now set and save data */
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

if ($oldparent !== null && $newparent !== null) {
    $opc = $modx->getCount('modResource', array('parent' => $oldparent->get('id')));
    if ($opc <= 0 || $opc == null) {
        $oldparent->set('isfolder', false);
        $oldparent->save();
    }

    $newparent->set('isfolder', true);
}

/* save resource */
if ($resource->save() == false) {
    return $modx->error->failure($modx->lexicon('resource_err_save'));
}

/* save TVs */
if (!empty($scriptProperties['tvs'])) {
    $tmplvars = array ();

    $tvs = $resource->getTemplateVars();
    foreach ($tvs as $tv) {
        /* set value of TV */
        if ($tv->get('type') != 'checkbox') {
            $value = isset($scriptProperties['tv'.$tv->get('id')]) ? $scriptProperties['tv'.$tv->get('id')] : $tv->get('default_text');
        } else {
            $value = isset($scriptProperties['tv'.$tv->get('id')]) ? $scriptProperties['tv'.$tv->get('id')] : '';
        }

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

/* Save resource groups */
if (isset($scriptProperties['resource_groups'])) {
    $resourceGroups = $modx->fromJSON($scriptProperties['resource_groups']);
    if (is_array($resourceGroups)) {
        foreach ($resourceGroups as $id => $resourceGroupAccess) {
            /* prevent adding records for non-existing groups */
            $resourceGroup = $modx->getObject('modResourceGroup',$resourceGroupAccess['id']);
            if (empty($resourceGroup)) continue;
            
            /* if assigning to group */
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
                
            /* if removing access to group */
            } else {
                $resourceGroupResource = $modx->getObject('modResourceGroupResource',array(
                    'document_group' => $resourceGroupAccess['id'],
                    'document' => $resource->get('id'),
                ));
                if ($resourceGroupResource && $resourceGroupResource instanceof modResourceGroupResource) {
                    $resourceGroupResource->remove();
                }
            }
        } /* end foreach */
    } /* end if is_array */
}
/* end save resource groups */

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