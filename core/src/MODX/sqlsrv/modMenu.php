<?php

namespace MODX\sqlsrv;


class modMenu extends \MODX\modMenu
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'menus',
        'extends' => 'MODX\\modAccessibleObject',
        'fields' =>
            [
                'text' => '',
                'parent' => '',
                'action' => '',
                'description' => '',
                'icon' => '',
                'menuindex' => 0,
                'params' => '',
                'handler' => '',
                'permissions' => '',
                'namespace' => 'core',
            ],
        'fieldMeta' =>
            [
                'text' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'pk',
                    ],
                'parent' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'action' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'description' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'icon' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'menuindex' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'params' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'handler' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'permissions' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'namespace' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'core',
                        'index' => 'index',
                    ],
            ],
        'indexes' =>
            [
                'PRIMARY' =>
                    [
                        'alias' => 'PRIMARY',
                        'primary' => true,
                        'unique' => true,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'text' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'parent' =>
                    [
                        'alias' => 'parent',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'parent' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'action' =>
                    [
                        'alias' => 'action',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'action' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'namespace' =>
                    [
                        'alias' => 'namespace',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'namespace' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'composites' =>
            [
                'Acls' =>
                    [
                        'class' => 'MODX\\modAccessMenu',
                        'local' => 'text',
                        'foreign' => 'target',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
            ],
        'aggregates' =>
            [
                'Action' =>
                    [
                        'class' => 'MODX\\modAction',
                        'local' => 'action',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
                'Parent' =>
                    [
                        'class' => 'MODX\\modMenu',
                        'local' => 'parent',
                        'foreign' => 'text',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
                'Children' =>
                    [
                        'class' => 'MODX\\modMenu',
                        'local' => 'text',
                        'foreign' => 'parent',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
            ],
    ];


    /**
     * Gets all submenus from a start menu.
     *
     * @param string $start The top menu to load from.
     *
     * @return array An array of modMenu objects, in tree form.
     */
    public function getSubMenus($start = '')
    {
        if (!$this->xpdo->lexicon) {
            $this->xpdo->getService('lexicon', 'modLexicon');
        }
        $this->xpdo->lexicon->load('menu', 'topmenu');

        $c = $this->xpdo->newQuery('modMenu');
        $c->select($this->xpdo->getSelectColumns('modMenu', 'modMenu'));

        /* 2.2 and earlier support */
        $c->leftJoin('modAction', 'Action', 'CAST(Action.id AS nvarchar) = modMenu.action');
        $c->select([
            'action_controller' => 'Action.controller',
            'action_namespace' => 'Action.namespace',
        ]);

        $c->where([
            'modMenu.parent' => $start,
        ]);
        $c->sortby($this->xpdo->getSelectColumns('modMenu', 'modMenu', '', ['menuindex']), 'ASC');
        $menus = $this->xpdo->getCollection('modMenu', $c);
        if (count($menus) < 1) return [];

        $list = [];
        /** @var modMenu $menu */
        foreach ($menus as $menu) {
            $ma = $menu->toArray();
            $ma['id'] = $menu->get('text');
            $action = $menu->get('action');
            $namespace = $menu->get('namespace');

            // allow 2.2 and earlier actions
            $deprecatedNamespace = $menu->get('action_namespace');
            if (!empty($deprecatedNamespace)) {
                $namespace = $deprecatedNamespace;
            }
            if ($namespace != 'core') {
                $this->xpdo->lexicon->load($namespace . ':default');
            }

            /* if 3rd party menu item, load proper text */
            if (!empty($action)) {
                if (!empty($namespace) && $namespace != 'core') {
                    $ma['text'] = $menu->get('text') === 'user'
                        ? $this->xpdo->lexicon($menu->get('text'), ['username' => $this->xpdo->getLoginUserName()])
                        : $this->xpdo->lexicon($menu->get('text'));
                } else {
                    $ma['text'] = $menu->get('text') === 'user'
                        ? $this->xpdo->lexicon($menu->get('text'), ['username' => $this->xpdo->getLoginUserName()])
                        : $this->xpdo->lexicon($menu->get('text'));
                }
            } else {
                $ma['text'] = $menu->get('text') === 'user'
                    ? $this->xpdo->lexicon($menu->get('text'), ['username' => $this->xpdo->getLoginUserName()])
                    : $this->xpdo->lexicon($menu->get('text'));
            }

            $desc = $menu->get('description');
            $ma['description'] = !empty($desc) ? $this->xpdo->lexicon($desc) : '';
            $ma['children'] = $menu->get('text') != '' ? $this->getSubMenus($menu->get('text')) : [];

            if ($menu->get('controller')) {
                $ma['controller'] = $menu->get('controller');
            } else {
                $ma['controller'] = '';
            }
            $list[] = $ma;
        }
        unset($menu, $desc, $namespace, $ma);

        return $list;
    }
}
