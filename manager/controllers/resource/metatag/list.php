<?php
if (!$modx->hasPermission('manage_metatags')) return $modx->error->failure($modx->lexicon('access_denied'));

$metatags = $modx->getCollection('modMetatag');	
$modx->smarty->assign('metatags',$metatags);

$modx->smarty->display('resource/metatag/list.tpl');
?>