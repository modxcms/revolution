<?php
/**
 * Loads the system settings page
 *
 * @package modx
 * @subpackage manager.system.settings
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('access_denied'));

/* render event */
$onSiteSettingsRender = $modx->invokeEvent('OnSiteSettingsRender');
if (is_array($onSiteSettingsRender)) $onSiteSettingsRender = implode("\n",$onSiteSettingsRender);
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
// <[!CDATA[
MODx.onSiteSettingsRender = "'.$onSiteSettingsRender.'";
// ]]>
</script>');

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.settings.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.panel.system.settings.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/settings.js');

$modx->smarty->assign('_pagetitle',$modx->lexicon('system_settings'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('system/settings/index.tpl');