<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

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
    protected $output = '';
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
        $placeholders = array(
            'username' => $username,
            'userImage' => $this->getUserImage(),
        );

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
        $output = '<i class="icon icon-user icon-large"></i>&nbsp;';
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
        foreach ($menus as $menu) {
            $this->childrenCt = 0;

            if (!$this->hasPermission($menu['permissions'])) {
                continue;
            }

            $description = '';
            if ($this->showDescriptions && !empty($menu['description'])) {
                $description = '<span class="description">'.$menu['description'].'</span>'."\n";
            }

            $label = $menu['text'];
            $title = ' title="' . $menu['description'] .'"';
            $icon = false;
            if (!empty($menu['icon'])) {
                $icon = true;
                // Use the icon as label
                $label = $menu['icon'];
                // Reset the description (which is set as text in $title)
                $description = '';
            }

            $top = (!empty($menu['children'])) ? ' class="top"' : '';
            $menuTpl = '<li id="limenu-'.$menu['id'].'"'.$top.'>'."\n";

            if (!empty($menu['action'])) {
                if ($menu['namespace'] != 'core') {
                    // Handle the namespace
                    $menu['action'] .= '&namespace='.$menu['namespace'];
                }
                if (!$icon) {
                    // No icon, no title property
                    $title = '';
                }
                $onclick = (!empty($menu['handler'])) ? ' onclick="'.str_replace('"','\'',$menu['handler']).'"' : '';
                $menuTpl .= '<a href="?a='.$menu['action'].$menu['params'].'"'.( $top ? ' class="top-link"': '' ).$onclick.$title.'>'.$label.$description.'</a>'."\n";
            } elseif (!empty($menu['handler'])) {
                $menuTpl .= '<a href="javascript:;" onclick="'.str_replace('"','\'',$menu['handler']).'">'.$label.'</a>'."\n";
            } else {
                $menuTpl .= '<a href="javascript:;">'.$label.'</a>'."\n";
            }

            if (!empty($menu['children'])) {
                $menuTpl .= '<ul class="modx-subnav">'."\n";
                $this->processSubMenus($menuTpl, $menu['children']);
                $menuTpl .= '</ul>'."\n";
            }
            $menuTpl .= '</li>'."\n";

            /* if has no permissable children, and is not clickable, hide top menu item */
            if (!empty($this->childrenCt) || !empty($menu['action']) || !empty($menu['handler'])) {
                $this->output .= $menuTpl;
            }
            $this->order++;
        }

        //$this->cleanEmptySubMenus();
        $this->controller->setPlaceholder($placeholder, $this->output);
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

        $menus = $this->modx->cacheManager->get($key, array(
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
        ));

        if ($menus == null || !is_array($menus)) {
            /** @var modMenu $menu */
            $menu = $this->modx->newObject('modMenu');
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
        return "menus/{$name}/" . $this->modx->getOption(
            'manager_language',
            null,
            $this->modx->getOption('cultureKey', null, 'en')
        );
    }

    /**
     * Reset menu HTML output & indexes counters
     *
     * @return void
     */
    protected function resetCounters()
    {
        $this->output = '';
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
        $permissions = array();
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
    public function processSubMenus(&$output, array $menus = array())
    {
        //$output .= '<ul class="modx-subnav">'."\n";

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
            $smTpl .= '<a'.$attributes.'>'.$menu['text'].$description.'</a>'."\n";

            if (!empty($menu['children'])) {
                $smTpl .= '<ul class="modx-subsubnav">'."\n";
                $this->processSubMenus($smTpl, $menu['children']);
                $smTpl .= '</ul>'."\n";
            }
            $smTpl .= '</li>';
            $output .= $smTpl;
            $this->childrenCt++;
        }

        //$output .= '</ul>'."\n";
    }

    /**
     * Clean "orphan" sub menus
     *
     * @return void
     */
    public function cleanEmptySubMenus()
    {
        $emptySub = '<ul class="modx-subsubnav">'."\n".'</ul>'."\n";

        $this->output = str_replace($emptySub, '', $this->output);
    }
}

// Set Smarty placeholder to display search bar, if appropriate
$this->setPlaceholder('_search', $modx->hasPermission('search'));

$menu = new TopMenu($this);
$menu->render();
