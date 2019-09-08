<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

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
    \MODX\Revolution\modAccessAction::class,
    \MODX\Revolution\modAccessActionDom::class,
    \MODX\Revolution\modAccessCategory::class,
    \MODX\Revolution\modAccessContext::class,
    \MODX\Revolution\modAccessElement::class,
    \MODX\Revolution\modAccessMenu::class,
    \MODX\Revolution\modAccessPermission::class,
    \MODX\Revolution\modAccessPolicy::class,
    \MODX\Revolution\modAccessPolicyTemplate::class,
    \MODX\Revolution\modAccessPolicyTemplateGroup::class,
    \MODX\Revolution\modAccessResource::class,
    \MODX\Revolution\modAccessResourceGroup::class,
    \MODX\Revolution\modAccessTemplateVar::class,
    \MODX\Revolution\modAccessNamespace::class,
    \MODX\Revolution\modAction::class,
    \MODX\Revolution\modActionDom::class,
    \MODX\Revolution\modActionField::class,
    \MODX\Revolution\modActiveUser::class,
    \MODX\Revolution\modCategory::class,
    \MODX\Revolution\modCategoryClosure::class,
    \MODX\Revolution\modChunk::class,
    \MODX\Revolution\modClassMap::class,
    \MODX\Revolution\modContentType::class,
    \MODX\Revolution\modContext::class,
    \MODX\Revolution\modContextResource::class,
    \MODX\Revolution\modContextSetting::class,
    \MODX\Revolution\modDashboard::class,
    \MODX\Revolution\modDashboardWidget::class,
    \MODX\Revolution\modDashboardWidgetPlacement::class,
    \MODX\Revolution\modElementPropertySet::class,
    \MODX\Revolution\modEvent::class,
    \MODX\Revolution\modExtensionPackage::class,
    \MODX\Revolution\modFormCustomizationProfile::class,
    \MODX\Revolution\modFormCustomizationProfileUserGroup::class,
    \MODX\Revolution\modFormCustomizationSet::class,
    \MODX\Revolution\modLexiconEntry::class,
    \MODX\Revolution\modManagerLog::class,
    \MODX\Revolution\modMenu::class,
    \MODX\Revolution\modNamespace::class,
    \MODX\Revolution\modPlugin::class,
    \MODX\Revolution\modPluginEvent::class,
    \MODX\Revolution\modPropertySet::class,
    \MODX\Revolution\modResource::class,
    \MODX\Revolution\modResourceGroup::class,
    \MODX\Revolution\modResourceGroupResource::class,
    \MODX\Revolution\modSession::class,
    \MODX\Revolution\modSnippet::class,
    \MODX\Revolution\modSystemSetting::class,
    \MODX\Revolution\modTemplate::class,
    \MODX\Revolution\modTemplateVar::class,
    \MODX\Revolution\modTemplateVarResource::class,
    \MODX\Revolution\modTemplateVarResourceGroup::class,
    \MODX\Revolution\modTemplateVarTemplate::class,
    \MODX\Revolution\modUser::class,
    \MODX\Revolution\modUserProfile::class,
    \MODX\Revolution\modUserGroup::class,
    \MODX\Revolution\modUserGroupMember::class,
    \MODX\Revolution\modUserGroupRole::class,
    \MODX\Revolution\modUserGroupSetting::class,
    \MODX\Revolution\modUserMessage::class,
    \MODX\Revolution\modUserSetting::class,
    \MODX\Revolution\modWorkspace::class,
    \MODX\Revolution\Registry\Db\modDbRegisterMessage::class,
    \MODX\Revolution\Registry\Db\modDbRegisterTopic::class,
    \MODX\Revolution\Registry\Db\modDbRegisterQueue::class,
    \MODX\Revolution\Transport\modTransportPackage::class,
    \MODX\Revolution\Transport\modTransportProvider::class,
    \MODX\Revolution\Sources\modAccessMediaSource::class,
    \MODX\Revolution\Sources\modMediaSource::class,
    \MODX\Revolution\Sources\modMediaSourceElement::class,
    \MODX\Revolution\Sources\modMediaSourceContext::class,
);

$modx->getManager();
$connected= $modx->connect();

$modx->setPackage('Revolution', MODX_CORE_PATH . 'src/');
$modx->addPackage('Revolution\Registry\Db', MODX_CORE_PATH . 'src/');
$modx->addPackage('Revolution\Sources', MODX_CORE_PATH . 'src/');
$modx->addPackage('Revolution\Transport', MODX_CORE_PATH . 'src/');

$created= false;
if (!$connected) {
    $dsnArray= \xPDO\xPDO :: parseDSN($modx->getOption('dsn'));
    $containerOptions['charset']= $install->settings->get('database_charset', 'utf8');
    $containerOptions['collation']= $install->settings->get('database_collation', 'utf8_general_ci');
    $created = $modx->manager->createSourceContainer($dsnArray, $modx->config['username'], $modx->config['password'], $containerOptions);
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
