<?php
/**
 * @var modX|xPDO $xpdo
 * @package modx
 * @subpackage build
 */
use MODX\Revolution\modAction;

$collection = [];
$collection['1']= $xpdo->newObject(modAction::class);
$collection['1']->fromArray([
  'id' => 1,
  'namespace' => 'core',
  'controller' => 'welcome',
  'haslayout' => 1,
  'lang_topics' => 'welcome,configcheck',
  'assets' => '',
], '', true, true);
$collection['3']= $xpdo->newObject(modAction::class);
$collection['3']->fromArray([
  'id' => 3,
  'namespace' => 'core',
  'controller' => 'system',
  'haslayout' => 0,
  'lang_topics' => '',
  'assets' => '',
], '', true, true);
$collection['5']= $xpdo->newObject(modAction::class);
$collection['5']->fromArray([
  'id' => 5,
  'namespace' => 'core',
  'controller' => 'browser',
  'haslayout' => 0,
  'lang_topics' => 'file',
  'assets' => '',
], '', true, true);
$collection['7']= $xpdo->newObject(modAction::class);
$collection['7']->fromArray([
  'id' => 7,
  'namespace' => 'core',
  'controller' => 'context/create',
  'haslayout' => 1,
  'lang_topics' => 'context,setting,access,policy,user',
  'assets' => '',
  'help_url' => 'Contexts',
], '', true, true);
$collection['8']= $xpdo->newObject(modAction::class);
$collection['8']->fromArray([
  'id' => 8,
  'namespace' => 'core',
  'controller' => 'context/update',
  'haslayout' => 1,
  'lang_topics' => 'context,setting,access,policy,user',
  'assets' => '',
  'help_url' => 'Contexts',
], '', true, true);
$collection['9']= $xpdo->newObject(modAction::class);
$collection['9']->fromArray([
  'id' => 9,
  'namespace' => 'core',
  'controller' => 'context/view',
  'haslayout' => 1,
  'lang_topics' => 'context',
  'assets' => '',
  'help_url' => 'Contexts',
], '', true, true);
$collection['10']= $xpdo->newObject(modAction::class);
$collection['10']->fromArray([
  'id' => 10,
  'namespace' => 'core',
  'controller' => 'element',
  'haslayout' => 1,
  'lang_topics' => 'element',
  'assets' => '',
], '', true, true);
$collection['11']= $xpdo->newObject(modAction::class);
$collection['11']->fromArray([
  'id' => 11,
  'namespace' => 'core',
  'controller' => 'element/chunk',
  'haslayout' => 1,
  'lang_topics' => 'chunk,category,propertyset,element',
  'assets' => '',
  'help_url' => 'Chunks',
], '', true, true);
$collection['12']= $xpdo->newObject(modAction::class);
$collection['12']->fromArray([
  'id' => 12,
  'namespace' => 'core',
  'controller' => 'element/chunk/create',
  'haslayout' => 1,
  'lang_topics' => 'chunk,category,propertyset,element',
  'assets' => '',
  'help_url' => 'Chunks',
], '', true, true);
$collection['13']= $xpdo->newObject(modAction::class);
$collection['13']->fromArray([
  'id' => 13,
  'namespace' => 'core',
  'controller' => 'element/chunk/update',
  'haslayout' => 1,
  'lang_topics' => 'chunk,category,propertyset,element',
  'assets' => '',
  'help_url' => 'Chunks',
], '', true, true);
$collection['20']= $xpdo->newObject(modAction::class);
$collection['20']->fromArray([
  'id' => 20,
  'namespace' => 'core',
  'controller' => 'element/plugin',
  'haslayout' => 1,
  'lang_topics' => 'plugin,category,system_events,propertyset,element',
  'assets' => '',
  'help_url' => 'Plugins',
], '', true, true);
$collection['21']= $xpdo->newObject(modAction::class);
$collection['21']->fromArray([
  'id' => 21,
  'namespace' => 'core',
  'controller' => 'element/plugin/create',
  'haslayout' => 1,
  'lang_topics' => 'plugin,category,system_events,propertyset,element',
  'assets' => '',
  'help_url' => 'Plugins',
], '', true, true);
$collection['22']= $xpdo->newObject(modAction::class);
$collection['22']->fromArray([
  'id' => 22,
  'namespace' => 'core',
  'controller' => 'element/plugin/update',
  'haslayout' => 1,
  'lang_topics' => 'plugin,category,system_events,propertyset,element',
  'assets' => '',
  'help_url' => 'Plugins',
], '', true, true);
$collection['25']= $xpdo->newObject(modAction::class);
$collection['25']->fromArray([
  'id' => 25,
  'namespace' => 'core',
  'controller' => 'element/snippet',
  'haslayout' => 1,
  'lang_topics' => 'snippet,propertyset,element',
  'assets' => '',
  'help_url' => 'Snippets',
], '', true, true);
$collection['26']= $xpdo->newObject(modAction::class);
$collection['26']->fromArray([
  'id' => 26,
  'namespace' => 'core',
  'controller' => 'element/snippet/create',
  'haslayout' => 1,
  'lang_topics' => 'snippet,propertyset,element',
  'assets' => '',
  'help_url' => 'Snippets',
], '', true, true);
$collection['27']= $xpdo->newObject(modAction::class);
$collection['27']->fromArray([
  'id' => 27,
  'namespace' => 'core',
  'controller' => 'element/snippet/update',
  'haslayout' => 1,
  'lang_topics' => 'snippet,propertyset,element',
  'assets' => '',
  'help_url' => 'Snippets',
], '', true, true);
$collection['28']= $xpdo->newObject(modAction::class);
$collection['28']->fromArray([
  'id' => 28,
  'namespace' => 'core',
  'controller' => 'element/template',
  'haslayout' => 1,
  'lang_topics' => 'template,propertyset,element',
  'assets' => '',
  'help_url' => 'Templates',
], '', true, true);
$collection['29']= $xpdo->newObject(modAction::class);
$collection['29']->fromArray([
  'id' => 29,
  'namespace' => 'core',
  'controller' => 'element/template/create',
  'haslayout' => 1,
  'lang_topics' => 'template,propertyset,element',
  'assets' => '',
  'help_url' => 'Templates',
], '', true, true);
$collection['30']= $xpdo->newObject(modAction::class);
$collection['30']->fromArray([
  'id' => 30,
  'namespace' => 'core',
  'controller' => 'element/template/update',
  'haslayout' => 1,
  'lang_topics' => 'template,propertyset,element',
  'assets' => '',
  'help_url' => 'Templates',
], '', true, true);
$collection['31']= $xpdo->newObject(modAction::class);
$collection['31']->fromArray([
  'id' => 31,
  'namespace' => 'core',
  'controller' => 'element/template/tvsort',
  'haslayout' => 1,
  'lang_topics' => 'template,tv,propertyset,element',
  'assets' => '',
], '', true, true);
$collection['32']= $xpdo->newObject(modAction::class);
$collection['32']->fromArray([
  'id' => 32,
  'namespace' => 'core',
  'controller' => 'element/tv',
  'haslayout' => 1,
  'lang_topics' => 'tv,propertyset,element',
  'assets' => '',
  'help_url' => 'Template+Variables',
], '', true, true);
$collection['33']= $xpdo->newObject(modAction::class);
$collection['33']->fromArray([
  'id' => 33,
  'namespace' => 'core',
  'controller' => 'element/tv/create',
  'haslayout' => 1,
  'lang_topics' => 'tv,tv_widget,propertyset,element',
  'assets' => '',
  'help_url' => 'Template+Variables',
], '', true, true);
$collection['34']= $xpdo->newObject(modAction::class);
$collection['34']->fromArray([
  'id' => 34,
  'namespace' => 'core',
  'controller' => 'element/tv/update',
  'haslayout' => 1,
  'lang_topics' => 'tv,tv_widget,propertyset,element',
  'assets' => '',
  'help_url' => 'Template+Variables',
], '', true, true);
$collection['35']= $xpdo->newObject(modAction::class);
$collection['35']->fromArray([
  'id' => 35,
  'namespace' => 'core',
  'controller' => 'element/view',
  'haslayout' => 1,
  'lang_topics' => 'element',
  'assets' => '',
], '', true, true);
$collection['36']= $xpdo->newObject(modAction::class);
$collection['36']->fromArray([
  'id' => 36,
  'namespace' => 'core',
  'controller' => 'resource',
  'haslayout' => 1,
  'lang_topics' => '',
  'assets' => '',
], '', true, true);
$collection['38']= $xpdo->newObject(modAction::class);
$collection['38']->fromArray([
  'id' => 38,
  'namespace' => 'core',
  'controller' => 'security/usergroup/create',
  'haslayout' => 1,
  'lang_topics' => 'user,access,policy,context',
  'assets' => '',
  'help_url' => 'User+Groups',
], '', true, true);
$collection['39']= $xpdo->newObject(modAction::class);
$collection['39']->fromArray([
  'id' => 39,
  'namespace' => 'core',
  'controller' => 'security/usergroup/update',
  'haslayout' => 1,
  'lang_topics' => 'user,access,policy,context',
  'assets' => '',
  'help_url' => 'User+Groups',
], '', true, true);
$collection['40']= $xpdo->newObject(modAction::class);
$collection['40']->fromArray([
  'id' => 40,
  'namespace' => 'core',
  'controller' => 'resource/data',
  'haslayout' => 1,
  'lang_topics' => 'resource',
  'assets' => '',
  'help_url' => 'Resource',
], '', true, true);
$collection['41']= $xpdo->newObject(modAction::class);
$collection['41']->fromArray([
  'id' => 41,
  'namespace' => 'core',
  'controller' => 'resource/empty_recycle_bin',
  'haslayout' => 1,
  'lang_topics' => 'resource',
  'assets' => '',
], '', true, true);
$collection['43']= $xpdo->newObject(modAction::class);
$collection['43']->fromArray([
  'id' => 43,
  'namespace' => 'core',
  'controller' => 'resource/update',
  'haslayout' => 1,
  'lang_topics' => 'resource',
  'assets' => '',
  'help_url' => 'Resource',
], '', true, true);
$collection['46']= $xpdo->newObject(modAction::class);
$collection['46']->fromArray([
  'id' => 46,
  'namespace' => 'core',
  'controller' => 'security',
  'haslayout' => 1,
  'lang_topics' => 'user',
  'assets' => '',
], '', true, true);
$collection['50']= $xpdo->newObject(modAction::class);
$collection['50']->fromArray([
  'id' => 50,
  'namespace' => 'core',
  'controller' => 'security/role',
  'haslayout' => 1,
  'lang_topics' => 'user',
  'assets' => '',
  'help_url' => 'Roles',
], '', true, true);
$collection['54']= $xpdo->newObject(modAction::class);
$collection['54']->fromArray([
  'id' => 54,
  'namespace' => 'core',
  'controller' => 'security/user/create',
  'haslayout' => 1,
  'lang_topics' => 'user,setting,access',
  'assets' => '',
  'help_url' => 'Users',
], '', true, true);
$collection['55']= $xpdo->newObject(modAction::class);
$collection['55']->fromArray([
  'id' => 55,
  'namespace' => 'core',
  'controller' => 'security/user/update',
  'haslayout' => 1,
  'lang_topics' => 'user,setting,access',
  'assets' => '',
  'help_url' => 'Users',
], '', true, true);
$collection['56']= $xpdo->newObject(modAction::class);
$collection['56']->fromArray([
  'id' => 56,
  'namespace' => 'core',
  'controller' => 'security/login',
  'haslayout' => 1,
  'lang_topics' => 'login',
  'assets' => '',
], '', true, true);
$collection['62']= $xpdo->newObject(modAction::class);
$collection['62']->fromArray([
  'id' => 62,
  'namespace' => 'core',
  'controller' => 'system/refresh_site',
  'haslayout' => 1,
  'lang_topics' => '',
  'assets' => '',
], '', true, true);
$collection['64']= $xpdo->newObject(modAction::class);
$collection['64']->fromArray([
  'id' => 64,
  'namespace' => 'core',
  'controller' => 'system/phpinfo',
  'haslayout' => 1,
  'lang_topics' => '',
  'assets' => '',
], '', true, true);
$collection['67']= $xpdo->newObject(modAction::class);
$collection['67']->fromArray([
  'id' => 67,
  'namespace' => 'core',
  'controller' => 'resource/tvs',
  'haslayout' => 0,
  'lang_topics' => '',
  'assets' => '',
], '', true, true);
$collection['70']= $xpdo->newObject(modAction::class);
$collection['70']->fromArray([
  'id' => 70,
  'namespace' => 'core',
  'controller' => 'system/file',
  'haslayout' => 1,
  'lang_topics' => 'file',
  'assets' => '',
], '', true, true);
$collection['71']= $xpdo->newObject(modAction::class);
$collection['71']->fromArray([
  'id' => 71,
  'namespace' => 'core',
  'controller' => 'system/file/edit',
  'haslayout' => 1,
  'lang_topics' => 'file',
  'assets' => '',
], '', true, true);
$collection['75']= $xpdo->newObject(modAction::class);
$collection['75']->fromArray([
  'id' => 75,
  'namespace' => 'core',
  'controller' => 'security/access/policy/update',
  'haslayout' => 1,
  'lang_topics' => 'user,policy',
  'assets' => '',
  'help_url' => 'Policies',
], '', true, true);
$collection['82']= $xpdo->newObject(modAction::class);
$collection['82']->fromArray([
  'id' => 82,
  'namespace' => 'core',
  'controller' => 'workspaces/package/view',
  'haslayout' => 1,
  'lang_topics' => 'workspace,namespace',
  'assets' => '',
  'help_url' => 'Package+Management',
], '', true, true);
$collection['83']= $xpdo->newObject(modAction::class);
$collection['83']->fromArray([
  'id' => 83,
  'namespace' => 'core',
  'controller' => 'security/access/policy/template/update',
  'haslayout' => 1,
  'lang_topics' => 'user,policy',
  'assets' => '',
  'help_url' => 'PolicyTemplates',
], '', true, true);
$collection['84']= $xpdo->newObject(modAction::class);
$collection['84']->fromArray([
  'id' => 84,
  'namespace' => 'core',
  'controller' => 'security/forms/profile/update',
  'haslayout' => 1,
  'lang_topics' => 'formcustomization,user,access,policy',
  'assets' => '',
  'help_url' => 'Form+Customization+Profiles',
], '', true, true);
$collection['85']= $xpdo->newObject(modAction::class);
$collection['85']->fromArray([
  'id' => 85,
  'namespace' => 'core',
  'controller' => 'security/forms/set/update',
  'haslayout' => 1,
  'lang_topics' => 'formcustomization,user,access,policy',
  'assets' => '',
  'help_url' => 'Form+Customization+Sets',
], '', true, true);
$collection['101']= $xpdo->newObject(modAction::class);
$collection['101']->fromArray([
  'id' => 101,
  'namespace' => 'core',
  'controller' => 'system/dashboards/update',
  'haslayout' => 1,
  'lang_topics' => 'dashboards,user',
  'assets' => '',
  'help_url' => 'Dashboards',
], '', true, true);
$collection['102']= $xpdo->newObject(modAction::class);
$collection['102']->fromArray([
  'id' => 102,
  'namespace' => 'core',
  'controller' => 'system/dashboards/create',
  'haslayout' => 1,
  'lang_topics' => 'dashboards,user',
  'assets' => '',
  'help_url' => 'Dashboards',
], '', true, true);
$collection['103']= $xpdo->newObject(modAction::class);
$collection['103']->fromArray([
  'id' => 103,
  'namespace' => 'core',
  'controller' => 'system/dashboards/widget/update',
  'haslayout' => 1,
  'lang_topics' => 'dashboards,user',
  'assets' => '',
  'help_url' => 'Dashboard+Widgets',
], '', true, true);
$collection['104']= $xpdo->newObject(modAction::class);
$collection['104']->fromArray([
  'id' => 104,
  'namespace' => 'core',
  'controller' => 'system/dashboards/widget/create',
  'haslayout' => 1,
  'lang_topics' => 'dashboards,user',
  'assets' => '',
  'help_url' => 'Dashboard+Widgets',
], '', true, true);
$collection['105']= $xpdo->newObject(modAction::class);
$collection['105']->fromArray([
  'id' => 105,
  'namespace' => 'core',
  'controller' => 'source/create',
  'haslayout' => 1,
  'lang_topics' => 'sources,namespace',
  'assets' => '',
  'help_url' => 'Media+Sources',
], '', true, true);
$collection['106']= $xpdo->newObject(modAction::class);
$collection['106']->fromArray([
  'id' => 106,
  'namespace' => 'core',
  'controller' => 'source/update',
  'haslayout' => 1,
  'lang_topics' => 'sources,namespace',
  'assets' => '',
  'help_url' => 'Media+Sources',
], '', true, true);
$collection['107']= $xpdo->newObject(modAction::class);
$collection['107']->fromArray([
  'id' => 107,
  'namespace' => 'core',
  'controller' => 'system/file/create',
  'haslayout' => 1,
  'lang_topics' => 'file',
  'assets' => '',
], '', true, true);

return $collection;
