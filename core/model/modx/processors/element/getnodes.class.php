<?php
/**
 * Grabs all elements for element tree
 *
 * @param string $id (optional) Parent ID of object to grab from. Defaults to 0.
 *
 * @package modx
 * @subpackage processors.layout.tree.element
 */
class modElementGetNodesProcessor extends modProcessor {
    public $typeMap = array(
        'template' => 'modTemplate',
        'tv' => 'modTemplateVar',
        'chunk' => 'modChunk',
        'snippet' => 'modSnippet',
        'plugin' => 'modPlugin',
        'category' => 'modCategory',
    );
    public $actionMap = array();
    
    public function checkPermissions() {
        return $this->modx->hasPermission('element_tree');
    }
    public function getLanguageTopics() {
        return array('category','element');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'stringLiterals' => false,
            'id' => 0,
        ));
        return true;
    }

    public function process() {
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

        if ($this->getProperty('stringLiterals',false)) {
            return $this->modx->toJSON($nodes);
        } else {
            return $this->toJSON($nodes);
        }
    }

    public function getActions() {
        $actions = $this->modx->request->getAllActionIDs();
        $this->actionMap = array(
            'template' => $actions['element/template/update'],
            'tv' => $actions['element/tv/update'],
            'chunk' => $actions['element/chunk/update'],
            'snippet' => $actions['element/snippet/update'],
            'plugin' => $actions['element/plugin/update'],
        );
    }

    public function getMap() {
        /* process ID prefixes */
        $id = $this->getProperty('id');
        $id = empty($id) ? 0 : (substr($id,0,2) == 'n_' ? substr($id,2) : $id);
        /* split the array */
        return explode('_',$id);
    }

    public function getRootNodes(array $map) {
        $elementType = ucfirst($map[0]);
        $nodes = array();

        /* templates */
        if ($this->modx->hasPermission('view_template')) {
            $class = 'icon-template';
            $class .= $this->modx->hasPermission('new_template') ? ' pnew' : '';
            $class .= $this->modx->hasPermission('new_category') ? ' pnewcat' : '';

            $nodes[] = array(
                'text' => $this->modx->lexicon('templates'),
                'id' => 'n_type_template',
                'leaf' => false,
                'cls' => $class,
                'page' => '',
                'classKey' => 'root',
                'type' => 'template',
                'draggable' => false,
            );
        }

        /* TVs */
        if ($this->modx->hasPermission('view_tv')) {
            $class = 'icon-tv';
            $class .= $this->modx->hasPermission('new_tv') ? ' pnew' : '';
            $class .= $this->modx->hasPermission('new_category') ? ' pnewcat' : '';

            $nodes[] = array(
                'text' => $this->modx->lexicon('tmplvars'),
                'id' => 'n_type_tv',
                'leaf' => false,
                'cls' => $class,
                'page' => '',
                'classKey' => 'root',
                'type' => 'tv',
                'draggable' => false,
            );
        }

        /* chunks */
        if ($this->modx->hasPermission('view_chunk')) {
            $class = 'icon-chunk';
            $class .= $this->modx->hasPermission('new_chunk') ? ' pnew' : '';
            $class .= $this->modx->hasPermission('new_category') ? ' pnewcat' : '';

            $nodes[] = array(
                'text' => $this->modx->lexicon('chunks'),
                'id' => 'n_type_chunk',
                'leaf' => false,
                'cls' => $class,
                'page' => '',
                'classKey' => 'root',
                'type' => 'chunk',
                'draggable' => false,
            );
        }

        /* snippets */
        if ($this->modx->hasPermission('view_snippet')) {
            $class = 'icon-snippet';
            $class .= $this->modx->hasPermission('new_snippet') ? ' pnew' : '';
            $class .= $this->modx->hasPermission('new_category') ? ' pnewcat' : '';

            $nodes[] = array(
                'text' => $this->modx->lexicon('snippets'),
                'id' => 'n_type_snippet',
                'leaf' => false,
                'cls' => $class,
                'page' => '',
                'classKey' => 'root',
                'type' => 'snippet',
                'draggable' => false,
            );
        }

        /* plugins */
        if ($this->modx->hasPermission('view_plugin')) {
            $class = 'icon-plugin';
            $class .= $this->modx->hasPermission('new_snippet') ? ' pnew' : '';
            $class .= $this->modx->hasPermission('new_category') ? ' pnewcat' : '';

            $nodes[] = array(
                'text' => $this->modx->lexicon('plugins'),
                'id' => 'n_type_plugin',
                'leaf' => false,
                'cls' => $class,
                'page' => '',
                'classKey' => 'root',
                'type' => 'plugin',
                'draggable' => false,
            );
        }

        /* categories */
        if ($this->modx->hasPermission('view_category')) {
            $class = 'icon-category';
            $class .= $this->modx->hasPermission('new_category') ? ' pnewcat' : '';

            $nodes[] = array(
                'text' => $this->modx->lexicon('categories'),
                'id' => 'n_category',
                'leaf' => 0,
                'cls' => $class,
                'page' => '',
                'classKey' => 'root',
                'type' => 'category',
                'draggable' => false,
            );
        }

        return $nodes;
    }
    
    public function getCategoryNodes(array $map) {
        if (!empty($map[1])) {
            /* if grabbing subcategories */
            $c = $this->modx->newQuery('modCategory');
            $c->where(array(
                'parent' => $map[1],
            ));
            $c->sortby($this->modx->getSelectColumns('modCategory','modCategory','',array('category')),'ASC');
        } else {
            /* if trying to grab all root categories */
            $c = $this->modx->newQuery('modCategory');
            $c->where(array(
                'parent' => 0,
            ));
            $c->sortby($this->modx->getSelectColumns('modCategory','modCategory','',array('category')),'ASC');
        }
        
        $c->select($this->modx->getSelectColumns('modCategory','modCategory'));
        $c->select(array(
            'COUNT('.$this->modx->getSelectColumns('modCategory','Children','',array('id')).') AS childrenCount',
        ));
        $c->leftJoin('modCategory','Children');
        $c->groupby($this->modx->getSelectColumns('modCategory','modCategory'));
        
        /* set permissions as css classes */
        $class = array('icon-category','folder');
        $types = array('template','tv','chunk','snippet','plugin');
        foreach ($types as $type) {
            if ($this->modx->hasPermission('new_'.$type)) {
                $class[] = 'pnew_'.$type;
            }
        }
        if ($this->modx->hasPermission('new_category')) $class[] = 'pnewcat';
        if ($this->modx->hasPermission('edit_category')) $class[] = 'peditcat';
        if ($this->modx->hasPermission('delete_category')) $class[] = 'pdelcat';
        $class = implode(' ',$class);
        
        /* get and loop through categories */
        $nodes = array();
        $categories = $this->modx->getCollection('modCategory',$c);
        /** @var modCategory $category */
        foreach ($categories as $category) {
            if (!$category->checkPolicy('list')) continue;
        
            $idNote = $this->modx->hasPermission('tree_show_element_ids') ? ' (' . $category->get('id') . ')' : '';
            $nodes[] = array(
                'text' => strip_tags($category->get('category')).$idNote,
                'id' => 'n_category_'.$category->get('id'),
                'pk' => $category->get('id'),
                'data' => $category->toArray(),
                'category' => $category->get('id'),
                'leaf' => false,
                'cls' => $class,
                'page' => '',
                'classKey' => 'modCategory',
                'type' => 'category',
            );
        }
        
        return $nodes;
    }
    
    public function getInCategoryNodes(array $map) {
        $nodes = array();
        /* 0: type,  1: element/category  2: elID  3: catID */
        $categoryId = isset($map[3]) ? $map[3] : ($map[1] == 'category' ? $map[2] : 0);
        $elementIdentifier = $map[0];
        $elementType = ucfirst($elementIdentifier);
        $elementClassKey = $this->typeMap[$elementIdentifier];
        
        /* first handle subcategories */
        $c = $this->modx->newQuery('modCategory');
        $c->select($this->modx->getSelectColumns('modCategory','modCategory'));
        $c->select('COUNT('.$elementClassKey.'.id) AS elementCount');
        $c->leftJoin($elementClassKey,$elementClassKey,$elementClassKey.'.category = modCategory.id');
        $c->where(array(
            'parent' => $categoryId,
        ));
        $c->groupby($this->modx->getSelectColumns('modCategory','modCategory'));
        $c->sortby($this->modx->getSelectColumns('modCategory','modCategory','',array('category')),'ASC');
        $categories = $this->modx->getCollection('modCategory',$c);
        
        /* set permissions as css classes */
        $class = array('icon-category','folder');
        $types = array('template','tv','chunk','snippet','plugin');
        foreach ($types as $type) {
            if ($this->modx->hasPermission('new_'.$type)) {
                $class[] = 'pnew_'.$type;
            }
        }
        if ($this->modx->hasPermission('new_category')) $class[] = 'pnewcat';
        if ($this->modx->hasPermission('edit_category')) $class[] = 'peditcat';
        if ($this->modx->hasPermission('delete_category')) $class[] = 'pdelcat';
        $class = implode(' ',$class);
        
        /* loop through categories */
        /** @var modCategory $category */
        foreach ($categories as $category) {
            if (!$category->checkPolicy('list')) continue;
            if ($category->get('elementCount') <= 0) continue;
        
            $nodes[] = array(
                'text' => strip_tags($category->get('category')) . ' (' . $category->get('elementCount') . ')',
                'id' => 'n_'.$map[0].'_category_'.($category->get('id') != null ? $category->get('id') : 0),
                'pk' => $category->get('id'),
                'category' => $category->get('id'),
                'data' => $category->toArray(),
                'leaf' => false,
                'cls' => $class,
                'classKey' => 'modCategory',
                'elementType' => $elementType,
                'page' => '',
                'type' => $elementIdentifier,
            );
        }
        
        /* all elements in category */
        $c = $this->modx->newQuery($elementClassKey);
        $c->where(array(
            'category' => $categoryId
        ));
        $c->sortby($elementIdentifier == 'template' ? 'templatename' : 'name','ASC');
        $elements = $this->modx->getCollection($elementClassKey,$c);
        
        /* do permission checks */
        $canNewElement = $this->modx->hasPermission('new_'.$elementIdentifier);
        $canEditElement = $this->modx->hasPermission('edit_'.$elementIdentifier);
        $canDeleteElement = $this->modx->hasPermission('delete_'.$elementIdentifier);
        $canNewCategory = $this->modx->hasPermission('new_category');
        $showElementIds = $this->modx->hasPermission('tree_show_element_ids');
        
        /* loop through elements */
        /** @var modElement $element */
        foreach ($elements as $element) {
            if (!$element->checkPolicy('list')) continue;
            $name = $elementIdentifier == 'template' ? $element->get('templatename') : $element->get('name');
        
            $class = array('icon-'.$elementIdentifier);
            if ($canNewElement) $class[] = 'pnew';
            if ($canEditElement && $element->checkPolicy(array('save' => true, 'view' => true))) $class[] = 'pedit';
            if ($canDeleteElement && $element->checkPolicy('remove')) $class[] = 'pdelete';
            if ($canNewCategory) $class[] = 'pnewcat';
            if ($element->get('locked')) $class[] = 'element-node-locked';
            if ($elementClassKey == 'modPlugin' && $element->get('disabled')) {
                $class[] = 'element-node-disabled';
            }
        
            $idNote = $showElementIds ? ' (' . $element->get('id') . ')' : '';
            $nodes[] = array(
                'text' => strip_tags($name) . $idNote,
                'id' => 'n_'.$elementIdentifier.'_element_'.$element->get('id').'_'.$element->get('category'),
                'pk' => $element->get('id'),
                'category' => $categoryId,
                'leaf' => 1,
                'name' => $name,
                'cls' => implode(' ',$class),
                'page' => 'index.php?a='.$this->actionMap[$elementIdentifier].'&id='.$element->get('id'),
                'type' => $elementIdentifier,
                'elementType' => $elementType,
                'classKey' => $elementClassKey,
                'active' => !$element->get('disabled'),
                'qtip' => strip_tags($element->get('description')),
            );
        }
        
        return $nodes;
    }
    
    public function getTypeNodes(array $map) {
        $nodes = array();
        $elementType = ucfirst($map[1]);
        $elementClassKey = $this->typeMap[$map[1]];
        
        /* get elements in this type */
        $c = $this->modx->newQuery('modCategory');
        $c->select($this->modx->getSelectColumns('modCategory','modCategory'));
        $c->select('
            COUNT('.$this->modx->getSelectColumns($elementClassKey,$elementClassKey,'',array('id')).') AS elementCount,
            COUNT('.$this->modx->getSelectColumns('modCategory','Children','',array('id')).') AS childrenCount
        ');
        $c->leftJoin($elementClassKey,$elementClassKey,$this->modx->getSelectColumns($elementClassKey,$elementClassKey,'',array('category')).' = '.$this->modx->getSelectColumns('modCategory','modCategory','',array('id')));
        $c->leftJoin('modCategory','Children');
        $c->where(array(
            'modCategory.parent' => 0,
        ));
        $c->sortby($this->modx->getSelectColumns('modCategory','modCategory','',array('category')),'ASC');
        $c->groupby($this->modx->getSelectColumns('modCategory','modCategory'));
        $categories = $this->modx->getCollection('modCategory',$c);
        
        /* set permissions as css classes */
        $class = 'icon-category folder';
        $types = array('template','tv','chunk','snippet','plugin');
        foreach ($types as $type) {
            if ($this->modx->hasPermission('new_'.$type)) {
                $class .= ' pnew_'.$type;
            }
        }
        $class .= $this->modx->hasPermission('new_category') ? ' pnewcat' : '';
        $class .= $this->modx->hasPermission('edit_category') ? ' peditcat' : '';
        $class .= $this->modx->hasPermission('delete_category') ? ' pdelcat' : '';
        
        /* loop through categories with elements in this type */
        /** @var modCategory $category */
        foreach ($categories as $category) {
            if (!$category->checkPolicy('list')) continue;
            $elCount = (int)$category->get('elementCount');
            $catCount = (int)$category->get('childrenCount');
            if ($elCount < 1 && $catCount < 1 && $category->get('id') != 0) {
                continue;
            }
            $cc = $elCount > 0 ? ' ('.$elCount.')' : '';
        
            $nodes[] = array(
                'text' => strip_tags($category->get('category')).$cc,
                'id' => 'n_'.$map[1].'_category_'.($category->get('id') != null ? $category->get('id') : 0),
                'pk' => $category->get('id'),
                'category' => $category->get('id'),
                'data' => $category->toArray(),
                'leaf' => false,
                'cls' => $class,
                'page' => '',
                'classKey' => 'modCategory',
                'elementType' => $elementType,
                'type' => $map[1],
            );
            unset($elCount,$childCats);
        }
        
        /* now add elements in this type without a category */
        $c = $this->modx->newQuery($elementClassKey);
        $c->where(array(
            'category' => 0,
        ));
        $c->sortby($elementClassKey == 'modTemplate' ? 'templatename' : 'name','ASC');
        $elements = $this->modx->getCollection($elementClassKey,$c);
        
        /* do permission checks */
        $canNewCategory = $this->modx->hasPermission('new_category');
        $canEditElement = $this->modx->hasPermission('edit_'.$map[1]);
        $canDeleteElement = $this->modx->hasPermission('delete_'.$map[1]);
        $canNewElement = $this->modx->hasPermission('new_'.$map[1]);
        $showElementIds = $this->modx->hasPermission('tree_show_element_ids');
        
        /* loop through elements */
        /** @var modElement $element */
        foreach ($elements as $element) {
            if (!$element->checkPolicy('list')) continue;
            /* handle templatename case */
            $name = $elementClassKey == 'modTemplate' ? $element->get('templatename') : $element->get('name');
        
            $class = array('icon-'.$map[1]);
            if ($canNewElement) $class[] = 'pnew';
            if ($canEditElement && $element->checkPolicy(array('save' => true,'view' => true))) $class[] = 'pedit';
            if ($canDeleteElement && $element->checkPolicy('remove')) $class[] = 'pdelete';
            if ($canNewCategory) $class[] = 'pnewcat';
            if ($element->get('locked')) $class[] = 'element-node-locked';
            if ($elementClassKey == 'modPlugin' && $element->get('disabled')) {
                $class[] = 'element-node-disabled';
            }
            if (!empty($scriptProperties['currentElement']) && $scriptProperties['currentElement'] == $element->get('id') && $scriptProperties['currentAction'] == $this->actionMap[$map[1]]) {
                $class[] = 'active-node';
            }
        
            $idNote = $showElementIds ? ' (' . $element->get('id') . ')' : '';
            $nodes[] = array(
                'text' => strip_tags($name) . $idNote,
                'id' => 'n_'.$map[1].'_element_'.$element->get('id').'_0',
                'pk' => $element->get('id'),
                'category' => 0,
                'leaf' => true,
                'name' => $name,
                'cls' => implode(' ',$class),
                'page' => '?a='.$this->actionMap[$map[1]].'&id='.$element->get('id'),
                'type' => $map[1],
                'elementType' => $elementType,
                'classKey' => $elementClassKey,
                'active' => !$element->get('disabled'),
                'qtip' => strip_tags($element->get('description')),
            );
        }
        return $nodes;
    }
}
return 'modElementGetNodesProcessor';