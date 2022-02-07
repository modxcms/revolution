<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modManagerController;
use MODX\Revolution\modMenu;
use xPDO\xPDO;
use xPDO\Cache\xPDOCacheManager;

/**
 * Loads the main structure
 *
 * @see modManagerController::getHeader
 * @see modManagerController::loadController
 *
 * @var modX $modx
 * @var modManagerController $this
 *
 * @package modx
 * @subpackage manager.controllers
 */

class TopMenu
{
    /**
     * @var modManagerController
     */
    public $controller;
    /**
     * @var modX
     */
    public $modx;
    /**
     * The current menu HTML output
     *
     * @var string
     */
    protected $menus = '';
    protected $submenus = '';
    /**
     * Whether or not to display menus description
     *
     * @var bool
     */
    protected $showDescriptions = true;
    /**
     * Current menu index
     *
     * @var int
     */
    protected $order = 0;
    /**
     * Current children menu index
     *
     * @var int
     */
    protected $childrenCt = 0;

    public function __construct(modManagerController &$controller)
    {
        $this->controller =& $controller;
        $this->modx =& $controller->modx;
        $this->showDescriptions = (boolean) $this->modx->getOption('topmenu_show_descriptions', null, true);
    }

    /**
     * Build the top menu
     *
     * @return void
     */
    public function render()
    {
        // First assign most variables so they could be used within menus
        $this->setPlaceholders();

        // Then process menu "containers"
        $mainNav = $this->modx->smarty->getTemplateVars('navb');
        if (empty($mainNav)) {
            $this->buildMenu(
                $this->modx->getOption('main_nav_parent', null, 'topnav', true),
                'navb'
            );
        }
        $userNav = $this->modx->smarty->getTemplateVars('userNav');
        if (empty($userNav)) {
            $this->buildMenu(
                $this->modx->getOption('user_nav_parent', null, 'usernav', true),
                'userNav'
            );
        }
    }

    /**
     * Set a bunch of placeholders to be used within Smarty templates
     *
     * @return void
     */
    public function setPlaceholders()
    {
        $username = '';
        if ($this->modx->getOption('manager_use_fullname') == true) {
            $userProfile = $this->modx->user->getOne('Profile');
            $username = $userProfile->get('fullname');
        }

        if (empty($username)) {
            $username = $this->modx->getLoginUserName();
        }
        $placeholders = [
            'username' => $username,
            'userImage' => $this->getUserImage(),
        ];

        $this->controller->setPlaceholders($placeholders);
    }

    /**
     * Retrieve/compute the user picture profile
     *
     * @return string The HTML output
     */
    public function getUserImage()
    {
        // Default to FontAwesome
        $output = '<i class="icon icon-user icon-large"></i>';
        $img = $this->modx->user->getPhoto(128, 128);

        if (!empty($img)) {
            $output = '<img src="' . $img . '" />';
        }

        return $output;
    }

    /**
     * Build the requested menu "container" and set it as a placeholder
     *
     * @param string $name The container name (topnav, usernav)
     * @param string $placeholder The placeholder to display the built menu to
     *
     * @return void
     */
    public function buildMenu($name, $placeholder)
    {
        if (!$placeholder) {
            $placeholder = $name;
        }

        // Grab the menus to process
        $menus = $this->getCache($name);

        // Iterate
        foreach ($menus as $idx => $menu) {
            $this->childrenCt = 0;

            if (!$this->hasPermission($menu['permissions'])) {
                continue;
            }

            $label = '';
            $description = '';
            $title = ' title="' . $menu['description'] .'"';

            if (!empty($menu['icon'])) {
                $label = $menu['icon'];
            } else {
                $label = '<i class="icon-link icon icon-large"></i>'."\n";
            }
            $label .= '<span class="label">'.$menu['text'].'</span>'."\n";

            if ($this->showDescriptions && !empty($menu['description'])) {
                $description = '<span class="description">'.$menu['description'].'</span>'."\n";
            }

            $top = !empty($menu['children']) ? ' top' : '';
            $position = $idx <= 2 && $placeholder == 'navb' ? 'down' : 'up';

            $menuTpl = '<li id="limenu-'.$menu['id'].'"class="menu-'.$position.$top.'">'."\n";
            if (!empty($menu['action'])) {
                if ($menu['namespace'] != 'core') {
                    // Handle the namespace
                    $menu['action'] .= '&namespace='.$menu['namespace'];
                }
                $onclick = (!empty($menu['handler'])) ? ' onclick="'.str_replace('"','\'',$menu['handler']).'"' : '';
                $menuTpl .= '<a href="?a='.$menu['action'].$menu['params'].'"'.( $top ? ' class="top-link"': '' ).$onclick.$title.'>'.$label.$description.'</a>'."\n";
            } elseif (!empty($menu['handler'])) {
                $menuTpl .= '<a href="javascript:;" onclick="'.str_replace('"','\'',$menu['handler']).'"'.$title.'>'.$label.$description.'</a>'."\n";
            } else {
                $menuTpl .= '<a href="javascript:;"'.$title.'>'.$label.$description.'</a>'."\n";
            }
            $menuTpl .= '</li>'."\n";

            if (!empty($menu['children'])) {
                $this->submenus .= '<ul id="limenu-' . $menu['id'] . '-submenu" class="modx-subnav modx-subnav-' . $menu['parent'] . '">';
                $this->processSubMenus($this->submenus, $menu['children']);
                $this->submenus .= '<div class="modx-subnav-arrow"></div></ul>';
            }

            /* if has no permissable children, and is not clickable, hide top menu item */
            if (!empty($this->childrenCt) || !empty($menu['action']) || !empty($menu['handler'])) {
                $this->menus .= $menuTpl;
            }
            $this->order++;
        }

        $this->controller->setPlaceholder($placeholder, $this->menus);
        $this->controller->setPlaceholder($placeholder . '_submenus', $this->submenus);
        $this->resetCounters();
    }

    /**
     * Retrieve the menus for the given "container"
     *
     * @param string $name
     *
     * @return array
     */
    protected function getCache($name)
    {
        $key = $this->getCacheKey($name);

        $menus = $this->modx->cacheManager->get($key, [
            xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_menu_key', null, 'menu'),
            xPDO::OPT_CACHE_HANDLER => $this->modx->getOption(
                'cache_menu_handler',
                null,
                $this->modx->getOption(xPDO::OPT_CACHE_HANDLER)
            ),
            xPDO::OPT_CACHE_FORMAT => (integer) $this->modx->getOption(
                'cache_menu_format',
                null,
                $this->modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)
            ),
        ]);

        if ($menus == null || !is_array($menus)) {
            /** @var modMenu $menu */
            $menu = $this->modx->newObject(modMenu::class);
            $menus = $menu->rebuildCache($name);
            unset($menu);
        }

        return $menus;
    }

    /**
     * Compute the cache key for the given menu "container"
     *
     * @param string $name
     *
     * @return string
     */
    protected function getCacheKey($name)
    {
        $ml = $this->modx->getOption('manager_language', $_SESSION, $this->modx->getOption('cultureKey', null, 'en'));

        return "menus/{$name}/" . $ml;
    }

    /**
     * Reset menu HTML output & indexes counters
     *
     * @return void
     */
    protected function resetCounters()
    {
        $this->menus = '';
        $this->submenus = '';
        $this->order = 0;
        $this->childrenCt = 0;
    }

    /**
     * Check if the current user is allowed to view the menu record
     *
     * @param string $perms
     *
     * @return bool
     */
    public function hasPermission($perms)
    {
        if (empty($perms)) {
            return true;
        }
        $permissions = [];
        $exploded = explode(',', $perms);
        foreach ($exploded as $permission) {
            $permissions[trim($permission)] = true;
        }

        return $this->modx->hasPermission($permissions);
    }

    /**
     * Process the given sub menus
     *
     * @param string $output The existing menu HTML "output"
     * @param array $menus The sub menus to process
     *
     * @return void
     */
    public function processSubMenus(&$output, array $menus = [])
    {
        foreach ($menus as $menu) {
            if (!$this->hasPermission($menu['permissions'])) {
                continue;
            }

            $sub = (!empty($menu['children'])) ? ' class="sub"' : '';
            $smTpl = '<li id="'.$menu['id'].'"'.$sub.'>'."\n";

            $description = '';
            if ($this->showDescriptions && !empty($menu['description'])) {
                $description = '<span class="description">'.$menu['description'].'</span>'."\n";
            }

            $attributes = '';
            if (!empty($menu['action'])) {
                if ($menu['namespace'] != 'core') {
                    $menu['action'] .= '&namespace='.$menu['namespace'];
                }
                $attributes = ' href="?a='.$menu['action'].$menu['params'].'"';
            }
            if (!empty($menu['handler'])) {
                $attributes .= ' onclick="{literal} '.str_replace('"','\'',$menu['handler']).'{/literal} "';
            }
            $menu['icon'] = $menu['icon'] ?? '';
            $smTpl .= '<a'.$attributes.'>'.$menu['text'].$menu['icon'].$description.'</a>'."\n";

            if (!empty($menu['children'])) {
                $smTpl .= '<ul class="modx-subsubnav">'."\n";
                $this->processSubMenus($smTpl, $menu['children']);
                $smTpl .= '</ul><div class="modx-subsubnav-arrow"></div>' . "\n";
            }
            $smTpl .= '</li>';
            $output .= $smTpl;
            $this->childrenCt++;
        }
    }
}

// Set Smarty placeholder to display search bar, if appropriate
$this->setPlaceholder('_search', (int)$modx->hasPermission('search'));
$this->setPlaceholder('_version', $modx->getVersionData());

$menu = new TopMenu($this);
$menu->render();
