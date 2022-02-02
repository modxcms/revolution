<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modActionDom;
use MODX\Revolution\modFormCustomizationProfile;
use MODX\Revolution\modFormCustomizationProfileUserGroup;
use MODX\Revolution\modFormCustomizationSet;
use MODX\Revolution\modResource;
use xPDO\Om\xPDOQuery;

require_once __DIR__ . '/resource.class.php';

/**
 * Loads the create resource page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class ResourceCreateManagerController extends ResourceManagerController
{

    /**
     * Check for any permissions or requirements to load page
     *
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('new_document');
    }


    /**
     * Register custom CSS/JS for the page
     *
     * @return void
     */
    public function loadCustomCssJs()
    {
        $mgrUrl = $this->modx->getOption('manager_url', null, MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.grid.resource.security.local.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->addJavascript($mgrUrl . 'assets/modext/sections/resource/create.js');
        $data = [
            'xtype' => 'modx-page-resource-create',
            'record' => $this->resourceArray,
            'publish_document' => $this->canPublish,
            'canSave' => (int)$this->modx->hasPermission('save_document'),
            'show_tvs' => (int)!empty($this->tvCounts),
            'mode' => 'create',
        ];
        $this->addHtml('<script>
        MODx.config.publish_document = "' . $this->canPublish . '";
        MODx.onDocFormRender = "' . $this->onDocFormRender . '";
        MODx.ctx = "' . $this->ctx . '";
        Ext.onReady(function() {MODx.load(' . json_encode($data, JSON_INVALID_UTF8_SUBSTITUTE) . ')});</script>');

        $this->loadRichTextEditor();
    }


    /**
     * Custom logic code here for setting placeholders, etc
     *
     * @param array $scriptProperties
     *
     * @return mixed
     */
    public function process(array $scriptProperties = [])
    {
        $placeholders = [];
        $reloadData = $this->getReloadData();

        // handle template inheritance
        if (!empty($this->scriptProperties['parent'])) {
            $this->parent = $this->modx->getObject(modResource::class, $this->scriptProperties['parent']);
            if (!$this->parent->checkPolicy('add_children')) {
                $this->failure($this->modx->lexicon('resource_add_children_access_denied'));

                return '';
            }
        } else {
            $this->parent = $this->modx->newObject(modResource::class);
            $this->parent->set('id', 0);
            $this->parent->set('template', $this->modx->getOption('default_template', null, 1));
        }
        $placeholders['parent'] = $this->parent;

        $this->setContext();
        if (!$this->context) {
            $this->failure($this->modx->lexicon('context_err_nf'));

            return '';
        }

        // handle custom resource types
        $this->resource = $this->modx->newObject($this->resourceClass);
        $this->resource->set('id', 0);
        $this->resource->set('context_key', $this->context->get('key'));
        $placeholders['resource'] = $this->resource;
        $this->resourceArray = [];

        $placeholders['parentname'] = $this->setParent();
        $this->fireOnRenderEvent();

        // set template
        if (!is_null($this->resource->get('template')) && $this->resource->get('template') !== 0) {
            $this->scriptProperties['template'] = $this->resource->get('template');
        }

        // check permissions
        $this->setPermissions();

        // set default template
        if (empty($reloadData)) {
            $defaultTemplate = $this->getDefaultTemplate();
            $this->resourceArray = array_merge($this->resourceArray, [
                'template' => $defaultTemplate,
                'content_type' => $this->context->getOption('default_content_type', 1, $this->modx->_userConfig),
                'class_key' => $this->resourceClass,
                'context_key' => $this->ctx,
                'parent' => $this->parent->get('id'),
                'parents' => $this->getParents(),
                'richtext' => $this->context->getOption('richtext_default', 1, $this->modx->_userConfig),
                'hidemenu' => $this->context->getOption('hidemenu_default', 0, $this->modx->_userConfig),
                'published' => $this->context->getOption('publish_default', 0, $this->modx->_userConfig),
                'searchable' => $this->context->getOption('search_default', 1, $this->modx->_userConfig),
                'cacheable' => $this->context->getOption('cache_default', 1, $this->modx->_userConfig),
                'syncsite' => $this->context->getOption('syncsite_default', 1, $this->modx->_userConfig),
                'show_in_tree' => $this->context->getOption('show_in_tree_default', 1, $this->modx->_userConfig),
                'alias_visible' => $this->context->getOption('alias_visible_default', 1, $this->modx->_userConfig),
            ]);

            // Allow certain fields to be prefilled from the OnDocFormRender plugin event
            $newValuesArr = [];
            $allowedFields = ['pagetitle', 'longtitle', 'description', 'introtext', 'content', 'link_attributes', 'alias', 'menutitle'];
            foreach ($allowedFields as $field) {
                $value = $this->resource->get($field);
                if (!empty($value)) {
                    $newValuesArr[$field] = $value;
                }
            }
            $this->resourceArray = array_merge($this->resourceArray, $newValuesArr);

            $this->parent->fromArray($this->resourceArray);
            $this->parent->set('template', $defaultTemplate);
            $this->resource->set('template', $defaultTemplate);
            $this->getResourceGroups();

            // check FC rules
            $overridden = $this->checkFormCustomizationRules($this->resource);
        } else {
            $this->resourceArray = array_merge($this->resourceArray, $reloadData);
            $this->resourceArray['resourceGroups'] = [];
            $this->resourceArray['parents'] = $this->getParents();
            $this->resourceArray['resource_groups'] = $this->modx->getOption('resource_groups',
                $this->resourceArray, []);
            $this->resourceArray['resource_groups'] = is_array($this->resourceArray['resource_groups'])
                ? $this->resourceArray['resource_groups']
                : json_decode($this->resourceArray['resource_groups'], true);
            if (is_array($this->resourceArray['resource_groups'])) {
                foreach ($this->resourceArray['resource_groups'] as $resourceGroup) {
                    $this->resourceArray['resourceGroups'][] = [
                        $resourceGroup['id'],
                        $resourceGroup['name'],
                        $resourceGroup['access'],
                    ];
                }
            }
            unset($this->resourceArray['resource_groups']);
            $this->resource->fromArray($reloadData); // We should have in Reload Data everything needed to do form customization checkings

            // check FC rules
            $overridden = $this->checkFormCustomizationRules($this->resource);
        }

        // apply FC rules
        $this->resourceArray = array_merge($this->resourceArray, $overridden);

        // handle checkboxes and defaults
        $fields = ['published', 'hidemenu', 'isfolder', 'richtext', 'searchable', 'cacheable', 'deleted', 'uri_override', 'syncsite', 'show_in_tree', 'alias_visible'];
        foreach ($fields as $field) {
            $this->resourceArray[$field] = !empty($this->resourceArray[$field]);
        }
        if (!empty($this->resourceArray['parent'])) {
            if ($this->parent->get('id') == $this->resourceArray['parent']) {
                $this->resourceArray['parent_pagetitle'] = $this->modx->stripTags($this->parent->get('pagetitle'));
            } else {
                /** @var modResource $overriddenParent */
                $overriddenParent = $this->modx->getObject(modResource::class, $this->resourceArray['parent']);
                if ($overriddenParent) {
                    $this->resourceArray['parent_pagetitle'] = $this->modx->stripTags($overriddenParent->get('pagetitle'));
                }
            }
        }

        // get TVs
        $this->loadTVs($reloadData);

        // single-use token for creating resource
        $this->setResourceToken();

        return $placeholders;
    }


    /**
     * Return the default template for this resource
     *
     * @return int
     */
    public function getDefaultTemplate()
    {
        $defaultTemplate = $this->context->getOption('default_template', 0, $this->modx->_userConfig);
        if (isset($this->scriptProperties['template'])) {
            $defaultTemplate = $this->scriptProperties['template'];
        } else {
            switch ($this->context->getOption('automatic_template_assignment', 'parent', $this->modx->_userConfig)) {
                case 'parent':
                    if (!empty($this->parent->id))
                        $defaultTemplate = $this->parent->get('template');
                    break;
                case 'sibling':
                    if (!empty($this->parent->id)) {
                        $c = $this->modx->newQuery(modResource::class);
                        $c->where(['parent' => $this->parent->id, 'context_key' => $this->ctx]);
                        $c->sortby('id', 'DESC');
                        $c->limit(1);
                        if ($siblings = $this->modx->getCollection(modResource::class, $c)) {
                            /** @var modResource $sibling */
                            foreach ($siblings as $sibling) {
                                $defaultTemplate = $sibling->get('template');
                            }
                        } else {
                            if (!empty($this->parent->id))
                                $defaultTemplate = $this->parent->get('template');
                        }
                    }
                    break;
                case 'system':
                    // already established
                    break;
            }
        }
        $userGroups = $this->modx->user->getUserGroups();
        $c = $this->modx->newQuery(modActionDom::class);
        $c->innerJoin(modFormCustomizationSet::class, 'FCSet');
        $c->innerJoin(modFormCustomizationProfile::class, 'Profile', 'FCSet.profile = Profile.id');
        $c->leftJoin(modFormCustomizationProfileUserGroup::class, 'ProfileUserGroup', 'Profile.id = ProfileUserGroup.profile');
        $c->leftJoin(modFormCustomizationProfile::class, 'UGProfile', 'UGProfile.id = ProfileUserGroup.profile');
        $c->where([
            'modActionDom.action' => 'resource/create',
            'modActionDom.name' => 'template',
            'modActionDom.container' => 'modx-panel-resource',
            'modActionDom.rule' => 'fieldDefault',
            'modActionDom.active' => true,
            'FCSet.active' => true,
            'Profile.active' => true,
        ]);
        $c->where([[
            'ProfileUserGroup.usergroup:IN' => $userGroups, [
                'OR:ProfileUserGroup.usergroup:IS' => null,
                'AND:UGProfile.active:=' => true,
            ]], 'OR:ProfileUserGroup.usergroup:=' => null,
        ], xPDOQuery::SQL_AND, null, 2);
        /** @var modActionDom $fcDt */
        $fcDtColl = $this->modx->getCollection(modActionDom::class, $c);
        if ($fcDtColl) {
            if ($this->parent) { /* ensure get all parents */
                $p = $this->parent ? $this->parent->get('id') : 0;
                $parentIds = $this->modx->getParentIds($p, 10, [
                    'context' => $this->parent->get('context_key'),
                ]);
                $parentIds[] = $p;
                $parentIds = array_unique($parentIds);
            } else {
                $parentIds = [0];
            }
            // Check for any FC rules relevant to this page's parents
            foreach ($fcDtColl as $fcDt) {
                $constraintField = $fcDt->get('constraint_field');
                if (($constraintField == 'id' || $constraintField == 'parent') && in_array($fcDt->get('constraint'), $parentIds)) {
                    $defaultTemplate = $fcDt->get('value');
                } elseif (empty($constraintField)) {
                    $defaultTemplate = $fcDt->get('value');
                }
            }
        }

        return $defaultTemplate;
    }


    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('document_new');
    }


    /**
     * Return the location of the template file
     *
     * @return string
     */
    public function getTemplateFile()
    {
        return 'resource/create.tpl';
    }
}

class_alias(ResourceCreateManagerController::class, 'DocumentCreateManagerController');
