<?php
/**
 * Loads form customization profile editing panel
 *
 * @package modx
 * @subpackage manager.security.forms
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('access_denied'));

/* get profile */
if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('profile_err_ns'));
$profile = $modx->getObject('modFormCustomizationProfile',$_REQUEST['id']);
if (empty($profile)) return $modx->error->failure($modx->lexicon('profile_err_nfs',array('id' => $_REQUEST['id'])));

$profileArray = $profile->toArray();

$c = $modx->newQuery('modUserGroup');
$c->innerJoin('modFormCustomizationProfileUserGroup','FormCustomizationProfiles');
$c->where(array(
    'FormCustomizationProfiles.profile' => $profile->get('id'),
));
$c->sortby('name','ASC');
$usergroups = $modx->getCollection('modUserGroup',$c);

$profileArray['usergroups'] = array();
foreach ($usergroups as $usergroup) {
    $profileArray['usergroups'][] = array(
        $usergroup->get('id'),
        $usergroup->get('name'),
    );
}

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/fc/modx.fc.common.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/fc/modx.panel.fcprofile.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/fc/modx.grid.fcset.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/fc/profile/update.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-fc-profile-update"
        ,profile: "'.$profile->get('id').'"
        ,record: '.$modx->toJSON($profileArray).'
    });
});
// ]]>
</script>');

$modx->smarty->assign('_pagetitle',$modx->lexicon('form_customization'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('security/forms/profile.tpl');