<?php
/**
 * Create a new MODX Revolution repository.
 *
 * @var xPDO $modx
 * @var modInstall $install
 * @var modInstallRunner $this
 * 
 * @package setup
 */

$results= array ();
$classes= array (
    'modAccessAction',
    'modAccessActionDom',
    'modAccessCategory',
    'modAccessContext',
    'modAccessElement',
    'modAccessMenu',
    'modAccessPermission',
    'modAccessPolicy',
    'modAccessPolicyTemplate',
    'modAccessPolicyTemplateGroup',
    'modAccessResource',
    'modAccessResourceGroup',
    'modAccessTemplateVar',
    'modAction',
    'modActionDom',
    'modActionField',
    'modActiveUser',
    'modCategory',
    'modCategoryClosure',
    'modChunk',
    'modClassMap',
    'modContentType',
    'modContext',
    'modContextResource',
    'modContextSetting',
    'modDashboard',
    'modDashboardWidget',
    'modDashboardWidgetPlacement',
    'modElementPropertySet',
    'modEvent',
    'modFormCustomizationProfile',
    'modFormCustomizationProfileUserGroup',
    'modFormCustomizationSet',
    'modLexiconEntry',
    'modManagerLog',
    'modMenu',
    'modNamespace',
    'modPlugin',
    'modPluginEvent',
    'modPropertySet',
    'modResource',
    'modResourceGroup',
    'modResourceGroupResource',
    'modSession',
    'modSnippet',
    'modSystemSetting',
    'modTemplate',
    'modTemplateVar',
    'modTemplateVarResource',
    'modTemplateVarResourceGroup',
    'modTemplateVarTemplate',
    'modUser',
    'modUserProfile',
    'modUserGroup',
    'modUserGroupMember',
    'modUserGroupRole',
    'modUserMessage',
    'modUserSetting',
    'modWorkspace',
    'registry.db.modDbRegisterMessage',
    'registry.db.modDbRegisterTopic',
    'registry.db.modDbRegisterQueue',
    'transport.modTransportPackage',
    'transport.modTransportProvider',
    'sources.modAccessMediaSource',
    'sources.modMediaSource',
    'sources.modMediaSourceElement',
    'sources.modMediaSourceContext',
);

$modx->getManager();
$connected= $modx->connect();
$created= false;
if (!$connected) {
    $dsnArray= xPDO :: parseDSN($modx->getOption('dsn'));
    $containerOptions['charset']= $install->settings->get('database_charset', 'utf8');
    $containerOptions['collation']= $install->settings->get('database_collation', 'utf8_general_ci');
    $created= $modx->manager->createSourceContainer($dsnArray, $modx->config['username'], $modx->config['password']);
    if (!$created) {
        $results[]= array ('class' => 'failed', 'msg' => '<p class="notok">'.$install->lexicon('db_err_create').'</p>');
    }
    else {
        $connected= $modx->connect();
    }
    if ($connected) {
        $results[]= array ('class' => 'success', 'msg' => '<p class="ok">'.$install->lexicon('db_created').'</p>');
    }
}
if ($connected) {
    ob_start();
    $modx->loadClass('modAccess');
    $modx->loadClass('modAccessibleObject');
    $modx->loadClass('modAccessibleSimpleObject');
    $modx->loadClass('modResource');
    $modx->loadClass('modElement');
    $modx->loadClass('modScript');
    $modx->loadClass('modPrincipal');
    $modx->loadClass('modUser');
    foreach ($classes as $class) {
        if (!$dbcreated= $modx->manager->createObjectContainer($class)) {
            $results[]= array ('class' => 'failed', 'msg' => '<p class="notok">' . $install->lexicon('table_err_create',array('class' => $class)) . '</p>');
        } else {
            $results[]= array ('class' => 'success', 'msg' => '<p class="ok">' . $install->lexicon('table_created',array('class' => $class)) . '</p>');
        }
    }
    ob_end_clean();
}
return $results;