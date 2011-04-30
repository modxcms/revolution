<?php
/**
 * Grabs all elements for propertyset tree
 *
 * @param string $id (optional) Parent ID of object to grab from. Defaults to 0.
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('view_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('element','propertyset');

$scriptProperties['id'] = !isset($scriptProperties['id']) ? 0 : (substr($scriptProperties['id'],0,2) == 'n_' ? substr($scriptProperties['id'],2) : $scriptProperties['id']);
$nodeId = $scriptProperties['id'];

/* split the array */
$node = explode('_',$nodeId);
$list = array();

/* check permissions */
$hasSave = $modx->hasPermission('save_propertyset');
$hasRemove = $modx->hasPermission('delete_propertyset');
$hasNew = $modx->hasPermission('new_propertyset');

switch ($node[0]) {
    case 'root': /* grab all categories and uncategorized property sets */
        $c = $modx->newQuery('modCategory');
        $c->sortby('category','ASC');
        $categories = $modx->getCollection('modCategory',$c);

        foreach ($categories as $category) {
            if (!$category->checkPolicy('list')) continue;
            $propertySets = $category->getMany('PropertySets');
            if (count($propertySets) < 1) continue;

            $categoryArray = array(
                'text' => $category->get('category'),
                'id' => 'cat_'.$category->get('id'),
                'leaf' => false,
                'cls' => 'icon-category',
                'href' => '',
                'class_key' => 'modCategory',
                'menu' => array(),
            );

            $list[] = $categoryArray;
        }
        unset($c,$propertySets,$categories,$category,$categoryArray);


        $c = $modx->newQuery('modPropertySet');
        $c->where(array('category' => 0));
        $c->sortby('name','ASC');
        $sets = $modx->getCollection('modPropertySet',$c);

        foreach ($sets as $set) {
            $menu = array();
            if ($hasSave) {
                $menu[] = array(
                    'text' => $modx->lexicon('propertyset_element_add'),
                    'handler' => 'function(itm,e) {
                        this.addElement(itm,e);
                    }',
                );
                $menu[] = '-';
                $menu[] = array(
                    'text' => $modx->lexicon('propertyset_update'),
                    'handler' => 'function(itm,e) {
                        this.updateSet(itm,e);
                    }',
                );
            }
            if ($hasNew && $hasSave) {
                $menu[] = array(
                    'text' => $modx->lexicon('propertyset_duplicate'),
                    'handler' => 'function(itm,e) {
                        this.duplicateSet(itm,e);
                    }',
                );
            }
            if ($hasRemove) {
                $menu[] = '-';
                $menu[] = array(
                    'text' => $modx->lexicon('propertyset_remove'),
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
                'href' => '',
                'class_key' => 'modPropertySet',
                'data' => $set->toArray(),
                'qtip' => $set->get('description'),
                'menu' => array('items' => $menu),
            );
            $list[] = $setArray;
        }
        break;
    case 'cat': /* grab all property sets for that category */
        $c = $modx->newQuery('modPropertySet');
        $c->where(array(
            'category' => $node[1],
        ));
        $c->sortby('name','ASC');
        $sets = $modx->getCollection('modPropertySet',$c);

        foreach ($sets as $set) {
            $menu = array();
            if ($hasSave) {
                $menu[] = array(
                    'text' => $modx->lexicon('propertyset_element_add'),
                    'handler' => 'function(itm,e) {
                        this.addElement(itm,e);
                    }',
                );
                $menu[] = '-';
                $menu[] = array(
                    'text' => $modx->lexicon('propertyset_update'),
                    'handler' => 'function(itm,e) {
                        this.updateSet(itm,e);
                    }',
                );
            }
            if ($hasNew && $hasSave) {
                $menu[] = array(
                    'text' => $modx->lexicon('propertyset_duplicate'),
                    'handler' => 'function(itm,e) {
                        this.duplicateSet(itm,e);
                    }',
                );
            }
            if ($hasRemove) {
                $menu[] = '-';
                $menu[] = array(
                    'text' => $modx->lexicon('propertyset_remove'),
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
                'href' => '',
                'class_key' => 'modPropertySet',
                'data' => $set->toArray(),
                'qtip' => $set->get('description'),
                'menu' => array('items' => $menu),
            );
            $list[] = $setArray;
        }
        break;
    case 'ps': /* grab all elements for property set */

        $classes = array(
            'modChunk' => $modx->lexicon('chunk'),
            'modPlugin' => $modx->lexicon('plugin'),
            'modSnippet' => $modx->lexicon('snippet'),
            'modTemplate' => $modx->lexicon('template'),
            'modTemplateVar' => $modx->lexicon('tv'),
        );

        foreach ($classes as $class => $alias) {
            $c = $modx->newQuery('modElementPropertySet');
            $c->select('modElementPropertySet.*, '.$alias.'.*');
            $c->innerJoin($class,$alias,array(
                'modElementPropertySet.element = '.$alias.'.id',
                'modElementPropertySet.element_class' => $class,
                'modElementPropertySet.property_set' => $node[1],
            ));
            $uk = ($class == 'modTemplate') ? 'templatename' : 'name';
            $c->sortby($alias.'.'.$uk,'ASC');
            $els = $modx->getCollection('modElementPropertySet',$c);

            foreach ($els as $el) {
                $elem = $el->getOne('Element');
                if (!$elem->checkPolicy('list')) continue;
                $menu = array();
                if ($hasRemove) {
                    $menu[] = array(
                        'text' => $modx->lexicon('propertyset_element_remove'),
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
                    'qtip' => '<i>'.$alias.'</i>: <b>'.$el->get('name').'</b>'.($el->get('description') != '' ? ' - '.$el->get('description') : ''),
                    'cls' => 'icon-'.strtolower($alias),
                    'propertyset' => $el->get('property_set'),
                    'element_class' => $class,
                    'menu' => array('items' => $menu),
                );
                $list[] = $setArray;
            }
            unset($c,$els,$el,$menu);
        }
        break;
}


return $this->toJSON($list);