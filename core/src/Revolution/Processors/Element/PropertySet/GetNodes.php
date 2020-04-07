<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\PropertySet;


use MODX\Revolution\modCategory;
use MODX\Revolution\modChunk;
use MODX\Revolution\modElement;
use MODX\Revolution\modElementPropertySet;
use MODX\Revolution\Processors\ModelProcessor;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modPropertySet;
use MODX\Revolution\modSnippet;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modTemplateVar;

/**
 * Grabs all elements for propertyset tree
 *
 * @property string $id (optional) Parent ID of object to grab from. Defaults to 0.
 *
 * @package MODX\Revolution\Processors\Element\PropertySet
 */
class GetNodes extends ModelProcessor
{
    public $classKey = modPropertySet::class;
    public $objectType = 'propertyset';
    public $permission = 'view_propertyset';
    public $languageTopics = ['element', 'propertyset'];

    /** @var array Current node */
    public $node;
    /** @var array Permissions user has */
    public $has;


    public function initialize()
    {
        $id = $this->getProperty($this->primaryKeyField, 0);
        $id = (substr($id, 0, 2) == 'n_') ? substr($id, 2) : $id;
        $this->node = explode('_', $id);

        /* check permissions */
        $this->has = [
            'save' => $this->modx->hasPermission('save_propertyset'),
            'remove' => $this->modx->hasPermission('delete_propertyset'),
            'new' => $this->modx->hasPermission('new_propertyset'),
        ];

        return true;
    }

    /**
     * Default icons for element types
     *
     * @param $elementIdentifier string Element Type
     *
     * @return string
     */
    function getNodeIcon($elementIdentifier = '')
    {
        $elementIdentifier = strtolower($elementIdentifier);
        $defaults = [
            'template' => 'icon icon-columns',
            'chunk' => 'icon icon-th-large',
            'tv' => 'icon icon-asterisk',
            'snippet' => 'icon icon-code',
            'plugin' => 'icon icon-cog',
            'category' => 'icon icon-folder',
            'propertyset' => 'icon icon-sitemap',
        ];

        return $defaults[$elementIdentifier];
    }

    /* grab all categories and uncategorized property sets */
    public function getRootNode()
    {
        $list = [];

        $c = $this->modx->newQuery(modCategory::class);
        $c->sortby($this->modx->escape('rank'), 'ASC');
        $c->sortby('category', 'ASC');
        $categories = $this->modx->getIterator(modCategory::class, $c);

        /** @var modCategory $category */
        foreach ($categories as $category) {
            if (!$category->checkPolicy('list')) {
                continue;
            }
            $propertySets = $category->getMany('PropertySets');
            if (count($propertySets) < 1) {
                continue;
            }

            $categoryArray = [
                'text' => $category->get('category'),
                'id' => 'cat_' . $category->get('id'),
                'leaf' => false,
                'cls' => 'icon-category',
                'iconCls' => $this->getNodeIcon('category'),
                'href' => '',
                'class_key' => 'MODX\\Revolution\\modCategory',
                'menu' => [],
            ];

            $list[] = $categoryArray;
        }
        unset($c, $propertySets, $categories, $category, $categoryArray);

        $list = array_merge($list, $this->getCategoryNode(0));

        return $list;
    }

    /* grab all property sets for that category */
    public function getCategoryNode($category)
    {
        $list = [];

        $c = $this->modx->newQuery($this->classKey);
        $c->where(['category' => $category]);
        $c->sortby('name', 'ASC');
        $sets = $this->modx->getIterator($this->classKey, $c);

        /** @var modPropertySet $set */
        foreach ($sets as $set) {
            $menu = [];
            if ($this->has['save']) {
                $menu[] = [
                    'text' => $this->modx->lexicon($this->objectType . '_element_add'),
                    'handler' => 'function(itm,e) {
                        this.addElement(itm,e);
                    }',
                ];
                $menu[] = '-';
                $menu[] = [
                    'text' => $this->modx->lexicon($this->objectType . '_update'),
                    'handler' => 'function(itm,e) {
                        this.updateSet(itm,e);
                    }',
                ];
            }
            if ($this->has['new'] && $this->has['save']) {
                $menu[] = [
                    'text' => $this->modx->lexicon($this->objectType . '_duplicate'),
                    'handler' => 'function(itm,e) {
                        this.duplicateSet(itm,e);
                    }',
                ];
            }
            if ($this->has['remove']) {
                $menu[] = '-';
                $menu[] = [
                    'text' => $this->modx->lexicon($this->objectType . '_remove'),
                    'handler' => 'function(itm,e) {
                        this.removeSet(itm,e);
                    }',
                ];
            }

            $setArray = [
                'text' => $set->get('name'),
                'id' => 'ps_' . $set->get('id'),
                'leaf' => false,
                'cls' => 'icon-propertyset',
                'iconCls' => $this->getNodeIcon('propertyset'),
                'href' => '',
                'class_key' => $this->classKey,
                'data' => $set->toArray(),
                'qtip' => $set->get('description'),
                'menu' => ['items' => $menu],
            ];
            $list[] = $setArray;
        }

        return $list;
    }

    /* grab all elements for property set */
    public function getPropertySetNode()
    {
        $list = [];

        $classes = [
            modChunk::class => $this->modx->lexicon('chunk', [], 'en'),
            modPlugin::class => $this->modx->lexicon('plugin', [], 'en'),
            modSnippet::class => $this->modx->lexicon('snippet', [], 'en'),
            modTemplate::class => $this->modx->lexicon('template', [], 'en'),
            modTemplateVar::class => $this->modx->lexicon('tv', [], 'en'),
        ];

        foreach ($classes as $class => $alias) {
            $c = $this->modx->newQuery(modElementPropertySet::class);
            $c->select("modElementPropertySet.*, {$alias}.*");
            $c->innerJoin($class, $alias, [
                "modElementPropertySet.element = {$alias}.id",
                'modElementPropertySet.element_class' => $class,
                'modElementPropertySet.property_set' => $this->node[1],
            ]);
            $uk = ($class == modTemplate::class) ? 'templatename' : 'name';
            $c->sortby($alias . '.' . $uk, 'ASC');
            $els = $this->modx->getIterator(modElementPropertySet::class, $c);

            /** @var modElementPropertySet $el */
            foreach ($els as $el) {
                /** @var modElement $elem */
                $elem = $el->getOne('Element');
                if (!$elem->checkPolicy('list')) {
                    continue;
                }
                $menu = [];
                if ($this->has['remove']) {
                    $menu[] = [
                        'text' => $this->modx->lexicon($this->objectType . '_element_remove'),
                        'handler' => 'function(itm,e) {
                            this.removeElement(itm,e);
                        }',
                    ];
                }
                $setArray = [
                    'text' => $el->get('name'),
                    'id' => 'el_' . $el->get('property_set') . '_' . $el->get('id') . '_' . $class,
                    'leaf' => true,
                    'href' => '',
                    'pk' => $el->get('id'),
                    'qtip' => "<i>{$alias}</i>: <b>{$el->get('name')}</b>" . ($el->get('description') != '' ? ' - ' . $el->get('description') : ''),
                    'cls' => 'icon-' . strtolower($alias),
                    'iconCls' => $this->getNodeIcon($alias),
                    'propertyset' => $el->get('property_set'),
                    'element_class' => $class,
                    'menu' => ['items' => $menu],
                ];
                $list[] = $setArray;
            }
            unset($c, $els, $el, $menu);
        }

        return $list;
    }

    public function process()
    {
        switch ($this->node[0]) {
            case 'root':
                $list = $this->getRootNode();
                break;
            case 'cat':
                $list = isset($this->node[1]) ? $this->getCategoryNode($this->node[1]) : [];
                break;
            case 'ps':
                $list = $this->getPropertySetNode();
                break;
            default:
                $list = [];
                break;
        }

        return $this->toJSON($list);
    }
}

