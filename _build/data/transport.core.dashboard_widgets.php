<?php
/**
 * Default Dashboard Widgets
 */
$widgets = array();
$widgets[1]= $xpdo->newObject('modDashboardWidget');
$widgets[1]->fromArray(array (
  'name' => 'w_newsfeed',
  'description' => 'w_newsfeed_desc',
  'type' => 'file',
  'size' => 'half',
  'content' => '[[++manager_path]]controllers/default/dashboard/widget.modx-news.php',
  'namespace' => 'core',
  'lexicon' => 'core:dashboards',
), '', true, true);

$widgets[2]= $xpdo->newObject('modDashboardWidget');
$widgets[2]->fromArray(array (
  'name' => 'w_securityfeed',
  'description' => 'w_securityfeed_desc',
  'type' => 'file',
  'size' => 'half',
  'content' => '[[++manager_path]]controllers/default/dashboard/widget.modx-security.php',
  'namespace' => 'core',
  'lexicon' => 'core:dashboards',
), '', true, true);

$widgets[3]= $xpdo->newObject('modDashboardWidget');
$widgets[3]->fromArray(array (
  'name' => 'w_whosonline',
  'description' => 'w_whosonline_desc',
  'type' => 'file',
  'size' => 'half',
  'content' => '[[++manager_path]]controllers/default/dashboard/widget.grid-online.php',
  'namespace' => 'core',
  'lexicon' => 'core:dashboards',
), '', true, true);

$widgets[4]= $xpdo->newObject('modDashboardWidget');
$widgets[4]->fromArray(array (
  'name' => 'w_recentlyeditedresources',
  'description' => 'w_recentlyeditedresources_desc',
  'type' => 'file',
  'size' => 'half',
  'content' => '[[++manager_path]]controllers/default/dashboard/widget.grid-rer.php',
  'namespace' => 'core',
  'lexicon' => 'core:dashboards',
), '', true, true);

$widgets[5]= $xpdo->newObject('modDashboardWidget');
$widgets[5]->fromArray(array (
  'name' => 'w_configcheck',
  'description' => 'w_configcheck_desc',
  'type' => 'file',
  'size' => 'full',
  'content' => '[[++manager_path]]controllers/default/dashboard/widget.configcheck.php',
  'namespace' => 'core',
  'lexicon' => 'core:dashboards',
), '', true, true);

return $widgets;
