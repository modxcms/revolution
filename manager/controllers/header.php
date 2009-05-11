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
}

$modx->smarty->assign('menus',$menus);

$welcome_back = $modx->lexicon('welcome_back',array('name' => $modx->getLoginUserName()));
$modx->smarty->assign('welcome_back',$welcome_back);


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