<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element;


use MODX\Revolution\modCategory;
use MODX\Revolution\modChunk;
use MODX\Revolution\modElement;
use MODX\Revolution\modPlugin;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modSnippet;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modTemplateVar;

/**
 * Grabs all elements for element tree
 *
 * @property string $id (optional) Parent ID of object to grab from. Defaults to 0.
 *
 * @package MODX\Revolution\Processors\Element
 */
class GetNodes extends Processor
{
    public $typeMap = [
        'template' => modTemplate::class,
        'tv' => modTemplateVar::class,
        'chunk' => modChunk::class,
        'snippet' => modSnippet::class,
        'plugin' => modPlugin::class,
        'category' => modCategory::class,
    ];

    public $actionMap = [];

    public function checkPermissions()
    {
        return $this->modx->hasPermission('element_tree');
    }

    public function getLanguageTopics()
    {
        return ['category', 'element'];
    }

    public function initialize()
    {
        $this->setDefaultProperties([
            'stringLiterals' => false,
            'id' => 0,
        ]);

        return true;
    }

    public function process()
    {
        $this->getActions();
        $map = $this->getMap();

        /* load correct mode */
        switch ($map[0]) {
            case 'type': /* if in the element, but not in a category */
                $nodes = $this->getTypeNodes($map);
                break;
            case 'root': /* if clicking one of the root nodes */
                $nodes = $this->getRootNodes($map);
                break;
            case 'category': /* if browsing categories */
                $nodes = $this->getCategoryNodes($map);
                break;
            default: /* if clicking a node in a category */
                $nodes = $this->getInCategoryNodes($map);
                break;
        }

        return $this->getProperty('stringLiterals', false)
            ? $this->modx->toJSON($nodes)
            : $this->toJSON($nodes);
    }

    public function getActions()
    {
        $this->actionMap = [
            'template' => 'element/template/update',
            'tv' => 'element/tv/update',
            'chunk' => 'element/chunk/update',
            'snippet' => 'element/snippet/update',
            'plugin' => 'element/plugin/update',
        ];
    }

    public function getMap()
    {
        /* process ID prefixes */
        $id = $this->getProperty('id');
        $id = empty($id) ? 0 : (substr($id, 0, 2) == 'n_' ? substr($id, 2) : $id);

        /* split the array */

        return explode('_', $id);
    }

    /**
     * Default icons for element types
     *
     * @param $elementIdentifier string Element Type
     *
     * @return string
     */
    public function getNodeIcon($elementIdentifier)
    {
        $defaults = [
            'template' => 'icon icon-columns',
            'chunk' => 'icon icon-th-large',
            'tv' => 'icon icon-list-alt',
            'snippet' => 'icon icon-code',
            'plugin' => 'icon icon-cogs',
            'category' => 'icon icon-folder',
        ];

        return $this->modx->getOption('mgr_tree_icon_' . $elementIdentifier, null, $defaults[$elementIdentifier]);
    }

    public function getRootNodes(array $map)
    {
        $elementType = ucfirst($map[0]);
        $nodes = [];

        /* Templates */
        if ($this->modx->hasPermission('view_template')) {
            $class = $this->modx->hasPermission('new_template') ? ' pnew' : '';
            $class .= $this->modx->hasPermission('new_category') ? ' pnewcat' : '';
            $class .= ' tree-pseudoroot-node';

            $nodes[] = [
                'text' => $this->modx->lexicon('templates'),
                'id' => 'n_type_template',
                'leaf' => false,
                'cls' => $class,
                'iconCls' => $this->getNodeIcon('template'),
                'page' => '',
                'classKey' => 'root',
                'type' => 'template',
                'draggable' => false,
                'pseudoroot' => true,
            ];
        }

        /* TVs */
        if ($this->modx->hasPermission('view_tv')) {
            $class = $this->modx->hasPermission('new_tv') ? ' pnew' : '';
            $class .= $this->modx->hasPermission('new_category') ? ' pnewcat' : '';
            $class .= ' tree-pseudoroot-node';

            $nodes[] = [
                'text' => $this->modx->lexicon('tmplvars'),
                'id' => 'n_type_tv',
                'leaf' => false,
                'cls' => $class,
                'iconCls' => $this->getNodeIcon('tv'),
                'page' => '',
                'classKey' => 'root',
                'type' => 'tv',
                'draggable' => false,
                'pseudoroot' => true,
            ];
        }

        /* Chunks */
        if ($this->modx->hasPermission('view_chunk')) {
            $class = $this->modx->hasPermission('new_chunk') ? ' pnew' : '';
            $class .= $this->modx->hasPermission('new_category') ? ' pnewcat' : '';
            $class .= ' tree-pseudoroot-node';

            $nodes[] = [
                'text' => $this->modx->lexicon('chunks'),
                'id' => 'n_type_chunk',
                'leaf' => false,
                'cls' => $class,
                'iconCls' => $this->getNodeIcon('chunk'),
                'page' => '',
                'classKey' => 'root',
                'type' => 'chunk',
                'draggable' => false,
                'pseudoroot' => true,
            ];
        }

        /* Snippets */
        if ($this->modx->hasPermission('view_snippet')) {
            $class = $this->modx->hasPermission('new_snippet') ? ' pnew' : '';
            $class .= $this->modx->hasPermission('new_category') ? ' pnewcat' : '';
            $class .= ' tree-pseudoroot-node';

            $nodes[] = [
                'text' => $this->modx->lexicon('snippets'),
                'id' => 'n_type_snippet',
                'leaf' => false,
                'cls' => $class,
                'iconCls' => $this->getNodeIcon('snippet'),
                'page' => '',
                'classKey' => 'root',
                'type' => 'snippet',
                'draggable' => false,
                'pseudoroot' => true,
            ];
        }

        /* Plugins */
        if ($this->modx->hasPermission('view_plugin')) {
            $class = $this->modx->hasPermission('new_snippet') ? ' pnew' : '';
            $class .= $this->modx->hasPermission('new_category') ? ' pnewcat' : '';
            $class .= ' tree-pseudoroot-node';

            $nodes[] = [
                'text' => $this->modx->lexicon('plugins'),
                'id' => 'n_type_plugin',
                'leaf' => false,
                'cls' => $class,
                'iconCls' => $this->getNodeIcon('plugin'),
                'page' => '',
                'classKey' => 'root',
                'type' => 'plugin',
                'draggable' => false,
                'pseudoroot' => true,
            ];
        }

        /* Categories */
        if ($this->modx->hasPermission('view_category')) {
            $class = $this->modx->hasPermission('new_category') ? ' pnewcat' : '';
            $class .= ' tree-pseudoroot-node';

            $nodes[] = [
                'text' => $this->modx->lexicon('categories'),
                'id' => 'n_category',
                'leaf' => false,
                'cls' => $class,
                'iconCls' => $this->getNodeIcon('category'),
                'page' => '',
                'classKey' => 'root',
                'type' => 'category',
                'draggable' => false,
                'pseudoroot' => true,
            ];
        }

        return $nodes;
    }

    /**
     * @param array $map
     *
     * @return array
     */
    public function getCategoryNodes(array $map)
    {
        if (!empty($map[1])) {
            /* if grabbing subcategories */
            $c = $this->modx->newQuery(modCategory::class);
            $c->where([
                'parent' => $map[1],
            ]);
            $c->sortby($this->modx->getSelectColumns(modCategory::class, 'modCategory', '', ['category']));
        } else {
            /* if trying to grab all root categories */
            $c = $this->modx->newQuery(modCategory::class);
            $c->where([
                'parent' => 0,
            ]);
            $c->sortby($this->modx->getSelectColumns(modCategory::class, 'modCategory', '', ['category']));
        }

        $c->select($this->modx->getSelectColumns(modCategory::class, 'modCategory'));
        $c->select([
            'COUNT(' . $this->modx->getSelectColumns(modCategory::class, 'Children', '', ['id']) . ') AS childrenCount',
        ]);
        $c->leftJoin(modCategory::class, 'Children');
        $c->groupby($this->modx->getSelectColumns(modCategory::class, 'modCategory'));

        /* set permissions as css classes */
        $class = ['folder'];
        $types = ['template', 'tv', 'chunk', 'snippet', 'plugin'];
        foreach ($types as $type) {
            if ($this->modx->hasPermission('new_' . $type)) {
                $class[] = 'pnew_' . $type;
            }
        }
        if ($this->modx->hasPermission('new_category')) {
            $class[] = 'pnewcat';
        }
        if ($this->modx->hasPermission('edit_category')) {
            $class[] = 'peditcat';
        }
        if ($this->modx->hasPermission('delete_category')) {
            $class[] = 'pdelcat';
        }
        $class = implode(' ', $class);

        /* get and loop through categories */
        $nodes = [];
        $categories = $this->modx->getCollection(modCategory::class, $c);
        /** @var modCategory $category */
        foreach ($categories as $category) {
            if (!$category->checkPolicy('list')) {
                continue;
            }

            $idNote = $this->modx->hasPermission('tree_show_element_ids') ? ' (' . $category->get('id') . ')' : '';
            $nodes[] = [
                'text' => strip_tags($category->get('category')) . $idNote,
                'id' => 'n_category_' . $category->get('id'),
                'pk' => $category->get('id'),
                'data' => $category->toArray(),
                'category' => $category->get('id'),
                'leaf' => false,
                'cls' => $class,
                'iconCls' => $this->getNodeIcon('category'),
                'page' => '',
                'classKey' => 'MODX\\Revolution\\modCategory',
                'type' => 'category',
            ];
        }

        if (isset($map[1])) {
            foreach (array_keys($this->actionMap) as $type) {
                $nodes = array_merge($nodes, $this->getInCategoryElements([$type, $map[1]]));
            }
        }

        return $nodes;
    }

    public function getInCategoryNodes(array $map)
    {
        $nodes = [];
        /* 0: type,  1: element/category  2: elID  3: catID */
        $categoryId = isset($map[3]) ? $map[3] : ($map[1] == 'category' ? $map[2] : 0);
        $elementIdentifier = $map[0];
        $elementType = ucfirst($elementIdentifier);
        $elementClassKey = $this->typeMap[$elementIdentifier];

        /* first handle subcategories */
        $c = $this->modx->newQuery(modCategory::class);
        $c->select($this->modx->getSelectColumns(modCategory::class, 'modCategory'));
        $c->select('COUNT(DISTINCT ' . $elementType . '.id) AS elementCount');
        $c->select('COUNT(DISTINCT ' . $this->modx->getSelectColumns(modCategory::class, 'Children', '',
                ['id']) . ') AS childrenCount');
        $c->leftJoin($elementClassKey, $elementType, $elementType . '.category = modCategory.id');
        $c->leftJoin(modCategory::class, 'Children');
        $c->where([
            'parent' => $categoryId,
        ]);
        $c->groupby($this->modx->getSelectColumns(modCategory::class, 'modCategory'));
        $c->sortby($this->modx->getSelectColumns(modCategory::class, 'modCategory', '', ['category']));
        $categories = $this->modx->getCollection(modCategory::class, $c);

        /* set permissions as css classes */
        $class = ['folder'];
        $types = ['template', 'tv', 'chunk', 'snippet', 'plugin'];
        foreach ($types as $type) {
            if ($this->modx->hasPermission('new_' . $type)) {
                $class[] = 'pnew_' . $type;
            }
        }
        if ($this->modx->hasPermission('new_category')) {
            $class[] = 'pnewcat';
        }
        if ($this->modx->hasPermission('edit_category')) {
            $class[] = 'peditcat';
        }
        if ($this->modx->hasPermission('delete_category')) {
            $class[] = 'pdelcat';
        }
        $class = implode(' ', $class);

        /* loop through categories */
        /** @var modCategory $category */
        foreach ($categories as $category) {
            if (!$category->checkPolicy('list')) {
                continue;
            }
            if ($category->get('elementCount') <= 0 && $category->get('childrenCount') <= 0) {
                continue;
            }

            /* check subcategories recursively */
            if ($category->get('childrenCount') > 0 && $category->get('elementCount') < 1) {
                if ($this->subCategoriesHaveElements($category->get('id'), $elementClassKey) === false) {
                    continue;
                }
            }

            $cc = ($category->get('elementCount') > 0) ? ' [' . $category->get('elementCount') . ']' : '';
            $nodes[] = [
                'text' => strip_tags($category->get('category')) . $cc,
                'id' => 'n_' . $map[0] . '_category_' . ($category->get('id') != null ? $category->get('id') : 0),
                'pk' => $category->get('id'),
                'category' => $category->get('id'),
                'data' => $category->toArray(),
                'leaf' => false,
                'cls' => $class,
                'iconCls' => $this->getNodeIcon('category'),
                'classKey' => modCategory::class,
                'elementType' => $elementType,
                'page' => '',
                'type' => $elementIdentifier,
            ];
        }

        /* all elements in category */
        $c = $this->modx->newQuery($elementClassKey);
        $c->where([
            'category' => $categoryId,
        ]);
        $c->sortby($elementIdentifier === 'template' ? 'templatename' : 'name');
        $elements = $this->modx->getCollection($elementClassKey, $c);

        /* do permission checks */
        $canNewElement = $this->modx->hasPermission('new_' . $elementIdentifier);
        $canEditElement = $this->modx->hasPermission('edit_' . $elementIdentifier);
        $canDeleteElement = $this->modx->hasPermission('delete_' . $elementIdentifier);
        $canNewCategory = $this->modx->hasPermission('new_category');
        $showElementIds = $this->modx->hasPermission('tree_show_element_ids');

        /* loop through elements */
        /** @var modElement $element */
        foreach ($elements as $element) {
            if (!$element->checkPolicy('list')) {
                continue;
            }
            $name = $elementIdentifier === 'template' ? $element->get('templatename') : $element->get('name');
            $caption = $elementClassKey === 'modTemplateVar' ? $element->get('caption') : '';

            $class = [];
            if ($canNewElement) {
                $class[] = 'pnew';
            }
            if ($canEditElement && $element->checkPolicy(['save' => true, 'view' => true])) {
                $class[] = 'pedit';
            }
            if ($canDeleteElement && $element->checkPolicy('remove')) {
                $class[] = 'pdelete';
            }
            if ($canNewCategory) {
                $class[] = 'pnewcat';
            }
            if ($element->get('locked')) {
                $class[] = 'element-node-locked';
            }
            if ($elementClassKey === modPlugin::class && $element->get('disabled')) {
                $class[] = 'element-node-disabled';
            }

            $active = false;
            if ($this->getProperty('currentElement') === $element->id && $this->getProperty('currentAction') === $this->actionMap[$map[0]]) {
                $active = true;
            }

            $idNote = $showElementIds ? ' (' . $element->get('id') . ')' : '';
            $nodes[] = [
                'text' => strip_tags($name) . $idNote,
                'id' => 'n_' . $elementIdentifier . '_element_' . $element->get('id') . '_' . $element->get('category'),
                'pk' => $element->get('id'),
                'category' => $categoryId,
                'leaf' => true,
                'name' => $name,
                'caption' => $caption,
                'cls' => implode(' ', $class),
                'iconCls' => 'icon ' . ($element->get('icon') ? $element->get('icon') : ($element->get('static') ? 'icon-file-code-o' : $this->getNodeIcon($elementIdentifier))),
                'page' => '?a=' . $this->actionMap[$elementIdentifier] . '&id=' . $element->get('id'),
                'type' => $elementIdentifier,
                'elementType' => $elementType,
                'classKey' => $elementClassKey,
                'active' => !$element->get('disabled'),
                'qtip' => strip_tags($element->get('description')),
                'selected' => $active,
            ];
        }

        return $nodes;
    }

    /**
     * @param array $map
     * 0: type of element
     * 1: parent category
     *
     * @return array
     */
    public function getInCategoryElements(array $map)
    {
        $nodes = [];
        $elementIdentifier = $map[0];
        $categoryId = $map[1];
        $elementType = ucfirst($elementIdentifier);
        $elementClassKey = $this->typeMap[$elementIdentifier];

        /* all elements in category */
        $c = $this->modx->newQuery($elementClassKey);
        $c->where([
            'category' => $categoryId,
        ]);
        $c->sortby($elementIdentifier === 'template' ? 'templatename' : 'name');
        $elements = $this->modx->getCollection($elementClassKey, $c);

        /* do permission checks */
        $canNewElement = $this->modx->hasPermission('new_' . $elementIdentifier);
        $canEditElement = $this->modx->hasPermission('edit_' . $elementIdentifier);
        $canDeleteElement = $this->modx->hasPermission('delete_' . $elementIdentifier);
        $canNewCategory = $this->modx->hasPermission('new_category');
        $showElementIds = $this->modx->hasPermission('tree_show_element_ids');

        /* loop through elements */
        /** @var modElement $element */
        foreach ($elements as $element) {
            if (!$element->checkPolicy('list')) {
                continue;
            }
            $name = $elementIdentifier == 'template' ? $element->get('templatename') : $element->get('name');

            $class = [];
            if ($canNewElement) {
                $class[] = 'pnew';
            }
            if ($canEditElement && $element->checkPolicy(['save' => true, 'view' => true])) {
                $class[] = 'pedit';
            }
            if ($canDeleteElement && $element->checkPolicy('remove')) {
                $class[] = 'pdelete';
            }
            if ($canNewCategory) {
                $class[] = 'pnewcat';
            }
            if ($element->get('locked')) {
                $class[] = 'element-node-locked';
            }
            if ($elementClassKey == modPlugin::class && $element->get('disabled')) {
                $class[] = 'element-node-disabled';
            }

            $idNote = $showElementIds ? ' (' . $element->get('id') . ')' : '';
            $nodes[] = [
                'text' => strip_tags($name) . $idNote,
                'id' => 'n_c_' . $elementIdentifier . '_element_' . $element->get('id') . '_' . $element->get('category'),
                'pk' => $element->get('id'),
                'category' => $categoryId,
                'leaf' => true,
                'name' => $name,
                'cls' => implode(' ', $class),
                'iconCls' => 'icon ' . $this->getNodeIcon($elementIdentifier),
                'page' => '?a=' . $this->actionMap[$elementIdentifier] . '&id=' . $element->get('id'),
                'type' => $elementIdentifier,
                'elementType' => $elementType,
                'classKey' => $elementClassKey,
                'active' => !$element->get('disabled'),
                'qtip' => strip_tags($element->get('description')),
            ];
        }

        return $nodes;
    }

    public function getTypeNodes(array $map)
    {
        $nodes = [];
        $elementType = ucfirst($map[1]);
        $elementClassKey = $this->typeMap[$map[1]];

        /* get elements in this type */
        $c = $this->modx->newQuery(modCategory::class);
        $c->select($this->modx->getSelectColumns(modCategory::class, 'modCategory'));
        $c->select('
            COUNT(DISTINCT ' . $this->modx->getSelectColumns($elementClassKey, $elementType, '', ['id']) . ') AS elementCount,
            COUNT(DISTINCT ' . $this->modx->getSelectColumns(modCategory::class, 'Children', '', ['id']) . ') AS childrenCount
        ');
        $c->leftJoin($elementClassKey, $elementType,
            $this->modx->getSelectColumns($elementClassKey, $elementType, '', ['category'])
            . ' = ' . $this->modx->getSelectColumns(modCategory::class, 'modCategory', '', ['id']));
        $c->leftJoin(modCategory::class, 'Children');
        $c->where([
            'modCategory.parent' => 0,
        ]);
        $c->sortby($this->modx->getSelectColumns(modCategory::class, 'modCategory', '', ['category']));
        $c->groupby($this->modx->getSelectColumns(modCategory::class, 'modCategory'));
        $categories = $this->modx->getCollection(modCategory::class, $c);

        /* set permissions as css classes */
        $class = 'folder';
        $types = ['template', 'tv', 'chunk', 'snippet', 'plugin'];
        foreach ($types as $type) {
            if ($this->modx->hasPermission('new_' . $type)) {
                $class .= ' pnew_' . $type;
            }
        }
        $class .= $this->modx->hasPermission('new_category') ? ' pnewcat' : '';
        $class .= $this->modx->hasPermission('edit_category') ? ' peditcat' : '';
        $class .= $this->modx->hasPermission('delete_category') ? ' pdelcat' : '';

        /* loop through categories with elements in this type */
        /** @var modCategory $category */
        foreach ($categories as $category) {
            if (!$category->checkPolicy('list')) {
                continue;
            }
            $elCount = (int)$category->get('elementCount');
            $catCount = (int)$category->get('childrenCount');
            if ($elCount < 1 && $catCount < 1 && $category->get('id') !== 0) {
                continue;
            }

            if ($catCount > 0 && $elCount < 1) {
                if ($this->subCategoriesHaveElements($category->get('id'), $elementClassKey) === false) {
                    continue;
                }
            }

            $cc = $elCount > 0 ? ' [' . $elCount . ']' : '';

            $nodes[] = [
                'text' => strip_tags($category->get('category')) . $cc,
                'id' => 'n_' . $map[1] . '_category_' . ($category->get('id') !== null ? $category->get('id') : 0),
                'pk' => $category->get('id'),
                'category' => $category->get('id'),
                'data' => $category->toArray(),
                'leaf' => false,
                'cls' => $class,
                'iconCls' => $this->getNodeIcon('category'),
                'page' => '',
                'classKey' => modCategory::class,
                'elementType' => $elementType,
                'type' => $map[1],
            ];
            unset($elCount, $childCats);
        }

        /* now add elements in this type without a category */
        $c = $this->modx->newQuery($elementClassKey);
        $c->where([
            'category' => 0,
        ]);
        $c->sortby($elementClassKey === modTemplate::class ? 'templatename' : 'name');
        $elements = $this->modx->getCollection($elementClassKey, $c);

        /* do permission checks */
        $canNewCategory = $this->modx->hasPermission('new_category');
        $canEditElement = $this->modx->hasPermission('edit_' . $map[1]);
        $canDeleteElement = $this->modx->hasPermission('delete_' . $map[1]);
        $canNewElement = $this->modx->hasPermission('new_' . $map[1]);
        $showElementIds = $this->modx->hasPermission('tree_show_element_ids');

        /* loop through elements */
        /** @var modElement $element */
        foreach ($elements as $element) {
            if (!$element->checkPolicy('list')) {
                continue;
            }
            /* handle templatename case */
            $name = $elementClassKey === modTemplate::class ? $element->get('templatename') : $element->get('name');
            $caption = $elementClassKey === modTemplateVar::class ? $element->get('caption') : '';

            $class = [];
            if ($canNewElement) {
                $class[] = 'pnew';
            }
            if ($canEditElement && $element->checkPolicy(['save' => true, 'view' => true])) {
                $class[] = 'pedit';
            }
            if ($canDeleteElement && $element->checkPolicy('remove')) {
                $class[] = 'pdelete';
            }
            if ($canNewCategory) {
                $class[] = 'pnewcat';
            }
            if ($element->get('locked')) {
                $class[] = 'element-node-locked';
            }
            if ($elementClassKey === modPlugin::class && $element->get('disabled')) {
                $class[] = 'element-node-disabled';
            }
            if (!empty($scriptProperties['currentElement']) && $scriptProperties['currentElement'] == $element->get('id') && $scriptProperties['currentAction'] == $this->actionMap[$map[1]]) {
                $class[] = 'active-node';
            }

            $active = false;
            if ($this->getProperty('currentElement') == $element->id && $this->getProperty('currentAction') == $this->actionMap[$map[1]]) {
                $active = true;
            }

            $idNote = $showElementIds ? ' (' . $element->get('id') . ')' : '';
            $nodes[] = [
                'text' => strip_tags($name) . $idNote,
                'id' => 'n_' . $map[1] . '_element_' . $element->get('id') . '_0',
                'pk' => $element->get('id'),
                'category' => 0,
                'leaf' => true,
                'name' => $name,
                'caption' => $caption,
                'cls' => implode(' ', $class),
                'iconCls' => 'icon ' . ($element->get('icon') ? $element->get('icon') : ($element->get('static') ? 'icon-file-code-o' : $this->getNodeIcon($map[1]))),
                'page' => '?a=' . $this->actionMap[$map[1]] . '&id=' . $element->get('id'),
                'type' => $map[1],
                'elementType' => $elementType,
                'classKey' => $elementClassKey,
                'active' => !$element->get('disabled'),
                'qtip' => strip_tags($element->get('description')),
                'selected' => $active,
            ];
        }

        return $nodes;
    }

    protected function subCategoriesHaveElements($categoryId, $elementClassKey)
    {
        $return = false;

        $categories = $this->modx->getCollection(modCategory::class, [
            'parent' => $categoryId,
        ]);

        $elementType = array_search($elementClassKey, $this->typeMap, true);

        foreach ($categories as $category) {
            $c = $this->modx->newQuery(modCategory::class);
            $c->select($this->modx->getSelectColumns(modCategory::class, 'modCategory'));
            $c->select('COUNT(DISTINCT ' . $elementType . '.id) AS elementCount');
            $c->select('COUNT(DISTINCT ' . $this->modx->getSelectColumns(modCategory::class, 'Children', '',
                    ['id']) . ') AS childrenCount');
            $c->leftJoin($elementClassKey, $elementType, $elementType . '.category = modCategory.id');
            $c->leftJoin(modCategory::class, 'Children');
            $c->where([
                'id' => $category->get('id'),
            ]);
            $c->groupby($this->modx->getSelectColumns(modCategory::class, 'modCategory'));
            $subCategory = $this->modx->getObject(modCategory::class, $c);

            if ($subCategory->get('elementCount') > 0) {
                $return = true;
            }
            if ($return == false && $subCategory->get('childrenCount') > 0) {
                $return = $this->subCategoriesHaveElements($subCategory->get('id'), $elementClassKey);
            }

        }

        return $return;
    }
}
