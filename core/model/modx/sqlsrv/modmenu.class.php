<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once (dirname(__DIR__) . '/modmenu.class.php');
/**
 * @package modx
 * @subpackage sqlsrv
 */
class modMenu_sqlsrv extends modMenu {
    /**
     * Gets all submenus from a start menu.
     *
     * @param string $start The top menu to load from.
     * @return array An array of modMenu objects, in tree form.
     */
    public function getSubMenus($start = '') {
        if (!$this->xpdo->lexicon) {
            $this->xpdo->getService('lexicon','modLexicon');
        }
        $this->xpdo->lexicon->load('menu','topmenu');

        $c = $this->xpdo->newQuery('modMenu');
        $c->select($this->xpdo->getSelectColumns('modMenu', 'modMenu'));

        /* 2.2 and earlier support */
        $c->leftJoin('modAction','Action', 'CAST(Action.id AS nvarchar) = modMenu.action');
        $c->select(array(
            'action_controller' => 'Action.controller',
            'action_namespace' => 'Action.namespace',
        ));

        $c->where(array(
            'modMenu.parent' => $start,
        ));
        $c->sortby($this->xpdo->getSelectColumns('modMenu','modMenu','',array('menuindex')),'ASC');
        $menus = $this->xpdo->getCollection('modMenu',$c);
        if (count($menus) < 1) return array();

        $list = array();
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
                $this->xpdo->lexicon->load($namespace.':default');
            }

            /* if 3rd party menu item, load proper text */
            if (!empty($action)) {
                if (!empty($namespace) && $namespace != 'core') {
                    $ma['text'] = $menu->get('text') === 'user'
                        ? $this->xpdo->lexicon($menu->get('text'), array('username' => $this->xpdo->getLoginUserName()))
                        : $this->xpdo->lexicon($menu->get('text'));
                } else {
                    $ma['text'] = $menu->get('text') === 'user'
                        ? $this->xpdo->lexicon($menu->get('text'), array('username' => $this->xpdo->getLoginUserName()))
                        : $this->xpdo->lexicon($menu->get('text'));
                }
            } else {
                $ma['text'] = $menu->get('text') === 'user'
                    ? $this->xpdo->lexicon($menu->get('text'), array('username' => $this->xpdo->getLoginUserName()))
                    : $this->xpdo->lexicon($menu->get('text'));
            }

            $desc = $menu->get('description');
            $ma['description'] = !empty($desc) ? $this->xpdo->lexicon($desc) : '';
            $ma['children'] = $menu->get('text') != '' ? $this->getSubMenus($menu->get('text')) : array();

            if ($menu->get('controller')) {
                $ma['controller'] = $menu->get('controller');
            } else {
                $ma['controller'] = '';
            }
            $list[] = $ma;
        }
        unset($menu,$desc,$namespace,$ma);
        return $list;
    }
}
