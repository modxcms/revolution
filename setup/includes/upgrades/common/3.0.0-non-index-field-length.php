<?php
/**
 * Adjust column sizes
 *
 * MySQL ONLY
 *
 * @var modX $modx
 *
 * @package setup
 */


$manager = $modx->getManager();

$manager->alterField(\MODX\Revolution\modAccessPolicy::class, 'lexicon');

$manager->alterField(\MODX\Revolution\modAccessPolicyTemplate::class, 'lexicon');

$manager->alterField(\MODX\Revolution\modActionDom::class, 'container');
$manager->alterField(\MODX\Revolution\modActionDom::class, 'constraint');

$manager->alterField(\MODX\Revolution\modActionField::class, 'name');
$manager->alterField(\MODX\Revolution\modActionField::class, 'form');
$manager->alterField(\MODX\Revolution\modActionField::class, 'other');

$manager->alterField(\MODX\Revolution\modActiveUser::class, 'action');

$manager->alterField(\MODX\Revolution\modChunk::class, 'description');
$manager->alterField(\MODX\Revolution\modChunk::class, 'static_file');

$manager->alterField(\MODX\Revolution\modContextSetting::class, 'area');

$manager->alterField(\MODX\Revolution\modDashboardWidget::class, 'size');
$manager->alterField(\MODX\Revolution\modDashboardWidget::class, 'permission');

$manager->alterField(\MODX\Revolution\modDashboardWidgetPlacement::class, 'size');

$manager->alterField(\MODX\Revolution\modFormCustomizationSet::class, 'constraint');

$manager->alterField(\MODX\Revolution\modManagerLog::class, 'item');

$manager->alterField(\MODX\Revolution\modMenu::class, 'description');
$manager->alterField(\MODX\Revolution\modMenu::class, 'icon');

$manager->alterField(\MODX\Revolution\modExtensionPackage::class, 'table_prefix');
$manager->alterField(\MODX\Revolution\modExtensionPackage::class, 'service_class');
$manager->alterField(\MODX\Revolution\modExtensionPackage::class, 'service_name');

$manager->alterField(\MODX\Revolution\modPlugin::class, 'static_file');
$manager->alterField(\MODX\Revolution\modPlugin::class, 'description');

$manager->alterField(\MODX\Revolution\modPropertySet::class, 'description');

$manager->alterField(\MODX\Revolution\modResource::class, 'link_attributes');
$manager->alterField(\MODX\Revolution\modResource::class, 'menutitle');

$manager->alterField(\MODX\Revolution\modSnippet::class, 'static_file');
$manager->alterField(\MODX\Revolution\modSnippet::class, 'description');

$manager->alterField(\MODX\Revolution\modSystemSetting::class, 'area');

$manager->alterField(\MODX\Revolution\modTemplate::class, 'description');
$manager->alterField(\MODX\Revolution\modTemplate::class, 'icon');
$manager->alterField(\MODX\Revolution\modTemplate::class, 'static_file');

$manager->alterField(\MODX\Revolution\modTemplateVar::class, 'description');
$manager->alterField(\MODX\Revolution\modTemplateVar::class, 'static_file');

$manager->alterField(\MODX\Revolution\modUserGroupSetting::class, 'area');

$manager->alterField(\MODX\Revolution\modUserMessage::class, 'subject');

$manager->alterField(\MODX\Revolution\modUserProfile::class, 'country');
$manager->alterField(\MODX\Revolution\modUserProfile::class, 'city');
$manager->alterField(\MODX\Revolution\modUserProfile::class, 'photo');
$manager->alterField(\MODX\Revolution\modUserProfile::class, 'website');

$manager->alterField(\MODX\Revolution\modUserSetting::class, 'area');
