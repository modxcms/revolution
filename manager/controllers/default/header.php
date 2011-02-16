<?php
/**
 * Loads the main structure
 *
 * @package modx
 * @subpackage manager
 */
/* get top navbar */
$menus = $modx->cacheManager->get('mgr/menus/'.$modx->getOption('manager_language',null,$modx->getOption('cultureKey',null,'en')), array(
    xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_menu_key', null, 'menu'),
    xPDO::OPT_CACHE_HANDLER => $this->modx->getOption('cache_menu_handler', null, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER)),
    xPDO::OPT_CACHE_FORMAT => (integer) $this->modx->getOption('cache_menu_format', null, $this->modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
));
if ($menus == null) {
    $menu = $modx->newObject('modMenu');
    $menus = $menu->rebuildCache();
    unset($menu);
}
$output = '';
$order = 0;
$showDescriptions = (boolean)$modx->getOption('topmenu_show_descriptions',null,true);
foreach ($menus as $menu) {
    $childrenCt = 0;

    if (!empty($menu['permissions'])) {
        $permissions = array();
        $exploded = explode(',', $menu['permissions']);
        foreach ($exploded as $permission) $permissions[trim($permission)]= true;
        if (!empty($permissions) && !$modx->hasPermission($permissions)) continue;
    }

    $menuTpl = '<li id="limenu-'.$menu['text'].'" class="top'.'">'."\n";
    if (!empty($menu['handler'])) {
        $menuTpl .= '<a href="javascript:;" onclick="'.str_replace('"','\'',$menu['handler']).'">'.$menu['text'].'</a>'."\n";
    } else if (!empty($menu['action'])) {
        $menuTpl .= '<a href="?a='.$menu['action'].$menu['params'].'">'.$menu['text'].'</a>'."\n";
    } else {
        $menuTpl .= '<a>'.$menu['text'].'</a>'."\n";
    }

    if (!empty($menu['children'])) {
        $menuTpl .= '<ul class="modx-subnav">'."\n";
        _modProcessMenus($modx,$menuTpl,$menu['children'],$childrenCt,$showDescriptions);
        $menuTpl .= '</ul>'."\n";
    }
    $menuTpl .= '</li>'."\n";

    /* if has no permissable children, and is not clickable, hide top menu item */
    if (!empty($childrenCt) || !empty($menu['action']) || !empty($menu['handler'])) {
        $output .= $menuTpl;
    }
    $order++;
}
function _modProcessMenus(modX &$modx,&$output,$menus,&$childrenCt,$showDescriptions = true) {
    foreach ($menus as $menu) {
        if (!empty($menu['permissions'])) {
            $permissions = array();
            $exploded = explode(',', $menu['permissions']);
            foreach ($exploded as $permission) $permissions[trim($permission)]= true;
            if (!empty($permissions) && !$modx->hasPermission($permissions)) continue;
        }
        $smTpl = '<li>'."\n";

        $description = !empty($menu['description']) ? '<span class="description">'.$menu['description'].'</span>'."\n" : '';

        if (!empty($menu['handler'])) {
            $smTpl .= '<a href="javascript:;" onclick="'.str_replace('"','\'',$menu['handler']).'">'.$menu['text'].($showDescriptions ? $description : '').'</a>'."\n";
        } else {
            $url = '?a='.$menu['action'].$menu['params'];
            $smTpl .= '<a href="'.$url.'">'.$menu['text'].($showDescriptions ? $description : '').'</a>'."\n";
        }

        if (!empty($menu['children'])) {
            $smTpl .= '<ul class="modx-subsubnav">'."\n";
            _modProcessMenus($modx,$smTpl,$menu['children'],$childrenCt,$showDescriptions);
            $smTpl .= '</ul>'."\n";
        }
        $smTpl .= '</li>';
        $output .= $smTpl;
        $childrenCt++;
    }
}
$modx->smarty->assign('navb',$output);


/* assign logged in text and link */
$profile = $modx->getObject('modMenu','profile');
$modx->smarty->assign('username',$modx->getLoginUserName());
$modx->smarty->assign('profileAction',$profile->get('action'));

/* assign welcome back text */
$welcome_back = $modx->lexicon('welcome_back',array('name' => $modx->getLoginUserName()));
$modx->smarty->assign('welcome_back',$welcome_back);
unset($welcome_back);


return $modx->smarty->fetch('header.tpl');