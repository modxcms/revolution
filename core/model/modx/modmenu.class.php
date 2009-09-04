<?php
/**
 * Represents a menu item at the top of the MODx manager.
 *
 * @package modx
 */
class modMenu extends modAccessibleObject {
    function modMenu(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    /**
     * Overrides xPDOObject::save to cache the menus.
     *
     * {@inheritdoc}
     */
    function save($cacheFlag = null) {
        $saved = parent::save($cacheFlag);
        if ($saved && empty($this->xpdo->config[XPDO_OPT_SETUP])) {
            $this->rebuildCache();
        }
        return $saved;
    }

    /**
     * Overrides xPDOObject::remove to cache the menus.
     *
     * {@inheritdoc}
     */
    function remove() {
        $removed = parent::remove();
        if ($removed && empty($this->xpdo->config[XPDO_OPT_SETUP])) {
            $this->rebuildCache();
        }
        return $removed;
    }

    /**
     * Rebuilds the menu map cache.
     *
     * @access public
     * @param integer $start The start menu to build from recursively.
     * @return array An array of modMenu objects, in tree form.
     */
    function rebuildCache($start = '') {
        $menus = $this->getSubMenus($start);

        if ($this->xpdo->cacheManager->set('mgr/menus',$menus) == false) {
            $this->xpdo->log(MODX_LOG_LEVEL_ERROR,'The menu cache could not be written.');
        }

        return $menus;
    }

    /**
     * Gets all submenus from a start menu.
     *
     * @access public
     * @param integer $start The top menu to load from.
     * @return array An array of modMenu objects, in tree form.
     */
    function getSubMenus($start = '') {
        if (!$this->xpdo->lexicon) {
            $this->xpdo->getService('lexicon','modLexicon');
        }
        $this->xpdo->lexicon->load('menu','topmenu');

        $c = $this->xpdo->newQuery('modMenu');
        $c->select('modMenu.*,Action.controller AS controller,Action.namespace');
        $c->leftJoin('modAction','Action');
        $c->where(array(
            'modMenu.parent' => $start,
        ));
        $c->sortby('`modMenu`.`menuindex`','ASC');
        $menus = $this->xpdo->getCollection('modMenu',$c);
        if (count($menus) < 1) return array();

        $list = array();
        foreach ($menus as $menu) {
            $ma = $menu->toArray();

            /* if 3rd party menu item, load proper text */
            if ($menu->get('action')) {
                $namespace = $menu->get('namespace');
                if ($namespace != null && $namespace != 'core') {
                    $this->xpdo->lexicon->load($namespace.':default');
                    $ma['text'] = $this->xpdo->lexicon($menu->get('text'));
                } else {
                    $ma['text'] = $this->xpdo->lexicon($menu->get('text'));
                }
            } else {
                $ma['text'] = $this->xpdo->lexicon($menu->get('text'));
            }

            $desc = $menu->get('description');
            if ($desc != '' && $desc != null) {
                $ma['description'] = $this->xpdo->lexicon($desc);
            } else {
                $ma['description'] = '';
            }
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