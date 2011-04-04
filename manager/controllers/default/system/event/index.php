<?php
/**
 * Loads the error log page
 *
 * @package modx
 * @subpackage manager.system.event
 */
if (!$modx->hasPermission('error_log_view')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.panel.error.log.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/event.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
MODx.hasEraseErrorLog = "'.($modx->hasPermission('error_log_erase') ? 1 : 0).'"
</script>');

$modx->smarty->assign('_pagetitle',$modx->lexicon('error_log'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('system/event/list.tpl');