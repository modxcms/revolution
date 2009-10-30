<?php
/**
 * Grabs all elements for propertyset tree
 *
 * @param string $id (optional) Parent ID of object to grab from. Defaults to 0.
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('element','propertyset');

$_REQUEST['id'] = !isset($_REQUEST['id']) ? 0 : (substr($_REQUEST['id'],0,2) == 'n_' ? substr($_REQUEST['id'],2) : $_REQUEST['id']);
$nodeId = $_REQUEST['id'];

/* split the array */
$node = explode('_',$nodeId);
$list = array();

switch ($node[0]) {
    case 'root': /* grab all categories and uncategorized property sets */
        $c = $modx->newQuery('modCategory');
        $c->sortby('category','ASC');
        $categories = $modx->getCollection('modCategory',$c);

        foreach ($categories as $category) {
            $ps = $category->getMany('PropertySets');
            if (count($ps) < 1) continue;

            $ca = array(
                'text' => $category->get('category'),
                'id' => 'cat_'.$category->get('id'),
                'leaf' => false,
                'cls' => 'icon-category',
                'href' => '',
                'class_key' => 'modCategory',
                'menu' => array(

                ),
            );

            $list[] = $ca;
        }


        $c = $modx->newQuery('modPropertySet');
        $c->where(array('category' => 0));
        $c->sortby('name','ASC');
        $sets = $modx->getCollection('modPropertySet',$c);

        foreach ($sets as $set) {
            $sa = array(
                'text' => $set->get('name'),
                'id' => 'ps_'.$set->get('id'),
                'leaf' => false,
                'cls' => 'icon-propertyset',
                'href' => '',
                'class_key' => 'modPropertySet',
                'data' => $set->toArray(),
                'qtip' => $set->get('description'),
                'menu' => array(
                    'items' => array(
                        array(
                            'text' => $modx->lexicon('propertyset_element_add'),
                            'handler' => 'function(itm,e) {
                                this.addElement(itm,e);
                            }',
                        ),
                        '-',
                        array(
                            'text' => $modx->lexicon('propertyset_update'),
                            'handler' => 'function(itm,e) {
                                this.updateSet(itm,e);
                            }',
                        ),
                        array(
                            'text' => $modx->lexicon('propertyset_duplicate'),
                            'handler' => 'function(itm,e) {
                                this.duplicateSet(itm,e);
                            }',
                        ),
                        '-',
                        array(
                            'text' => $modx->lexicon('propertyset_remove'),
                            'handler' => 'function(itm,e) {
                                this.removeSet(itm,e);
                            }',
                        ),
                    ),
                ),
            );
            $list[] = $sa;
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
            $sa = array(
                'text' => $set->get('name'),
                'id' => 'ps_'.$set->get('id'),
                'leaf' => false,
                'cls' => 'icon-propertyset',
                'href' => '',
                'class_key' => 'modPropertySet',
                'data' => $set->toArray(),
                'qtip' => $set->get('description'),
                'menu' => array(
                    'items' => array(
                        array(
                            'text' => $modx->lexicon('propertyset_element_add'),
                            'handler' => 'function(itm,e) {
                                this.addElement(itm,e);
                            }',
                        ),
                        '-',
                        array(
                            'text' => $modx->lexicon('propertyset_update'),
                            'handler' => 'function(itm,e) {
                                this.updateSet(itm,e);
                            }',
                        ),
                        array(
                            'text' => $modx->lexicon('propertyset_duplicate'),
                            'handler' => 'function(itm,e) {
                                this.duplicateSet(itm,e);
                            }',
                        ),
                        '-',
                        array(
                            'text' => $modx->lexicon('propertyset_remove'),
                            'handler' => 'function(itm,e) {
                                this.removeSet(itm,e);
                            }',
                        ),
                    ),
                ),
            );
            $list[] = $sa;
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
                'modElementPropertySet.element_class = "'.$class.'"',
                'modElementPropertySet.property_set' => $node[1],
            ));
            $uk = ($class == 'modTemplate') ? 'templatename' : 'name';
            $c->sortby('`'.$alias.'`.`'.$uk.'`','ASC');
            $els = $modx->getCollection('modElementPropertySet',$c);

            foreach ($els as $el) {
                $sa = array(
                    'text' => $el->get('name'),
                    'id' => 'el_'.$el->get('property_set').'_'.$el->get('id'),
                    'leaf' => true,
                    'href' => '',
                    'pk' => $el->get('id'),
                    'qtip' => '<i>'.$alias.'</i>: <b>'.$el->get('name').'</b>'.($el->get('description') != '' ? ' - '.$el->get('description') : ''),
                    'cls' => 'icon-'.strtolower($alias),
                    'propertyset' => $el->get('property_set'),
                    'element_class' => $class,
                    'menu' => array(
                        'items' => array(
                            array(
                                'text' => $modx->lexicon('propertyset_element_remove'),
                                'handler' => 'function(itm,e) {
                                    this.removeElement(itm,e);
                                }',
                            )
                        ),
                    ),
                );
                $list[] = $sa;
            }
            unset($c,$els,$el);
        }
        break;
}


return $this->toJSON($list);