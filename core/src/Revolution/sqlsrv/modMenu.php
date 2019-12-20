<?php
namespace MODX\Revolution\sqlsrv;

use MODX\Revolution\modLexicon;

class modMenu extends \MODX\Revolution\modMenu
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'menus',
        'extends' => 'MODX\\Revolution\\modAccessibleObject',
        'fields' => 
        array (
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
        ),
        'fieldMeta' => 
        array (
            'text' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'pk',
            ),
            'parent' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'index',
            ),
            'action' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'index',
            ),
            'description' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'icon' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'menuindex' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'params' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'handler' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'permissions' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'namespace' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => 'core',
                'index' => 'index',
            ),
        ),
        'indexes' => 
        array (
            'PRIMARY' => 
            array (
                'alias' => 'PRIMARY',
                'primary' => true,
                'unique' => true,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'text' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'parent' => 
            array (
                'alias' => 'parent',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'parent' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'action' => 
            array (
                'alias' => 'action',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'action' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'namespace' => 
            array (
                'alias' => 'namespace',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'namespace' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
        'composites' => 
        array (
            'Acls' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessMenu',
                'local' => 'text',
                'foreign' => 'target',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
        ),
        'aggregates' => 
        array (
            'Parent' => 
            array (
                'class' => 'MODX\\Revolution\\modMenu',
                'local' => 'parent',
                'foreign' => 'text',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
            'Children' => 
            array (
                'class' => 'MODX\\Revolution\\modMenu',
                'local' => 'text',
                'foreign' => 'parent',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
        ),
    );

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
            $this->xpdo->getService('lexicon', modLexicon::class);
        }
        $this->xpdo->lexicon->load('menu', 'topmenu');

        $c = $this->xpdo->newQuery(\MODX\Revolution\modMenu::class);
        $c->select($this->xpdo->getSelectColumns(\MODX\Revolution\modMenu::class, 'modMenu'));

        $c->where([
            'modMenu.parent' => $start,
        ]);
        $c->sortby($this->xpdo->getSelectColumns(\MODX\Revolution\modMenu::class, 'modMenu', '', ['menuindex']), 'ASC');
        $menus = $this->xpdo->getCollection(\MODX\Revolution\modMenu::class, $c);
        if (count($menus) < 1) {
            return [];
        }

        $list = [];
        /** @var modMenu $menu */
        foreach ($menus as $menu) {
            $ma = $menu->toArray();
            $ma['id'] = $menu->get('text');
            $action = $menu->get('action');
            $namespace = $menu->get('namespace');

            if ($namespace !== 'core') {
                $this->xpdo->lexicon->load($namespace . ':default');
            }

            /* if 3rd party menu item, load proper text */
            if (!empty($action)) {
                if (!empty($namespace) && $namespace !== 'core') {
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
