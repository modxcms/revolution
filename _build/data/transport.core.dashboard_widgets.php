<?php
/**
 * Default Dashboard Widgets
 */

use MODX\Revolution\modDashboardWidget;

$widgets = array();
$widgets[1]= $xpdo->newObject(modDashboardWidget::class);
$widgets[1]->fromArray(array (
  'name' => 'w_newsfeed',
  'description' => 'w_newsfeed_desc',
  'type' => 'file',
  'size' => 'one-third',
  'content' => '[[++manager_path]]controllers/default/dashboard/widget.modx-news.php',
  'namespace' => 'core',
  'lexicon' => 'core:dashboards',
), '', true, true);

$widgets[2]= $xpdo->newObject(modDashboardWidget::class);
$widgets[2]->fromArray(array (
  'name' => 'w_securityfeed',
  'description' => 'w_securityfeed_desc',
  'type' => 'file',
  'size' => 'one-third',
  'content' => '[[++manager_path]]controllers/default/dashboard/widget.modx-security.php',
  'namespace' => 'core',
  'lexicon' => 'core:dashboards',
), '', true, true);

$widgets[3]= $xpdo->newObject(modDashboardWidget::class);
$widgets[3]->fromArray(array (
  'name' => 'w_whosonline',
  'description' => 'w_whosonline_desc',
  'type' => 'file',
  'size' => 'one-third',
  'content' => '[[++manager_path]]controllers/default/dashboard/widget.grid-online.php',
  'namespace' => 'core',
  'lexicon' => 'core:dashboards',
), '', true, true);

$widgets[4]= $xpdo->newObject(modDashboardWidget::class);
$widgets[4]->fromArray(array (
  'name' => 'w_recentlyeditedresources',
  'description' => 'w_recentlyeditedresources_desc',
  'type' => 'file',
  'permission' => 'view_document',
  'size' => 'two-thirds',
  'content' => '[[++manager_path]]controllers/default/dashboard/widget.grid-rer.php',
  'namespace' => 'core',
  'lexicon' => 'core:dashboards',
), '', true, true);

$widgets[5]= $xpdo->newObject(modDashboardWidget::class);
$widgets[5]->fromArray(array (
  'name' => 'w_configcheck',
  'description' => 'w_configcheck_desc',
  'type' => 'file',
  'size' => 'full',
  'content' => '[[++manager_path]]controllers/default/dashboard/widget.configcheck.php',
  'namespace' => 'core',
  'lexicon' => 'core:dashboards',
), '', true, true);

$widgets[6]= $xpdo->newObject(modDashboardWidget::class);
$widgets[6]->fromArray(array (
  'name' => 'w_buttons',
  'description' => 'w_buttons_desc',
  'type' => 'file',
  'size' => 'full',
  'properties' => json_encode([
      'create-resource' => [
          'link' => '[[++manager_url]]?a=resource/create',
          'icon' => 'file-o',
          'title' => '[[%action_new_resource]]',
          'description' => '[[%action_new_resource_desc]]'
      ],
      'view-site' => [
          'link' => '[[++site_url]]',
          'icon' => 'eye',
          'title' => '[[%action_view_website]]',
          'description' => '[[%action_view_website_desc]]',
          'target' => '_blank'
      ],
      'advanced-search' => [
          'link' => '[[++manager_url]]?a=search',
          'icon' => 'search',
          'title' => '[[%action_advanced_search]]',
          'description' => '[[%action_advanced_search_desc]]',
      ],
      'manage-users' => [
          'link' => '[[++manager_url]]?a=security/user',
          'icon' => 'user',
          'title' => '[[%action_manage_users]]',
          'description' => '[[%action_manage_users_desc]]',
      ],
  ]),
  'content' => '[[++manager_path]]controllers/default/dashboard/widget.buttons.php',
  'namespace' => 'core',
  'lexicon' => 'core:dashboards',
), '', true, true);

$widgets[7]= $xpdo->newObject(modDashboardWidget::class);
$widgets[7]->fromArray(array (
  'name' => 'w_updates',
  'description' => 'w_updates_desc',
  'type' => 'file',
  'permission' => 'workspaces',
  'size' => 'one-third',
  'content' => '[[++manager_path]]controllers/default/dashboard/widget.updates.php',
  'namespace' => 'core',
  'lexicon' => 'core:dashboards',
), '', true, true);

$widgets[9]= $xpdo->newObject('modDashboardWidget');
$widgets[9]->fromArray(array (
    'name' => 'w_shortcuts',
    'description' => 'w_shortcuts_desc',
    'type' => 'file',
    'permission' => 'workspaces',
    'size' => 'one-third',
    'content' => '[[++manager_path]]controllers/default/dashboard/widget.shortcuts.php',
    'namespace' => 'core',
    'lexicon' => 'core:dashboards',
), '', true, true);

return $widgets;
