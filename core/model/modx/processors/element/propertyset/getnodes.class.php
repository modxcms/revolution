<?php
/**
 * Grabs all elements for propertyset tree
 *
 * @param string $id (optional) Parent ID of object to grab from. Defaults to 0.
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */

class modPropertySetGetNodesProcessor extends modObjectProcessor {
    public $objectType = 'propertyset';
    public $classKey = 'modPropertySet';
    public $permission = 'view_propertyset';
    public $languageTopics = array('element', 'propertyset');

    /** @var array Current node */
    public $node;
    /** @var array Permissions user has */
    public $has;


    public function initialize() {
        $id = $this->getProperty($this->primaryKeyField, 0);
        $id = (substr($id, 0, 2) == 'n_') ? substr($id, 2) : $id;
        $this->node = explode('_', $id);

        /* check permissions */
        $this->has = array(
            'save' => $this->modx->hasPermission('save_propertyset'),
            'remove' => $this->modx->hasPermission('delete_propertyset'),
            'new' => $this->modx->hasPermission('new_propertyset'),
        );

        return true;
    }

    /**
     * Default icons for element types
     * @param $elementIdentifier string Element Type
     * @return string
     */
    function getNodeIcon($elementIdentifier = ''){
        $elementIdentifier = strtolower($elementIdentifier);
        $defaults = array(
            'template' => 'icon icon-columns',
            'chunk' => 'icon icon-th-large',
            'tv' => 'icon icon-asterisk',
            'snippet' => 'icon icon-code',
            'plugin' => 'icon icon-cog',
            'category' => 'icon icon-folder',
            'propertyset' => 'icon icon-sitemap',
        );
        return $defaults[$elementIdentifier];
    }

    /* grab all categories and uncategorized property sets */
    public function getRootNode() {
        $list = array();

        $c = $this->modx->newQuery('modCategory');
        $c->sortby('rank', 'ASC');
        $c->sortby('category', 'ASC');
        $categories = $this->modx->getIterator('modCategory', $c);

        /** @var modCategory $category */
        foreach ($categories as $category) {
            if (!$category->checkPolicy('list')) continue;
            $propertySets = $category->getMany('PropertySets');
            if (count($propertySets) < 1) continue;

            $categoryArray = array(
                'text' => $category->get('category'),
                'id' => 'cat_'.$category->get('id'),
                'leaf' => false,
                'cls' => 'icon-category',
                'iconCls' => $this->getNodeIcon('category'),
                'href' => '',
                'class_key' => 'modCategory',
                'menu' => array(),
            );

            $list[] = $categoryArray;
        }
        unset($c,$propertySets,$categories,$category,$categoryArray);

        $list = array_merge($list, $this->getCategoryNode(0));

        return $list;
    }

    /* grab all property sets for that category */
    public function getCategoryNode($category) {
        $list = array();

        $c = $this->modx->newQuery($this->classKey);
        $c->where(array('category' => $category));
        $c->sortby('name', 'ASC');
        $sets = $this->modx->getIterator($this->classKey, $c);

        /** @var modPropertySet $set */
        foreach ($sets as $set) {
            $menu = array();
            if ($this->has['save']) {
                $menu[] = array(
                    'text' => $this->modx->lexicon($this->objectType.'_element_add'),
                    'handler' => 'function(itm,e) {
                        this.addElement(itm,e);
                    }',
                );
                $menu[] = '-';
                $menu[] = array(
                    'text' => $this->modx->lexicon($this->objectType.'_update'),
                    'handler' => 'function(itm,e) {
                        this.updateSet(itm,e);
                    }',
                );
            }
            if ($this->has['new'] && $this->has['save']) {
                $menu[] = array(
                    'text' => $this->modx->lexicon($this->objectType.'_duplicate'),
                    'handler' => 'function(itm,e) {
                        this.duplicateSet(itm,e);
                    }',
                );
            }
            if ($this->has['remove']) {
                $menu[] = '-';
                $menu[] = array(
                    'text' => $this->modx->lexicon($this->objectType.'_remove'),
                    'handler' => 'function(itm,e) {
                        this.removeSet(itm,e);
                    }',
                );
            }

            $setArray = array(
                'text' => $set->get('name'),
                'id' => 'ps_'.$set->get('id'),
                'leaf' => false,
                'cls' => 'icon-propertyset',
                'iconCls' => $this->getNodeIcon('propertyset'),
                'href' => '',
                'class_key' => $this->classKey,
                'data' => $set->toArray(),
                'qtip' => $set->get('description'),
                'menu' => array('items' => $menu),
            );
            $list[] = $setArray;
        }

        return $list;
    }

    /* grab all elements for property set */
    public function getPropertySetNode() {
        $list = array();

        $classes = array(
            'modChunk' => $this->modx->lexicon('chunk', array(), 'en'),
            'modPlugin' => $this->modx->lexicon('plugin', array(), 'en'),
            'modSnippet' => $this->modx->lexicon('snippet', array(), 'en'),
            'modTemplate' => $this->modx->lexicon('template', array(), 'en'),
            'modTemplateVar' => $this->modx->lexicon('tv', array(), 'en'),
        );

        foreach ($classes as $class => $alias) {
            $c = $this->modx->newQuery('modElementPropertySet');
            $c->select("modElementPropertySet.*, {$alias}.*");
            $c->innerJoin($class, $alias, array(
                "modElementPropertySet.element = {$alias}.id",
                'modElementPropertySet.element_class' => $class,
                'modElementPropertySet.property_set' => $this->node[1],
            ));
            $uk = ($class == 'modTemplate') ? 'templatename' : 'name';
            $c->sortby($alias.'.'.$uk, 'ASC');
            $els = $this->modx->getIterator('modElementPropertySet',$c);

            /** @var modElementPropertySet $el */
            foreach ($els as $el) {
                /** @var modElement $elem */
                $elem = $el->getOne('Element');
                if (!$elem->checkPolicy('list')) continue;
                $menu = array();
                if ($this->has['remove']) {
                    $menu[] = array(
                        'text' => $this->modx->lexicon($this->objectType.'_element_remove'),
                        'handler' => 'function(itm,e) {
                            this.removeElement(itm,e);
                        }',
                    );
                }
                $setArray = array(
                    'text' => $el->get('name'),
                    'id' => 'el_'.$el->get('property_set').'_'.$el->get('id').'_'.$class,
                    'leaf' => true,
                    'href' => '',
                    'pk' => $el->get('id'),
                    'qtip' => "<i>{$alias}</i>: <b>{$el->get('name')}</b>".($el->get('description') != '' ? ' - '.$el->get('description') : ''),
                    'cls' => 'icon-'.strtolower($alias),
                    'iconCls' => $this->getNodeIcon($alias),
                    'propertyset' => $el->get('property_set'),
                    'element_class' => $class,
                    'menu' => array('items' => $menu),
                );
                $list[] = $setArray;
            }
            unset($c,$els,$el,$menu);
        }

        return $list;
    }

    public function process() {
        switch ($this->node[0]) {
            case 'root':
                $list = $this->getRootNode();
                break;
            case 'cat':
                $list = isset($this->node[1]) ? $this->getCategoryNode($this->node[1]) : array();
                break;
            case 'ps':
                $list = $this->getPropertySetNode();
                break;
            default:
                $list = array();
                break;
        }

        return $this->toJSON($list);
    }
}

return 'modPropertySetGetNodesProcessor';
