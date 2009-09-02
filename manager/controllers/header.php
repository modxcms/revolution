<?php
/**
 * Loads the main structure
 *
 * @package modx
 * @subpackage manager
 */
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
    $output .= '<li id="limenu'.$menu['id'].'" class="top'.($order == 0 ? ' active' : '').'">'."\n";
    $output .= '<a href="javascript:;" onmouseover="MODx.changeMenu(this,\'menu'.$menu['id'].'\');">'.$menu['text'].'</a>'."\n";
    $output .= '<div class="zone">'."\n";

    if (!empty($menu['children'])) {
        $output .= '<ul class="modx-subnav">'."\n";
        _modProcessMenus($output,$menu['children']);
        $output .= '</ul>'."\n";
    }
    $output .= '</div>'."\n";
    $output .= '</li>'."\n";
    $order++;
}
function _modProcessMenus(&$output,$menus) {
    foreach ($menus as $menu) {
        $output .= '<li>'."\n";
        $output .= '<a href="javascript:;"'."\n";
        $output .= '   onclick="';
        if (!empty($menu['handler'])) {
            $output .= str_replace('"','\'',$menu['handler']); // escape
        } else {
            $output .= 'MODx.loadPage('.$menu['action'].',\''.$menu['params'].'\');';
        }
        $output .= '">'.$menu['text'];
        if (!empty($menu['description'])) {
            $output .= '<span class="description">'.$menu['description'].'</span>'."\n";
        }
        $output .= '</a>'."\n";

        if (!empty($menu['children'])) {
            $output .= '<ul class="modx-subsubnav">'."\n";
            _modProcessMenus($output,$menu['children']);
            $output .= '</ul>'."\n";
        }
        $output .= '</li>';
    }
    $output .= '<li class="last"><span>&nbsp;</span></li>'."\n";
}
$modx->smarty->assign('navb',$output);


/* assign logged in text and link */
$logged_in_as = $modx->lexicon('logged_in_as',array(
    'username' => '<a id="modx-login-user" onclick="MODx.loadPage(49);">'.$modx->getLoginUserName().'</a>',
));
$modx->smarty->assign('logged_in_as',$logged_in_as);
unset($logged_in_as);

/* assign welcome back text */
$welcome_back = $modx->lexicon('welcome_back',array('name' => $modx->getLoginUserName()));
$modx->smarty->assign('welcome_back',$welcome_back);
unset($welcome_back);

/* register JS scripts */
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-layout"
        ,accordionPanels: MODx.accordionPanels || []
    });
});
</script>');
if (!empty($modx->sjscripts)) {
    $modx->smarty->assign('cssjs',$modx->sjscripts);
}

return $modx->smarty->fetch('header.tpl');