<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modContextSetting;
use MODX\Revolution\modResource;
use MODX\Revolution\modUser;

require_once __DIR__ . '/resource.class.php';

/**
 * Loads the update resource page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ResourceUpdateManagerController extends ResourceManagerController
{
    /** @var bool Whether this Resource is locked for editing */
    public $locked = false;
    /** @var string If the Resource is locked, the text on the locked button to show */
    public $lockedText = '';
    /** @var string The URL of the resource on the front-end */
    public $previewUrl = '';

    /** @var modResource $resource */
    public $resource;


    /**
     * Register custom CSS/JS for the page
     *
     * @return void
     */
    public function loadCustomCssJs()
    {
        $mgrUrl = $this->context->getOption('manager_url', MODX_MANAGER_URL, $this->modx->_userConfig);
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.grid.resource.security.local.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->addJavascript($mgrUrl . 'assets/modext/sections/resource/update.js');
        $data = [
            'xtype' => 'modx-page-resource-update',
            'resource' => $this->resource->get('id'),
            'record' => $this->resourceArray,
            'publish_document' => $this->canPublish,
            'preview_url' => $this->previewUrl,
            'locked' => (int)$this->locked,
            'lockedText' => $this->lockedText,
            'canSave' => (int)$this->canSave,
            'canEdit' => (int)$this->canEdit,
            'canCreate' => (int)$this->canCreate,
            'canCreateRoot' => (int)$this->canCreateRoot,
            'canDuplicate' => (int)$this->canDuplicate,
            'canDelete' => (int)$this->canDelete,
            'show_tvs' => (int)!empty($this->tvCounts),
            'mode' => 'update',
        ];
        $this->addHtml('<script>
        MODx.config.publish_document = "' . $this->canPublish . '";
        MODx.onDocFormRender = "' . $this->onDocFormRender . '";
        MODx.ctx = "' . $this->resource->get('context_key') . '";
        Ext.onReady(function() {MODx.load(' . json_encode($data, JSON_INVALID_UTF8_SUBSTITUTE) . ')});</script>');

        $this->loadRichTextEditor();
    }


    /**
     * @return bool|string
     */
    public function getResource()
    {
        $id = (int)$this->scriptProperties['id'];
        if (!$this->resource = $this->modx->getObject($this->resourceClass, $id)) {
            return $this->modx->lexicon('resource_err_nfs', ['id' => $this->scriptProperties['id']]);
        }

        if (!$this->resource->checkPolicy('save')) {
            $this->canSave = false;
        }

        return true;
    }


    /**
     * @param array $scriptProperties
     *
     * @return array|mixed
     */
    public function process(array $scriptProperties = [])
    {
        $placeholders = [];
        $reloadData = $this->getReloadData();

        $loaded = $this->getResource();
        if ($loaded !== true) {
            $this->failure($loaded);

            return [];
        }
        if (is_array($reloadData) && !empty($reloadData)) {
            $this->resource->fromArray($reloadData);
        }

        // get context
        $this->setContext();
        if (!$this->context) {
            $this->failure($this->modx->lexicon('access_denied'));

            return [];
        }

        // check for locked status
        $this->checkForLocks();

        // set template overrides
        if (isset($scriptProperties['template'])) $this->resource->set('template', $scriptProperties['template']);

        $this->setParent();

        // invoke OnDocFormRender event
        $this->fireOnRenderEvent();

        // check permissions
        $this->setPermissions();

        // register FC rules
        $this->resourceArray = $this->resource->toArray();
        $overridden = $this->checkFormCustomizationRules($this->resource);
        $this->resourceArray = array_merge($this->resourceArray, $overridden);
        $this->resourceArray['parents'] = $this->getParents();

        $fields = ['published', 'hidemenu', 'isfolder', 'richtext', 'searchable', 'cacheable', 'deleted', 'uri_override', 'alias_visible'];
        foreach ($fields as $field) {
            $this->resourceArray[$field] = !empty($this->resourceArray[$field]);
        }
        if (isset($this->resourceArray['syncsite'])) {
            $this->resourceArray['syncsite'] = !empty($this->resourceArray['syncsite']);
        } else {
            $syncsiteDefault = $this->context->getOption('syncsite_default', 1,
                $this->modx->_userConfig);
            $this->resourceArray['syncsite'] = !empty($syncsiteDefault);
        }
        if (!empty($this->resourceArray['parent'])) {
            if ($this->parent->get('id') == $this->resourceArray['parent']) {
                $this->resourceArray['parent_pagetitle'] = $this->modx->stripTags($this->parent->get('pagetitle'));
            } else {
                $overriddenParent = $this->modx->getObject(modResource::class, $this->resourceArray['parent']);
                if ($overriddenParent) {
                    $this->resourceArray['parent_pagetitle'] = $this->modx->stripTags($overriddenParent->get('pagetitle'));
                }
            }
        }

        // get TVs
        $this->resource->set('template', $this->resourceArray['template']);

        if (!empty($reloadData)) {
            $this->resourceArray['resourceGroups'] = [];
            $this->resourceArray['resource_groups'] = $this->modx->getOption('resource_groups', $this->resourceArray, []);
            $this->resourceArray['resource_groups'] = is_array($this->resourceArray['resource_groups'])
                ? $this->resourceArray['resource_groups']
                : json_decode($this->resourceArray['resource_groups'], true);
            foreach ($this->resourceArray['resource_groups'] as $resourceGroup) {
                $this->resourceArray['resourceGroups'][] = [
                    $resourceGroup['id'],
                    $resourceGroup['name'],
                    $resourceGroup['access'],
                ];
            }
            unset($this->resourceArray['resource_groups']);
        } else {
            $this->getResourceGroups();
        }

        $this->prepareResource();
        $this->loadTVs($reloadData);

        $this->getPreviewUrl();
        // Single-use token for reloading resource
        $this->setResourceToken();
        $this->setPlaceholder('resource', $this->resource);

        return $placeholders;
    }


    /**
     * Get url for resource for preview window
     *
     * @return string
     */
    public function getPreviewUrl()
    {
        if (!$this->resource->get('deleted')) {
            $this->modx->setOption('cache_alias_map', false);
            $sessionEnabled = '';
            $ctxSetting = $this->modx->getObject(modContextSetting::class, [
                'context_key' => $this->resource->get('context_key'),
                'key' => 'session_enabled',
            ]);
            if ($ctxSetting) {
                $sessionEnabled = $ctxSetting->get('value') == 0 ? ['preview' => 'true'] : '';
            }

            $this->previewUrl = $this->modx->makeUrl($this->resource->get('id'), $this->resource->get('context_key'), $sessionEnabled, 'full', ['xhtml_urls' => false]);
        }

        return $this->previewUrl;
    }


    /**
     * Check for locks on the Resource
     *
     * @return bool
     */
    public function checkForLocks()
    {
        $lockedBy = $this->resource->addLock($this->modx->user->get('id'));
        $this->canSave = $this->modx->hasPermission('save_document') ? 1 : 0;
        $this->locked = false;
        $this->lockedText = '';
        if (!empty($lockedBy) && $lockedBy !== true) {
            $this->canSave = false;
            $this->locked = true;
            $locker = $this->modx->getObject(modUser::class, $lockedBy);
            if ($locker) {
                $lockedBy = $locker->get('username');
            }
            $this->lockedText = $this->modx->lexicon('resource_locked_by', [
                'user' => $lockedBy,
                'id' => $this->resource->get('id'),
            ]);
        }

        return $this->locked;
    }


    /**
     * Check for any permissions or requirements to load page
     *
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('edit_document');
    }


    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('editing', ['name' => $this->resourceArray['pagetitle']]);
    }


    /**
     * Return the location of the template file
     *
     * @return string
     */
    public function getTemplateFile()
    {
        return 'resource/update.tpl';
    }
}

class_alias(ResourceUpdateManagerController::class, 'DocumentUpdateManagerController');
