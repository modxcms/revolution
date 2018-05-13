<?php
/**
 * Create a new MODX Revolution repository.
 *
 * @var \xPDO\xPDO $modx
 * @var modInstall $install
 * @var modInstallRunner $this
 *
 * @package setup
 */

$results= array ();
$classes= array (
    'MODX\modAccessAction',
    'MODX\modAccessActionDom',
    'MODX\modAccessCategory',
    'MODX\modAccessContext',
    'MODX\modAccessElement',
    'MODX\modAccessMenu',
    'MODX\modAccessPermission',
    'MODX\modAccessPolicy',
    'MODX\modAccessPolicyTemplate',
    'MODX\modAccessPolicyTemplateGroup',
    'MODX\modAccessResource',
    'MODX\modAccessResourceGroup',
    'MODX\modAccessTemplateVar',
    'MODX\modAccessNamespace',
    'MODX\modAction',
    'MODX\modActionDom',
    'MODX\modActionField',
    'MODX\modActiveUser',
    'MODX\modCategory',
    'MODX\modCategoryClosure',
    'MODX\modChunk',
    'MODX\modClassMap',
    'MODX\modContentType',
    'MODX\modContext',
    'MODX\modContextResource',
    'MODX\modContextSetting',
    'MODX\modDashboard',
    'MODX\modDashboardWidget',
    'MODX\modDashboardWidgetPlacement',
    'MODX\modElementPropertySet',
    'MODX\modEvent',
    'MODX\modExtensionPackage',
    'MODX\modFormCustomizationProfile',
    'MODX\modFormCustomizationProfileUserGroup',
    'MODX\modFormCustomizationSet',
    'MODX\modLexiconEntry',
    'MODX\modManagerLog',
    'MODX\modMenu',
    'MODX\modNamespace',
    'MODX\modPlugin',
    'MODX\modPluginEvent',
    'MODX\modPropertySet',
    'MODX\modResource',
    'MODX\modResourceGroup',
    'MODX\modResourceGroupResource',
    'MODX\modSession',
    'MODX\modSnippet',
    'MODX\modSystemSetting',
    'MODX\modTemplate',
    'MODX\modTemplateVar',
    'MODX\modTemplateVarResource',
    'MODX\modTemplateVarResourceGroup',
    'MODX\modTemplateVarTemplate',
    'MODX\modUser',
    'MODX\modUserProfile',
    'MODX\modUserGroup',
    'MODX\modUserGroupMember',
    'MODX\modUserGroupRole',
    'MODX\modUserGroupSetting',
    'MODX\modUserMessage',
    'MODX\modUserSetting',
    'MODX\modWorkspace',
    'MODX\Registry\Db\modDbRegisterMessage',
    'MODX\Registry\Db\modDbRegisterTopic',
    'MODX\Registry\Db\modDbRegisterQueue',
    'MODX\Transport\modTransportPackage',
    'MODX\Transport\modTransportProvider',
    'MODX\Sources\modAccessMediaSource',
    'MODX\Sources\modMediaSource',
    'MODX\Sources\modMediaSourceElement',
    'MODX\Sources\modMediaSourceContext',
);

$modx->getManager();
$connected= $modx->connect();
$created= false;
if (!$connected) {
    $dsnArray= \xPDO\xPDO :: parseDSN($modx->getOption('dsn'));
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
