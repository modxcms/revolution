<?php
/**
 * Loads the profile page
 *
 * @package modx
 * @subpackage manager.security.profile
 */
if (!$modx->hasPermission('change_profile')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->user->profile = $modx->user->getOne('modUserProfile');
$modx->smarty->assign('user',$modx->user);


/* register JS scripts */
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/security/modx.grid.user.recent.resource.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/sections/security/profile/update.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-profile"
        ,user: "'.$modx->user->get('id').'"
    });
});
// ]]>
</script>');

return $modx->smarty->fetch('security/profile/index.tpl');