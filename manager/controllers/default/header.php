<?php
/**
 * Loads the main structure
 *
 * @var modX $modx
 * @var modManagerController $this
 *
 * @package modx
 * @subpackage manager.controllers
 */

/**
 * Recursively build nested menu structure
 *
 * @param modX $modx
 * @param $output
 * @param $menus
 * @param $childrenCt
 * @param bool $showDescriptions
 */
function _modProcessMenus(modX &$modx,&$output,$menus,&$childrenCt,$showDescriptions = true) {
    foreach ($menus as $menu) {
        if (!empty($menu['permissions'])) {
            $permissions = array();
            $exploded = explode(',', $menu['permissions']);
            foreach ($exploded as $permission) $permissions[trim($permission)]= true;
            if (!empty($permissions) && !$modx->hasPermission($permissions)) continue;
        }
        $smTpl = '<li id="'.$menu['id'].'">'."\n";

        if ($menu['namespace'] != 'core') {
            $menu['action'] .= '&namespace='.$menu['namespace'];
        }

        $description = !empty($menu['description']) ? '<span class="description">'.$menu['description'].'</span>'."\n" : '';

        if (!empty($menu['handler'])) {
            $smTpl .= '<a href="javascript:;" onclick="'.str_replace('"','\'',$menu['handler']).'">'.$menu['text'].($showDescriptions ? $description : '').'</a>'."\n";
        } else {
            $url = ($menu['action'] ? '?a='.$menu['action'].$menu['params'] : '#');
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

/* get top navbar */
$topNavCacheKey = 'menus/topnav/' . $modx->getOption('manager_language', null, $modx->getOption('cultureKey', null, 'en'));
$topNavMenus = $modx->cacheManager->get($topNavCacheKey, array(
    xPDO::OPT_CACHE_KEY => $modx->getOption('cache_menu_key', null, 'menu'),
    xPDO::OPT_CACHE_HANDLER => $modx->getOption('cache_menu_handler', null, $modx->getOption(xPDO::OPT_CACHE_HANDLER)),
    xPDO::OPT_CACHE_FORMAT => (integer) $modx->getOption('cache_menu_format', null, $modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
));
if ($topNavMenus == null || !is_array($topNavMenus)) {
    /** @var modMenu $menu */
    $menu = $modx->newObject('modMenu');
    $topNavMenus = $menu->rebuildCache('topnav');
    unset($menu);
}
$output = '';
$order = 0;
$showDescriptions = (boolean)$modx->getOption('topmenu_show_descriptions',null,true);
foreach ($topNavMenus as $menu) {
    $childrenCt = 0;

    if (!empty($menu['permissions'])) {
        $permissions = array();
        $exploded = explode(',', $menu['permissions']);
        foreach ($exploded as $permission) $permissions[trim($permission)]= true;
        if (!empty($permissions) && !$modx->hasPermission($permissions)) continue;
    }
    if ($menu['namespace'] != 'core') {
        $menu['action'] .= '&namespace='.$menu['namespace'];
    }

    $menuTpl = '<li id="limenu-'.$menu['id'].'" class="top'.'">'."\n";
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
$emptySub = '<ul class="modx-subsubnav">'."\n".'</ul>'."\n";
$output = str_replace($emptySub, '', $output);
$this->setPlaceholder('navb',$output);

/* get user navbar */
$userNavCacheKey = 'menus/usernav/' . $modx->getOption('manager_language', null, $modx->getOption('cultureKey', null, 'en'));
$userNavMenus = $modx->cacheManager->get($userNavCacheKey, array(
    xPDO::OPT_CACHE_KEY => $modx->getOption('cache_menu_key', null, 'menu'),
    xPDO::OPT_CACHE_HANDLER => $modx->getOption('cache_menu_handler', null, $modx->getOption(xPDO::OPT_CACHE_HANDLER)),
    xPDO::OPT_CACHE_FORMAT => (integer) $modx->getOption('cache_menu_format', null, $modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
));
if ($userNavMenus == null || !is_array($userNavMenus)) {
    /** @var modMenu $menu */
    $menu = $modx->newObject('modMenu');
    $userNavMenus = $menu->rebuildCache('usernav');
    unset($menu);
}
$output = '';
$order = 0;
$showDescriptions = (boolean)$modx->getOption('topmenu_show_descriptions',null,true);
foreach ($userNavMenus as $menu) {
    $childrenCt = 0;

    if (!empty($menu['permissions'])) {
        $permissions = array();
        $exploded = explode(',', $menu['permissions']);
        foreach ($exploded as $permission) $permissions[trim($permission)]= true;
        if (!empty($permissions) && !$modx->hasPermission($permissions)) continue;
    }
    if ($menu['namespace'] != 'core') {
        $menu['action'] .= '&namespace='.$menu['namespace'];
    }

    $menuTpl = '<li id="limenu-'.$menu['id'].'" class="top'.'">'."\n";
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
$emptySub = '<ul class="modx-subsubnav">'."\n".'</ul>'."\n";
$output = str_replace($emptySub, '', $output);
$this->setPlaceholder('navbUser',$output);

/** @var modUserProfile $userProfile */
$userProfile = $modx->user->getOne('Profile');

if ($userProfile->photo) {
    $src = $modx->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?zc=1&h=32&w=32&src=' . $userProfile->photo;
    $userImage = '<img src="' . $src . '" />';
} else {
    $gravemail = md5( strtolower( trim( $userProfile->email ) ) );
    $gravsrc = 'http://www.gravatar.com/avatar/' . $gravemail . '?s=32';
    $gravcheck = 'http://www.gravatar.com/avatar/' . $gravemail . '?d=404';
    $response = get_headers($gravcheck);

    if ($response != false){
        $userImage = '<img src="' . $gravsrc . '" />';
    } else {
        $userImage = '<i class="icon-user icon-large"></i>';
    }
}

$this->setPlaceholder('userImage',$userImage);

/* assign logged in text and link */
/** @var modMenu $profile */
$profile = $modx->getObject('modMenu','profile');
$this->setPlaceholder('username',$modx->getLoginUserName());
//$this->setPlaceholder('user_email',$modx->getLoginUserEmail());
$this->setPlaceholder('canChangeProfile',$modx->hasPermission('change_profile'));
$this->setPlaceholder('canLogout',$modx->hasPermission('logout'));
$this->setPlaceholder('canModifySettings',$modx->hasPermission('logout'));
$this->setPlaceholder('canCustomizeManager',$modx->hasPermission('customize_forms'));
$this->setPlaceholder('canManageDashboards',$modx->hasPermission('dashboards'));
$this->setPlaceholder('canManageContexts',$modx->hasPermission('view_contexts'));
$this->setPlaceholder('canManageTopNav',$modx->hasPermission('actions'));
$this->setPlaceholder('canManageACLs',$modx->hasPermission('access_permissions'));
$this->setPlaceholder('canManageProperties',$modx->hasPermission('property_sets'));
$this->setPlaceholder('canManageLexicons',$modx->hasPermission('lexicons'));
$this->setPlaceholder('canManageNamespaces',$modx->hasPermission('namespaces'));

/* assign welcome back text */
$welcome_back = $modx->lexicon('welcome_back',array('name' => $modx->getLoginUserName()));
$this->setPlaceholder('welcome_back',$welcome_back);
unset($welcome_back);
