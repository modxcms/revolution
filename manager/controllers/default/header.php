<?php
/**
 * Loads the main structure
 *
 * @package modx
 * @subpackage manager
 */
function getStrBtwn($str,$start,$end) {
        $r = explode($start,$str);
        if (!empty($r[1])) {
            $r = explode($end, $r[1]);
            return $r[0];
        }
        return false;
}
$clog = file_get_contents($modx->getOption('core_path').'docs/changelog.txt');
$rev = getStrBtwn($clog,'$LastChangedRevision: ',' $');
$modx->smarty->assign('revision',$rev);

/* get top navbar */
$menus = $modx->cacheManager->get('mgr/menus');
if ($menus == null) {
    $menu = $modx->newObject('modMenu');
    $menus = $menu->rebuildCache();
    unset($menu);
}
$output = '';
$order = 0;
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
        _modProcessMenus($modx,$menuTpl,$menu['children'],$childrenCt);
        $menuTpl .= '</ul>'."\n";
    }
    $menuTpl .= '</li>'."\n";

    /* if has no permissable children, and is not clickable, hide top menu item */
    if (!empty($childrenCt) || !empty($menu['action']) || !empty($menu['handler'])) {
        $output .= $menuTpl;
    }
    $order++;
}
function _modProcessMenus(modX &$modx,&$output,$menus,&$childrenCt) {
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
            $smTpl .= '<a href="javascript:;" onclick="'.str_replace('"','\'',$menu['handler']).'">'.$menu['text'].$description.'</a>'."\n";
        } else {
            $url = '?a='.$menu['action'].$menu['params'];
            $smTpl .= '<a href="'.$url.'">'.$menu['text'].$description.'</a>'."\n";
        }

        if (!empty($menu['children'])) {
            $smTpl .= '<ul class="modx-subsubnav">'."\n";
            _modProcessMenus($modx,$smTpl,$menu['children'],$childrenCt);
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
$logged_in_as = $modx->lexicon('logged_in_as',array(
    'username' => '<a id="modx-login-user" href="?a='.$profile->get('action').'">'.$modx->getLoginUserName().'</a>',
));
$modx->smarty->assign('logged_in_as',$logged_in_as);
unset($logged_in_as);

/* assign welcome back text */
$welcome_back = $modx->lexicon('welcome_back',array('name' => $modx->getLoginUserName()));
$modx->smarty->assign('welcome_back',$welcome_back);
unset($welcome_back);


return $modx->smarty->fetch('header.tpl');