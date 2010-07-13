<?php
/**
 * @package modx
 * @subpackage build
 */
$collection = array();
$collection['1']= $xpdo->newObject('modAction');
$collection['1']->fromArray(array (
  'id' => 1,
  'namespace' => 'core',
  'parent' => 0,
  'controller' => 'welcome',
  'haslayout' => 1,
  'lang_topics' => 'welcome,configcheck',
  'assets' => '',
), '', true, true);
$collection['3']= $xpdo->newObject('modAction');
$collection['3']->fromArray(array (
  'id' => 3,
  'namespace' => 'core',
  'parent' => 0,
  'controller' => 'system',
  'haslayout' => 0,
  'lang_topics' => '',
  'assets' => '',
), '', true, true);
$collection['5']= $xpdo->newObject('modAction');
$collection['5']->fromArray(array (
  'id' => 5,
  'namespace' => 'core',
  'parent' => 0,
  'controller' => 'browser',
  'haslayout' => 0,
  'lang_topics' => 'file',
  'assets' => '',
), '', true, true);
$collection['7']= $xpdo->newObject('modAction');
$collection['7']->fromArray(array (
  'id' => 7,
  'namespace' => 'core',
  'parent' => 6,
  'controller' => 'context/create',
  'haslayout' => 1,
  'lang_topics' => 'context,setting,access,policy,user',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Contexts',
), '', true, true);
$collection['8']= $xpdo->newObject('modAction');
$collection['8']->fromArray(array (
  'id' => 8,
  'namespace' => 'core',
  'parent' => 6,
  'controller' => 'context/update',
  'haslayout' => 1,
  'lang_topics' => 'context,setting,access,policy,user',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Contexts',
), '', true, true);
$collection['9']= $xpdo->newObject('modAction');
$collection['9']->fromArray(array (
  'id' => 9,
  'namespace' => 'core',
  'parent' => 6,
  'controller' => 'context/view',
  'haslayout' => 1,
  'lang_topics' => 'context',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Contexts',
), '', true, true);
$collection['10']= $xpdo->newObject('modAction');
$collection['10']->fromArray(array (
  'id' => 10,
  'namespace' => 'core',
  'parent' => 0,
  'controller' => 'element',
  'haslayout' => 1,
  'lang_topics' => 'element',
  'assets' => '',
), '', true, true);
$collection['11']= $xpdo->newObject('modAction');
$collection['11']->fromArray(array (
  'id' => 11,
  'namespace' => 'core',
  'parent' => 10,
  'controller' => 'element/chunk',
  'haslayout' => 1,
  'lang_topics' => 'chunk,category,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Chunks',
), '', true, true);
$collection['12']= $xpdo->newObject('modAction');
$collection['12']->fromArray(array (
  'id' => 12,
  'namespace' => 'core',
  'parent' => 11,
  'controller' => 'element/chunk/create',
  'haslayout' => 1,
  'lang_topics' => 'chunk,category,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Chunks',
), '', true, true);
$collection['13']= $xpdo->newObject('modAction');
$collection['13']->fromArray(array (
  'id' => 13,
  'namespace' => 'core',
  'parent' => 11,
  'controller' => 'element/chunk/update',
  'haslayout' => 1,
  'lang_topics' => 'chunk,category,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Chunks',
), '', true, true);
$collection['20']= $xpdo->newObject('modAction');
$collection['20']->fromArray(array (
  'id' => 20,
  'namespace' => 'core',
  'parent' => 10,
  'controller' => 'element/plugin',
  'haslayout' => 1,
  'lang_topics' => 'plugin,category,system_events,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Plugins',
), '', true, true);
$collection['21']= $xpdo->newObject('modAction');
$collection['21']->fromArray(array (
  'id' => 21,
  'namespace' => 'core',
  'parent' => 20,
  'controller' => 'element/plugin/create',
  'haslayout' => 1,
  'lang_topics' => 'plugin,category,system_events,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Plugins',
), '', true, true);
$collection['22']= $xpdo->newObject('modAction');
$collection['22']->fromArray(array (
  'id' => 22,
  'namespace' => 'core',
  'parent' => 20,
  'controller' => 'element/plugin/update',
  'haslayout' => 1,
  'lang_topics' => 'plugin,category,system_events,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Plugins',
), '', true, true);
$collection['25']= $xpdo->newObject('modAction');
$collection['25']->fromArray(array (
  'id' => 25,
  'namespace' => 'core',
  'parent' => 10,
  'controller' => 'element/snippet',
  'haslayout' => 1,
  'lang_topics' => 'snippet,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Snippets',
), '', true, true);
$collection['26']= $xpdo->newObject('modAction');
$collection['26']->fromArray(array (
  'id' => 26,
  'namespace' => 'core',
  'parent' => 25,
  'controller' => 'element/snippet/create',
  'haslayout' => 1,
  'lang_topics' => 'snippet,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Snippets',
), '', true, true);
$collection['27']= $xpdo->newObject('modAction');
$collection['27']->fromArray(array (
  'id' => 27,
  'namespace' => 'core',
  'parent' => 25,
  'controller' => 'element/snippet/update',
  'haslayout' => 1,
  'lang_topics' => 'snippet,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Snippets',
), '', true, true);
$collection['28']= $xpdo->newObject('modAction');
$collection['28']->fromArray(array (
  'id' => 28,
  'namespace' => 'core',
  'parent' => 10,
  'controller' => 'element/template',
  'haslayout' => 1,
  'lang_topics' => 'template,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Templates',
), '', true, true);
$collection['29']= $xpdo->newObject('modAction');
$collection['29']->fromArray(array (
  'id' => 29,
  'namespace' => 'core',
  'parent' => 28,
  'controller' => 'element/template/create',
  'haslayout' => 1,
  'lang_topics' => 'template,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Templates',
), '', true, true);
$collection['30']= $xpdo->newObject('modAction');
$collection['30']->fromArray(array (
  'id' => 30,
  'namespace' => 'core',
  'parent' => 28,
  'controller' => 'element/template/update',
  'haslayout' => 1,
  'lang_topics' => 'template,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Templates',
), '', true, true);
$collection['31']= $xpdo->newObject('modAction');
$collection['31']->fromArray(array (
  'id' => 31,
  'namespace' => 'core',
  'parent' => 28,
  'controller' => 'element/template/tvsort',
  'haslayout' => 1,
  'lang_topics' => 'template,tv,propertyset,element',
  'assets' => '',
), '', true, true);
$collection['32']= $xpdo->newObject('modAction');
$collection['32']->fromArray(array (
  'id' => 32,
  'namespace' => 'core',
  'parent' => 10,
  'controller' => 'element/tv',
  'haslayout' => 1,
  'lang_topics' => 'tv,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Template+Variables',
), '', true, true);
$collection['33']= $xpdo->newObject('modAction');
$collection['33']->fromArray(array (
  'id' => 33,
  'namespace' => 'core',
  'parent' => 32,
  'controller' => 'element/tv/create',
  'haslayout' => 1,
  'lang_topics' => 'tv,tv_widget,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Template+Variables',
), '', true, true);
$collection['34']= $xpdo->newObject('modAction');
$collection['34']->fromArray(array (
  'id' => 34,
  'namespace' => 'core',
  'parent' => 32,
  'controller' => 'element/tv/update',
  'haslayout' => 1,
  'lang_topics' => 'tv,tv_widget,propertyset,element',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Template+Variables',
), '', true, true);
$collection['35']= $xpdo->newObject('modAction');
$collection['35']->fromArray(array (
  'id' => 35,
  'namespace' => 'core',
  'parent' => 10,
  'controller' => 'element/view',
  'haslayout' => 1,
  'lang_topics' => 'element',
  'assets' => '',
), '', true, true);
$collection['36']= $xpdo->newObject('modAction');
$collection['36']->fromArray(array (
  'id' => 36,
  'namespace' => 'core',
  'parent' => 0,
  'controller' => 'resource',
  'haslayout' => 1,
  'lang_topics' => '',
  'assets' => '',
), '', true, true);
$collection['38']= $xpdo->newObject('modAction');
$collection['38']->fromArray(array (
  'id' => 38,
  'namespace' => 'core',
  'parent' => 46,
  'controller' => 'security/usergroup/create',
  'haslayout' => 1,
  'lang_topics' => 'user,access,policy,context',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/User+Groups',
), '', true, true);
$collection['39']= $xpdo->newObject('modAction');
$collection['39']->fromArray(array (
  'id' => 39,
  'namespace' => 'core',
  'parent' => 46,
  'controller' => 'security/usergroup/update',
  'haslayout' => 1,
  'lang_topics' => 'user,access,policy,context',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/User+Groups',
), '', true, true);
$collection['40']= $xpdo->newObject('modAction');
$collection['40']->fromArray(array (
  'id' => 40,
  'namespace' => 'core',
  'parent' => 36,
  'controller' => 'resource/data',
  'haslayout' => 1,
  'lang_topics' => 'resource',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Resource',
), '', true, true);
$collection['41']= $xpdo->newObject('modAction');
$collection['41']->fromArray(array (
  'id' => 41,
  'namespace' => 'core',
  'parent' => 36,
  'controller' => 'resource/empty_recycle_bin',
  'haslayout' => 1,
  'lang_topics' => 'resource',
  'assets' => '',
), '', true, true);
$collection['43']= $xpdo->newObject('modAction');
$collection['43']->fromArray(array (
  'id' => 43,
  'namespace' => 'core',
  'parent' => 36,
  'controller' => 'resource/update',
  'haslayout' => 1,
  'lang_topics' => 'resource',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Resource',
), '', true, true);
$collection['46']= $xpdo->newObject('modAction');
$collection['46']->fromArray(array (
  'id' => 46,
  'namespace' => 'core',
  'parent' => 0,
  'controller' => 'security',
  'haslayout' => 1,
  'lang_topics' => 'user',
  'assets' => '',
), '', true, true);
$collection['50']= $xpdo->newObject('modAction');
$collection['50']->fromArray(array (
  'id' => 50,
  'namespace' => 'core',
  'parent' => 46,
  'controller' => 'security/role',
  'haslayout' => 1,
  'lang_topics' => 'user',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Roles',
), '', true, true);
$collection['54']= $xpdo->newObject('modAction');
$collection['54']->fromArray(array (
  'id' => 54,
  'namespace' => 'core',
  'parent' => 53,
  'controller' => 'security/user/create',
  'haslayout' => 1,
  'lang_topics' => 'user,setting,access',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Users',
), '', true, true);
$collection['55']= $xpdo->newObject('modAction');
$collection['55']->fromArray(array (
  'id' => 55,
  'namespace' => 'core',
  'parent' => 53,
  'controller' => 'security/user/update',
  'haslayout' => 1,
  'lang_topics' => 'user,setting,access',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Users',
), '', true, true);
$collection['56']= $xpdo->newObject('modAction');
$collection['56']->fromArray(array (
  'id' => 56,
  'namespace' => 'core',
  'parent' => 46,
  'controller' => 'security/login',
  'haslayout' => 1,
  'lang_topics' => 'login',
  'assets' => '',
), '', true, true);
$collection['58']= $xpdo->newObject('modAction');
$collection['58']->fromArray(array (
  'id' => 58,
  'namespace' => 'core',
  'parent' => 57,
  'controller' => 'system/event/details',
  'haslayout' => 1,
  'lang_topics' => '',
  'assets' => '',
), '', true, true);
$collection['62']= $xpdo->newObject('modAction');
$collection['62']->fromArray(array (
  'id' => 62,
  'namespace' => 'core',
  'parent' => 3,
  'controller' => 'system/refresh_site',
  'haslayout' => 1,
  'lang_topics' => '',
  'assets' => '',
), '', true, true);
$collection['64']= $xpdo->newObject('modAction');
$collection['64']->fromArray(array (
  'id' => 64,
  'namespace' => 'core',
  'parent' => 3,
  'controller' => 'system/phpinfo',
  'haslayout' => 1,
  'lang_topics' => '',
  'assets' => '',
), '', true, true);
$collection['67']= $xpdo->newObject('modAction');
$collection['67']->fromArray(array (
  'id' => 67,
  'namespace' => 'core',
  'parent' => 36,
  'controller' => 'resource/tvs',
  'haslayout' => 0,
  'lang_topics' => '',
  'assets' => '',
), '', true, true);
$collection['70']= $xpdo->newObject('modAction');
$collection['70']->fromArray(array (
  'id' => 70,
  'namespace' => 'core',
  'parent' => 3,
  'controller' => 'system/file',
  'haslayout' => 1,
  'lang_topics' => 'file',
  'assets' => '',
), '', true, true);
$collection['71']= $xpdo->newObject('modAction');
$collection['71']->fromArray(array (
  'id' => 71,
  'namespace' => 'core',
  'parent' => 70,
  'controller' => 'system/file/edit',
  'haslayout' => 1,
  'lang_topics' => 'file',
  'assets' => '',
), '', true, true);
$collection['75']= $xpdo->newObject('modAction');
$collection['75']->fromArray(array (
  'id' => 75,
  'namespace' => 'core',
  'parent' => 65,
  'controller' => 'security/access/policy/update',
  'haslayout' => 1,
  'lang_topics' => 'user,policy',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Policies',
), '', true, true);
$collection['76']= $xpdo->newObject('modAction');
$collection['76']->fromArray(array (
  'id' => 76,
  'namespace' => 'core',
  'parent' => 36,
  'controller' => 'resource/weblink/update',
  'haslayout' => 1,
  'lang_topics' => 'resource',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Weblink',
), '', true, true);
$collection['77']= $xpdo->newObject('modAction');
$collection['77']->fromArray(array (
  'id' => 77,
  'namespace' => 'core',
  'parent' => 36,
  'controller' => 'resource/weblink/create',
  'haslayout' => 1,
  'lang_topics' => 'resource',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Weblink',
), '', true, true);
$collection['78']= $xpdo->newObject('modAction');
$collection['78']->fromArray(array (
  'id' => 78,
  'namespace' => 'core',
  'parent' => 36,
  'controller' => 'resource/symlink/update',
  'haslayout' => 1,
  'lang_topics' => 'resource',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Symlink',
), '', true, true);
$collection['79']= $xpdo->newObject('modAction');
$collection['79']->fromArray(array (
  'id' => 79,
  'namespace' => 'core',
  'parent' => 36,
  'controller' => 'resource/symlink/create',
  'haslayout' => 1,
  'lang_topics' => 'resource',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Symlink',
), '', true, true);
$collection['80']= $xpdo->newObject('modAction');
$collection['80']->fromArray(array (
  'id' => 80,
  'namespace' => 'core',
  'parent' => 36,
  'controller' => 'resource/staticresource/update',
  'haslayout' => 1,
  'lang_topics' => 'resource',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Static+Resource',
), '', true, true);
$collection['81']= $xpdo->newObject('modAction');
$collection['81']->fromArray(array (
  'id' => 81,
  'namespace' => 'core',
  'parent' => 36,
  'controller' => 'resource/staticresource/create',
  'haslayout' => 1,
  'lang_topics' => 'resource',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Static+Resource',
), '', true, true);
$collection['82']= $xpdo->newObject('modAction');
$collection['82']->fromArray(array (
  'id' => 82,
  'namespace' => 'core',
  'parent' => 212,
  'controller' => 'workspaces/package/view',
  'haslayout' => 1,
  'lang_topics' => 'workspace,namespace',
  'assets' => '',
  'help_url' => 'http://docs.modxcms.com/display/revolution/Package+Management',
), '', true, true);

return $collection;