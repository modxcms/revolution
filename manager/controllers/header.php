<?php
/**
 * Loads the main structure
 *
 * @package modx
 * @subpackage manager
 */
$menus = $modx->cacheManager->get('mgr/menus');
if ($menus == null) {
    $menu = $modx->newObject('modMenu');
    $menus = $menu->rebuildCache();
    unset($menu);
}
$modx->smarty->assign('menus',$menus);


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