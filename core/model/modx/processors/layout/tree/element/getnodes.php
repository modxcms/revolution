<?php
/**
 * Grabs all elements for element tree
 *
 * @param string $id (optional) Parent ID of object to grab from. Defaults to 0.
 *
 * @package modx
 * @subpackage processors.layout.tree.element
 */
$modx->lexicon->load('category','element');

$_REQUEST['id'] = !isset($_REQUEST['id']) ? 0 : (substr($_REQUEST['id'],0,2) == 'n_' ? substr($_REQUEST['id'],2) : $_REQUEST['id']);

$grab = $_REQUEST['id'];

$ar_typemap = array(
    'template' => 'modTemplate',
    'tv' => 'modTemplateVar',
    'chunk' => 'modChunk',
    'snippet' => 'modSnippet',
    'plugin' => 'modPlugin',
    'category' => 'modCategory',
);
$actions = $modx->request->getAllActionIDs();
$ar_actionmap = array(
    'template' => $actions['element/template/update'],
    'tv' => $actions['element/tv/update'],
    'chunk' => $actions['element/chunk/update'],
    'snippet' => $actions['element/snippet/update'],
    'plugin' => $actions['element/plugin/update'],
);

/* split the array */
$g = split('_',$grab);
$resources = array();

switch ($g[0]) {
    case 'type': /* if in the element, but not in a category */
        $elementType = ucfirst($g[1]);
        $elementClassKey = $ar_typemap[$g[1]];
        /* 1: type - eg. category_templates */
        $c = $modx->newQuery('modCategory');
        $c->select('modCategory.*,
            COUNT(`'.$elementClassKey.'`.`id`) AS elementCount,
            COUNT(`Children`.`id`) AS childrenCount
        ');
        $c->leftJoin($elementClassKey,$elementClassKey,'`'.$elementClassKey.'`.`category` = `modCategory`.`id`');
        $c->leftJoin('modCategory','Children');
        $c->where(array(
            'modCategory.parent' => 0,
        ));
        $c->sortby('`category`','ASC');
        $c->groupby('`modCategory`.`id`');
        $categories = $modx->getCollection('modCategory',$c);

        foreach ($categories as $category) {
            if ($category->get('elementCount') == 0 && $category->get('childrenCount') <= 0 && $category->get('id') != 0) {
                continue;
            }

            $resources[] = array(
                'text' => $category->get('category') . ' (' . $category->get('id') . ')',
                'id' => 'n_'.$g[1].'_category_'.($category->get('id') != null ? $category->get('id') : 0),
                'pk' => $category->get('id'),
                'category' => $category->get('id'),
                'leaf' => false,
                'cls' => 'icon-category',
                'href' => '',
                'type' => $g[1],
                'menu' => array(
                    'items' => array(
                        array(
                            'text' => '<b>'.$category->get('category').'</b>',
                            'params' => '',
                            'handler' => 'function() { return false; }',
                            'header' => true,
                        )
                        ,'-',
                        array(
                            'text' => $modx->lexicon('add_to_category_this',array('type' => $elementType)),
                            'handler' => 'function(itm,e) {
                                this._createElement(itm,e);
                            }',
                        ),
                        array(
                            'text' => $modx->lexicon('chunk_create_quick'),
                            'handler' => 'function(itm,e) {
                                this.quickCreateChunk(itm,e);
                            }',
                        ),
                        '-',
                        array(
                            'text' => $modx->lexicon('category_create'),
                            'handler' => 'function(itm,e) {
                                this.createCategory(itm,e);
                            }',
                        ),
                        array(
                            'text' => $modx->lexicon('remove_category'),
                            'handler' => 'function(itm,e) {
                                this.removeCategory(itm,e);
                            }',
                        )
                    ),
                ),
            );
            unset($elCount,$childCats);
        }

        $c = $modx->newQuery($elementClassKey);
        $c->where(array(
            'category' => 0,
        ));
        $c->sortby($elementClassKey == 'modTemplate' ? 'templatename' : 'name','ASC');
        $elements = $modx->getCollection($elementClassKey,$c);
        foreach ($elements as $element) {
            $name = $elementClassKey == 'modTemplate' ? $element->get('templatename') : $element->get('name');

            $menu = array();
            $menu[] = array(
                'text' => '<b>'.$name.'</b>',
                'params' => '',
                'handler' => 'function() { return false; }',
                'header' => true,
            );
            $menu[] = '-';
            $menu[] = array(
                'text' => $modx->lexicon('edit').' '.$elementType,
                'handler' => 'function() {
                    location.href = "index.php?'
                        . 'a=' . $actions['element/'.strtolower($elementType).'/update']
                        . '&id=' . $element->get('id')
                     . '";
                }',
            );
            if ($elementClassKey == 'modChunk') {
                $menu[] = array(
                    'text' => $modx->lexicon('chunk_update_quick'),
                    'handler' => 'function(itm,e) {
                        this.quickUpdateChunk(itm,e);
                    }',
                );
            }
            $menu[] = array(
                'text' => $modx->lexicon('duplicate').' '.$elementType,
                'handler' => 'function(itm,e) {
                    this.duplicateElement(itm,e,'.$element->get('id').',"'.strtolower($elementType).'");
                }',
            );
            $menu[] = array(
                'text' => $modx->lexicon('remove').' '.$elementType,
                'handler' => 'function(itm,e) {
                    this.removeElement(itm,e);
                }',
            );
            $menu[] = '-';
            $menu[] = array(
                'text' => sprintf($modx->lexicon('add_to_category_this'),$elementType),
                'handler' => 'function(itm,e) {
                    this._createElement(itm,e);
                }',
            );
            $menu[] = array(
                'text' => $modx->lexicon('new_category'),
                'handler' => 'function(itm,e) {
                    this.createCategory(itm,e);
                }',
            );

            $resources[] = array(
                'text' => $name . ' (' . $element->get('id') . ')',
                'id' => 'n_'.$g[1].'_element_'.$element->get('id').'_0',
                'pk' => $element->get('id'),
                'category' => 0,
                'leaf' => true,
                'cls' => 'icon-'.$g[1],
                'href' => 'index.php?a='.$ar_actionmap[$g[1]].'&id='.$element->get('id'),
                'type' => $g[1],
                'qtip' => $element->get('description'),
                'menu' => array(
                    'items' => $menu,
                ),
            );
        }
        break;
    case 'root': /* if clicking one of the root nodes */
        $elementType = ucfirst($g[0]);
        $resources = array(
            array(
                'text' => $modx->lexicon('templates'),
                'id' => 'n_type_template',
                'leaf' => false,
                'cls' => 'icon-template',
                'href' => '',
                'type' => 'template',
                'menu' => array( 'items' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('template'),
                        'handler' => 'function(itm,e) {
                            this._createElement(itm,e);
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'function(itm,e) {
                            this.createCategory(itm,e);
                        }',
                    )
                )),
            ),
            array(
                'text' => $modx->lexicon('tmplvars'),
                'id' => 'n_type_tv',
                'leaf' => false,
                'cls' => 'icon-tv',
                'href' => '',
                'type' => 'tv',
                'menu' => array( 'items' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('tmplvar'),
                        'handler' => 'function(itm,e) {
                            this._createElement(itm,e);
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'function(itm,e) {
                            this.createCategory(itm,e);
                        }',
                    )
                )),
            ),
            array(
                'text' => $modx->lexicon('chunks'),
                'id' => 'n_type_chunk',
                'leaf' => false,
                'cls' => 'icon-chunk',
                'href' => '',
                'type' => 'chunk',
                'menu' => array( 'items' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('chunk'),
                        'handler' => 'function(itm,e) {
                            this._createElement(itm,e);
                        }',
                    ),
                    array(
                        'text' => $modx->lexicon('chunk_create_quick'),
                        'handler' => 'function(itm,e) {
                            this.quickCreateChunk(itm,e);
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'function(itm,e) {
                            this.createCategory(itm,e);
                        }',
                    )
                )),
            ),
            array(
                'text' => $modx->lexicon('snippets'),
                'id' => 'n_type_snippet',
                'leaf' => false,
                'cls' => 'icon-snippet',
                'href' => '',
                'type' => 'snippet',
                'menu' => array( 'items' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('snippet'),
                        'handler' => 'function(itm,e) {
                            this._createElement(itm,e);
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'function(itm,e) {
                            this.createCategory(itm,e);
                        }',
                    )
                )),
            ),
            array(
                'text' => $modx->lexicon('plugins'),
                'id' => 'n_type_plugin',
                'leaf' => false,
                'cls' => 'icon-plugin',
                'href' => '',
                'type' => 'plugin',
                'menu' => array( 'items' => array(
                    array(
                        'text' => $modx->lexicon('new').' '.$modx->lexicon('plugin'),
                        'handler' => 'function(itm,e) {
                            this._createElement(itm,e);
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('new_category'),
                        'handler' => 'function(itm,e) {
                            this.createCategory(itm,e);
                        }',
                    )
                )),
            ),
            array(
                'text' => $modx->lexicon('categories'),
                'id' => 'n_category',
                'leaf' => 0,
                'cls' => 'icon-category',
                'href' => '',
                'type' => 'category',
                'menu' => array( 'items' => array(
                    array(
                        'text' => $modx->lexicon('category_create'),
                        'handler' => 'function(itm,e) {
                            this.createCategory(itm,e);
                        }',
                    ),
                )),
            ),
        );
        break;
    case 'category':
        if (isset($g[1]) && $g[1] != '' && $g[1] != 0) {
            $c = $modx->newQuery('modCategory');
            $c->where(array(
                'parent' => $g[1],
            ));
            $c->sortby('category','ASC');
        } else {
            /* if trying to grab all root categories */
            $c = $modx->newQuery('modCategory');
            $c->where(array(
                'parent' => 0,
            ));
            $c->sortby('category','ASC');
        }

        $categories = $modx->getCollection('modCategory',$c);
        foreach ($categories as $category) {
            $resources[] = array(
                'text' => $category->get('category') . ' (' . $category->get('id') . ')',
                'id' => 'n_category_'.$category->get('id'),
                'pk' => $category->get('id'),
                'category' => $category->get('id'),
                'leaf' => false,
                'cls' => 'icon-category',
                'href' => '',
                'type' => 'category',
                'menu' => array(
                    'items' => array(
                        array(
                            'text' => $modx->lexicon('category_create'),
                            'handler' => 'function(itm,e) {
                                this.createCategory(itm,e);
                            }',
                        ),
                        array(
                            'text' => $modx->lexicon('category_rename'),
                            'handler' => 'function(itm,e) {
                                this.renameCategory(itm,e);
                            }',
                        ),
                        '-',
                        array(
                            'text' => $modx->lexicon('chunk_create_quick'),
                            'handler' => 'function(itm,e) {
                                this.quickCreateChunk(itm,e);
                            }',
                        ),
                        '-',
                        array(
                            'text' => $modx->lexicon('category_remove'),
                            'handler' => 'function(itm,e) {
                                this.removeCategory(itm,e);
                            }',
                        ),
                    ),
                ),
            );
        }
        break;
    default: /* if clicking a node in a category */
        /* 0: type,  1: element/category  2: elID  3: catID */
        $categoryId = isset($g[3]) ? $g[3] : ($g[1] == 'category' ? $g[2] : 0);
        $elementType = ucfirst($g[0]);
        $elementClassKey = $ar_typemap[$g[0]];

        /* first handle subcategories */
        $c = $modx->newQuery('modCategory');
        $c->select('modCategory.*,
            COUNT(`'.$elementClassKey.'`.`id`) AS elementCount
        ');
        $c->leftJoin($elementClassKey,$elementClassKey,'`'.$elementClassKey.'`.`category` = `modCategory`.`id`');
        $c->where(array(
            'parent' => $categoryId,
        ));
        $c->groupby('`modCategory`.`id`');
        $c->sortby('`category`','ASC');

        $categories = $modx->getCollection('modCategory',$c);
        foreach ($categories as $category) {
            if ($category->get('elementCount') <= 0) continue;

            $resources[] = array(
                'text' => $category->get('category') . ' (' . $category->get('id') . ')',
                'id' => 'n_'.$g[0].'_category_'.($category->get('id') != null ? $category->get('id') : 0),
                'pk' => $category->get('id'),
                'category' => $category->get('id'),
                'leaf' => false,
                'cls' => 'icon-category',
                'href' => '',
                'type' => $g[0],
                'menu' => array(
                    'items' => array(
                        array(
                            'text' => '<b>'.$category->get('category').'</b>',
                            'params' => '',
                            'handler' => 'function() { return false; }',
                            'header' => true,
                        )
                        ,'-',
                        array(
                            'text' => sprintf($modx->lexicon('add_to_category_this'),$elementType),
                            'handler' => 'function(itm,e) {
                                this._createElement(itm,e);
                            }',
                        ),
                        array(
                            'text' => $modx->lexicon('chunk_create_quick'),
                            'handler' => 'function(itm,e) {
                                this.quickCreateChunk(itm,e);
                            }',
                        ),
                        '-',
                        array(
                            'text' => $modx->lexicon('category_create'),
                            'handler' => 'function(itm,e) {
                                this.createCategory(itm,e);
                            }',
                        ),
                        array(
                            'text' => $modx->lexicon('remove_category'),
                            'handler' => 'function(itm,e) {
                                this.removeCategory(itm,e);
                            }',
                        )
                    ),
                ),
            );
        }

        /* all elements in category */

        $c = $modx->newQuery($elementClassKey);
        $c->where(array(
            'category' => $categoryId
        ));
        $c->sortby($g[0] == 'template' ? 'templatename' : 'name','ASC');

        $elements = $modx->getCollection($elementClassKey,$c);
        $elementType = ucfirst($g[0]);
        foreach ($elements as $element) {
            $name = $g[0] == 'template' ? $element->get('templatename') : $element->get('name');

            $menu = array(
                array(
                    'text' => '<b>'.$name.'</b>',
                    'params' => '',
                    'handler' => 'function() { return false; }',
                    'header' => true,
                ),'-',
                array(
                    'text' => $modx->lexicon('edit').' '.$elementType,
                    'handler' => 'function() {
                        location.href = "index.php?'
                            . 'a=' . $actions['element/'.strtolower($elementType).'/update']
                            . '&id=' . $element->get('id')
                         . '";
                    }',
                ),
            );
            if ($elementType == 'Chunk') {
                $menu[] = array(
                    'text' => $modx->lexicon('chunk_update_quick'),
                    'handler' => 'function(itm,e) {
                        this.quickUpdateChunk(itm,e);
                    }',
                );
            }
            $menu[] = array(
                'text' => $modx->lexicon('duplicate').' '.$elementType,
                'handler' => 'function(itm,e) {
                    this.duplicateElement(itm,e,'.$element->get('id').',"'.strtolower($elementType).'");
                }',
            );
            $menu[] = '-';
            $menu[] = array(
                'text' => $modx->lexicon('remove').' '.$elementType,
                'handler' => 'function(itm,e) {
                    this.removeElement(itm,e);
                }',
            );

            $resources[] = array(
                'text' => $name . ' (' . $element->get('id') . ')',
                /* setup g[], 1: 'element', 2: type of el, 3: el ID, 4: cat ID */
                'id' => 'n_'.$g[0].'_element_'.$element->get('id').'_'.$element->get('category'),
                'pk' => $element->get('id'),
                'category' => $cat_id,
                'leaf' => 1,
                'cls' => 'icon-'.$g[0],
                'href' => 'index.php?a='.$ar_actionmap[$g[0]].'&id='.$element->get('id'),
                'type' => $g[0],
                'menu' => array(
                    'items' => $menu
                ),
            );
        }
        break;
}

return $this->toJSON($resources);